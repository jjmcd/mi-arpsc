<?php
  /*! \file process_upload.php

\brief Process an uploaded file

process_upload first checks that the file to be uploaded
is a valid type. It the copies the file to a holding area
and sets the file protection.  The file is then opened,
the county read, and checked against a list of valid counties.
The appropriate column of data is then read and inserted
into the database.
  */
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><title>Upload</title></head>
<body>
<?php
	
  require_once ("config.php");
//! Database password
$dbpwd=$dbpassword;
  require_once ("globals.php");
  require_once ("functions.php");
  require_once ("counties.php");
include('../includes/session.inc');
include('../includes/functions.inc');

//! Get the column number in the spreadsheet for the current report
function thisColumn()
{
  // Note: This may be a little confusing, the first (0) column
  // contains a description, so the "month-th" column contains
  // this month's data, not available yet.  We want LAST month's
  // data.
  $d = getdate();
  $m = $d['mon']-1;
  if ( $m < 1 )
    $m += 12;
  return $m;
}

//! Determine the period number for the current report
function thePeriod()
{
  $m = getdate();
  return 12 * ( $m['year']-2000 ) + $m['mon'] - 2;
}

//! Get a row, add to SQL and display
function nextRow( $fh, $sql, $m )
{
  $s = fgetcsv($fh,256);
  $val = $s[$m];
  if ( strlen($val) == 0 )
    {
      $val = 'NULL';
      $style="dodgerblue";
    }
  else
    $style="mediumblue";
  $sql = $sql . $val . ",";
  echo "     <tr><td style=\"color:" . $style . ";\">" . $s[0] . 
    "</td><td style=\"color:" . $style . ";\">" . $s[$m] .
    "</td></tr>\n";
  return $sql;
}

//! Get a row, display, and return value
function specialRow( $fh, $m )
{
  $s = fgetcsv($fh,256);
  $val = $s[$m];
  if ( strlen($val) == 0 )
    {
      $val = 0;
      $style="lightblue";
    }
  else
    $style="steelblue";
  echo "     <tr><td style=\"color:" . $style . ";\">" . $s[0] . 
    "</td><td style=\"color:" . $style . ";\">" . $s[$m] .
    "</td></tr>\n";
  return $val;
}

//! Get a row and display
function unusedRow( $fh, $m )
{
  $s = fgetcsv($fh,256);
  echo "     <tr><td style=\"color: gray;\">" . $s[0] . 
    "</td><td style=\"color: gray;\">" . $s[$m] . "</td></tr>\n";
}

clearcache();

//! Set to 0 if upload successful, != 0 if not
$uploaderror = 1;
//! Display style for serious error messages
$err="<p style=\"background-color:red; color:beige; font-weight:bold;\">";

echo " <div style=\"color: steelblue; font-family: helvetica; " .
     "background-color: azure; font-size: 14px;\">\n";
//echo "<center>User:[" . $_SESSION['call'] . "]<br />\n";
//! Remember whether an error message was generated
$errmsg = 0;
//! If we have a valid file.
if (isset ($_FILES['myfile']))
  {
    if ($_FILES['myfile']['error'] == 0)
      {
	//Then we need to confirm it is of a file type we want.
	if (in_array ($_FILES['myfile']['type'],
		      $GLOBALS['allowedcsvmimetypes']))
	  {
	    //Then we can perform the copy.
	    $filename = $GLOBALS['csvfolder'] . "/" . $_FILES['myfile']['name'];
	    if (!move_uploaded_file ($_FILES['myfile']['tmp_name'], 
				     $filename ))
	      {
		echo $err . "&nbsp;There was an error uploading the file. </p>";
		$errmsg = 1;
		$GLOBALS['uploaderror'] = 1;
	      }
	    else
	      {
		// Be sure we can clean up later
		chmod( $filename, 0666 );
		// Select the correct column from the spreadsheet
		$m = thisColumn();

		$fh = fopen( $filename, "r" );
		if ( !$fh )
		  {
		    $GLOBALS['uploaderror'] = 1;
		    die($err . "can't open: $php_errormsg");
		  }

		echo " <span style=\"color:midnightblue;\">\n";

		// Get county, look up in list, get county code
		$s = fgetcsv($fh,256);
		$county = $s[6];
		if ( !in_array( $county, $cnames) )
		  {
		    $GLOBALS['uploaderror'] = 1;
		    die($err .  " &nbsp; Bad County [" . $county .
			"]</p>\n");
	          }
		$ccode = $ccodes[array_search( $county, $cnames)];
		echo "  <center><b>" . $county . " (" . $ccode .
		  ")</b><br />\n";

		// Now open the database and write the result
		//include('../includes/miscFunctions.inc');
		$db=mysql_connect("localhost",$dbuser,$dbpwd);
		if ( ! $db )
		  {
		    echo "<p>User:" . $dbuser . " Pwd:" . $dbpwd . "</p>\n";
		    die($err . " Connect db error #" . mysql_errno($db) . 
			"</p>\n");
		    echo "<p onload=document.getElementByID(\"errordiv\").innerHTML = \"ERROR\"> </p>\n";
		    $GLOBALS['uploaderror'] = 1;
		  }
		if ( ! mysql_select_db($DatabaseName,$db) )
		  {
		    die($err . " Select db error #" . mysql_errno($db) . 
			"</p>\n");
		    $GLOBALS['uploaderror'] = 1;
		  }
		$q = "SELECT COUNT(*) FROM `ares_juris` WHERE `call` = '" .
		  $_SESSION['call'] . "' AND `juris` = '" .
                  $ccode . "'";
		if ( !$res=singleResult($q,$db) )
		  {
		    $GLOBALS['uploaderror'] = 1;
		    die($err .  " &nbsp; no authorization " . $county .
			"</p>\n");
	          }


		// Generate initial SQL
		$havedata = 0;
		$sql = "INSERT INTO `arpsc_ecrept` VALUES(" .
		  thePeriod() . ",'" . $ccode . "',";

		// Open the table for results display
		echo " </span>\n <span style=\"color:mediumblue;\">\n";
		$s = fgets($fh,256);
		$s = fgets($fh,256);
		echo "  <span style=\"font-size:8px;\">\n";
		echo "   <table border=\"0\" width=50%>\n";

		// Members
		$sql = nextRow($fh,$sql,$m);
		$sql = $sql . "NULL,NULL,NULL,NULL, ";

		// Nets and meetings
		$sql = nextRow($fh,$sql,$m);

		// Nets time
		$sql = nextRow($fh,$sql,$m);

		// P.S. events
		$sql = nextRow($fh,$sql,$m);

		// P.S. hours
		$sql = nextRow($fh,$sql,$m);

		// P.S. #1 name
		$s = fgetcsv($fh,256);

		// P.S. #2 name
		$s = fgetcsv($fh,256);

		// Emergency Ops
		$sql = nextRow($fh,$sql,$m);

		// Emergency Ops Hours
		$sql = nextRow($fh,$sql,$m);

		// Em Op. #1 name
		$s = fgetcsv($fh,256);

		// Em Op #2 name
		$s = fgetcsv($fh,256);

		// Equip and Sessions get added up strangely
		$adops = 0;
		$adtime = 0;

		// Admin sessions
		$adops = $adops + specialRow($fh,$m);

		// Admin time
		$adtime = $adtime + specialRow($fh,$m);

		// Equip sessions
		$adops = $adops + specialRow($fh,$m);

		// Equip time
		$adtime = $adtime + specialRow($fh,$m);

		// Miles
		unusedRow($fh,$m);

		// Travel time
		$adtime = $adtime + specialRow($fh,$m);

		// Add all admin time into SQL
		$sql = $sql . $adops . "," . $adtime . ", ";

		// Put expenses in comments
		$s = fgetcsv($fh,256);
		$val = $s[$m];
		if ( strlen($val) == 0 )
		  $val = "NULL";
		else
		  $val = "'" . $val . "'";
		echo "     <tr><td>" . $s[0] . "</td><td>" . $s[$m] .
		  "</td></tr>\n";
		$sql = $sql . $val . ",";

		// Reporting call
		$s = fgetcsv($fh,256);
		$val = $s[$m];
		if ( strlen($val) == 0 )
		  $val = "NULL";
		else
		  $val = "'" . $val . "'";
		echo "     <tr><td>" . $s[0] . "</td><td>" . $s[$m] .
		  "</td></tr>\n";
		$sql = $sql . $val . ",NULL,now());";

		// Remaining upreported rows
		for ($i = 0; $i < 4; $i++)
		  {
		    unusedRow($fh,$m);
		  }
		echo "    </table>\n";
		echo "   </span>\n  </span>\n  </center>\n";
		$sql1 = "DELETE FROM `arpsc_ecrept` WHERE `period`=" .
		  thePeriod() . " AND `county`='" .
		  $ccode . "'";

		// Delete error not fatal so no $err
		if ( ! mysql_query($sql1,$db) )
		  {
		    echo "<p>Delete error #" . mysql_errno($db) . "</p>\n";
		    echo "<p>" . mysql_error($db) . "</p>\n";
		    }
		if ( ! mysql_query($sql,$db) )
		  {
		    die($err . " Insert error #" . mysql_errno($db) . "</p>\n");
		    $GLOBALS['uploaderror'] = 1;
		  }
		else
		  {
		  echo "  <p style=\"background-color:darkgreen;" . 
		    "color:palegreen;\"> &nbsp; Insert success </p>\n";
		  $GLOBALS['uploaderror'] = 0;
		  }
	      }
	  }
	else 
	  {
	    echo $err . " There is an error with the file (incorrect type)." .
	      " &nbsp; " . $_FILES['myfile']['type'] . "<br /> </p>\n";
	    $errmsg = 1;
	    $GLOBALS['uploaderror'] = 1;
	  }
      } 
  }
echo " </div>\n";
if ( $errmsg )
  {
    echo "<script type=\"text/javascript\"> " .
      "{ document.all.getElementByID(\"picdiv\").offsetParent.innerHTML = " .
      "\"<p> &nbsp; ERROR - see below</p>\"; } </script>\n";
    $GLOBALS['uploaderror'] = 1;
  }
else
  {
    echo "<script type=\"text/javascript\"> " .
      "{ clearcache(); document.parentElement.all.getElementByID(\"middiv\").innerHTML = " .
      "\"<p> &nbsp; Success</p>\"; } </script>\n";
    $GLOBALS['uploaderror'] = 1;
  }
?>
</body>
</html>
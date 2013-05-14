<?php
//    index.php
//    $Revision: 1.1 $ - $Date: 2007-11-07 07:44:55-05 $
//
//    index is the opening page of the mi-nts website.  It displays the
//    standard menu, and then only some text introducing the site.
//
    include('includes/session.inc');
    $title=_('ARES/RACES Member Capabilities');

    include('includes/functions.inc');

    // Remember the launch time
    $starttime = strftime("%A, %B %d %Y, %H:%M");

    // Open the database
    $db = mysql_connect($host , $dbuser, $dbpassword);
    mysql_select_db($DatabaseName,$db);

    ARESheader($title,"ARES RACES ARPSC Michigan Section");

    ARESleftBar( $db );

    echo "  <div id=\"main\">\n";
    echo "   <form name=\"volData\" method=\"post\" action=\"volData1.php\">\n";
    echo "    <h1>Member Capability Data</h1>\n";

    $SQL0 = "SELECT `id`,`description`,`needsicon`,`width` FROM `Cap_Cats` " .
      "WHERE `sequence`>0 ORDER BY `sequence`";
    $r0=getResult($SQL0,$db);

    while ( $row0 = getRow($r0,$db) )
      {
	echo "    <h2>" . $row0[1] . "</h2>\n";

        $SQL1="SELECT `id`, `short_name`, `description` " . 
	  "FROM `Capabilities` WHERE `category`=" . $row0[0] . 
	  " ORDER BY `sequence`";
        $r1=getResult($SQL1,$db);

        echo "    <table align=\"center\">\n";
        $i = 0;
        while ( $row1 = getRow($r1,$db) )
          {
	    if ( $i == 0 )
	      echo "      <tr>\n";
	    echo "        <td>\n";
	    echo "          <input type=\"checkbox\" name=\"" . $row1[0] . 
	      "\" value=\"" . $row1[0] . "\" /> " . $row1[1] . "\n";
	    if ( $row0[2]==1 )
	      {
		echo "          <a title=\"" . $row1[2] . "\" >\n";
	        echo "            <img src=\"images/q15.png\" alt=\"" .
		  $row1[2] . "\" />\n";
		echo "          </a>\n";
	      }
	    echo "        <td>\n";
	    $i = $i + 1;
	    if ( $i == $row0[3] )
	      {
	        echo "      </tr>\n";
	        $i = 0;
	      }
          }
        echo "    </table>\n";
      }
    echo "          <p><input type=\"submit\" name=\"Submit\" value=\"Submit\"></p>\n";
    echo "   </form>\n";
    echo "  </div>\n";

    sectLeaders($db);
    footer($starttime,$maxdate,
      "\$Revision: 1.1 $ - \$Date: 2012-12-18 17:02:35-04 $");
?>
</div>
</body>
</html>

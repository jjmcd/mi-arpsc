<?php
  /*! \file aresinput.php

\brief Main control for FSD-212 import

aresinput.php first checks for a valid session variable.  If
no session variable, or if the name is unreasonable, the
session is destroyed and the login screen is launched.

If the session variable exists and is reasonable,
aresinput.php then puts up a page with a file control, a Submit
button and a message area.  Pressing the Submit button launches
process_upload.php whose output is directed to a window prepared
by aresinput.php.  The Submit button presumes the user has
entered a file into the file control, most likely with 
the Browse button that is part of that control.
  */

include ('../includes/session.inc');
//! Title of the web page
$title=_('ARES Data Entry');
session_start();
//! Session variable - must be 3-7 characters
if ( strlen($_SESSION['call'])<3 )
  {
    session_destroy();
    header("Location: login.php");
  }
if ( strlen($_SESSION['call'])>7 )
  {
    session_destroy();
    header("Location: login.php");
  }
include('../includes/functions.inc');
//! Remembered launch time
$starttime = strftime("%A, %B %d %Y, %H:%M");

//! Database handle
$db=mysql_connect($host,$dbuser,$dbpassword);
mysql_select_db($DatabaseName,$db);

NTSheader($title,"FSD-212");
leftbar($db);
?>
  <div id="main" style="background-image:url(images/bgrad2-30.jpg);
    ">
    <h1 style="color:aquamarine;">
      <center>ARES FSD-212 Reporting</center></h1>

    <div style="background-image:url(images/bgrad3-30.jpg); margin: 20px;
      border-style:inset;border-width:thick;">
      <div style="margin: 10px; color:firebrick;">
        <h2>Upload csv report:</h2>

        <form id="uploadform" action="process_upload.php"
               method="post" enctype="multipart/form-data"
               target="uploadframe">

          <input type="file" id="myfile" name="myfile" />
          &nbsp;&nbsp;
          <input type="submit" value="Submit"
            onclick="uploadimg(document.getElementById('uploadform'));return false;" />
          <div id="middiv" height="40px" 
            style="background-color:aquamarine;color:blueviolet;">&nbsp;</div>

          <iframe id="uploadframe" name="uploadframe"
                  src="process_upload.php" class="noshow"
                  width="100%" height="400px"
                  style="background-color: mintcream;"></iframe>

          <div id="picdiv" <!--height="40px"
                  style="background-color:lemonchiffon;"--! > </div>
          <div id="errordiv" <!-- height="80 px" width="75%"
                  style="background-color:mistyrose;"--! > </div>
        </form>
      </div>
      <p>&nbsp;</p>
    </div>

<?php
echo "<p style=\"color:#69c;\">Logged on: " . $_SESSION['call'] . "</p>\n";
echo "  </div>\n";
sectLeaders($db);
footer($starttime,$maxdate,
       "\$Revision: 1.10 $ - \$Date: 2009-04-04 12:58:00-05 $");
?>
</div>
</body>
</html>

<?php
  /*! \file login.php

\brief FSD-212 import login screen

login.php displays a login screen requesting a callsign
and password.  When the user clicks submit, login2.php
is launched to validate the combination.
  */

include ('../includes/session.inc');
//! Titl for page
$title=_('ARES Data Entry');
session_destroy();
include('../includes/miscFunctions.inc');
//! Time page was launched
 $starttime = strftime("%A, %B %d %Y, %H:%M");

//! Database handle
//$db=mysql_connect($host,$dbuser,$dbpassword);
$db=openDatabase($dppE);
mysql_select_db($DatabaseName,$db);

NTSheader($title,"FSD-212");
leftbar($db);

//! Session variable
$_SESSION['call'] = "none";


?>
<div id="main"  style="background-image:url(images/bgrad2-30.jpg);">
  <form method="post" action="login2.php"
    style="background-image:url(images/bgrad1-30.jpg);margin:20px;
      border-style:inset;border-width:thick;border-color:#69c">
    <center>
      <h2>FSD-212 Data Import</h2>
      <h3>Sign in please</h3>
      <p>&nbsp;</p>
      <p>
        Call: <input type="text" size="8" name="call"> 
      </p>
      <p>
        Password: <input type="password" name="pwd"> 
      </p>
      <p>&nbsp;</p>
      <p>
       <input type="submit" value="Submit">
      </p>
      <p>&nbsp;</p>
    </form>
  </div>
<?php
sectLeaders($db);
footer($starttime,$maxdate,
       "\$Revision: 1.10 $ - \$Date: 2009-04-04 12:58:00-05 $");
?>
</div>
</body>
</html>

<?php
//    netinfo.php
//    $Revision: 1.1 $ - $Date: 2007-11-06 08:35:17-05 $
//
    include('includes/session.inc');
    $title=_('Michigan Section Nets');

    include('includes/functions.inc');

    $db = mysql_connect($host , $dbuser, $dbpassword);
    mysql_select_db($DatabaseName,$db);

    ARESheader($title,"ARES Net Michigan Section ARPSC RACES");

    // Remember the launch time
    $starttime = strftime("%A, %B %d %Y, %H:%M");

    ARESleftBar( $db );
?>
  <div id="main">
<p></p>
<?php
    // Initialize the latest data counter
    $maxdate=0;

    $order = $_GET['order'];
    if ( $order == '' )
	$order = 'netfullname';

    echo '<h1>Michigan Nets</h1>' . "\n";

    // Each call to ShowNets() executes the provided SQL depending on
    // getting the correct number of columns, then displays a table
    // of the information about the net

    $SQL = 'SELECT `netacro`,`netfullname`,`netmanager`,`days1`,`time1`,`freq1`,`days2`,`time2`,`freq2`,`comments`,`updated`' .
           ' FROM nets' .
           ' ORDER BY `' . $order . '`';
    Show_Nets($db,$SQL);

    echo "  </div>\n";

    sectLeaders($db);

    footer($starttime,$maxdate,
      "\$Revision: 1.1 $ - \$Date: 2007-11-06 08:35:17-05 $");
?>
</div>
</body>
</html>

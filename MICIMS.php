<?php

$titles=array(
"Full Screen Shot - Menu on left, Significant Events in foreground, Station Status list in background",
"Auxcomm Station Status List",
"Auxcomm Station Status Detail - ECs can schedule shifts ahead and move operators around",
"Activity Log Item - private unless the three boxes at the bottom are not all white",
"EM Program Status - A good overview of the state, more detail in list view",
"Dashboards - SEOC",
"Dashboards - EM",
"Dashboards - NWS",
"Incident Timeline",
"Incident Action Plan - List",
"Incident Action Plan - SEOC uses this to plan objectives for upcoming operational period.  This may go on for several pages",
"Battle Rhythm - This tells SEOC players of upcoming items",
"Situation Report - List",
"Situation Report - this is the official report on the operational period.  Again, goes on for several pages listing each agency activities, etc.",
"Training and Events Calendar",
"Training and Events Item Detail",
"Scrolling photos - List of thumbnails and slideshow",
"Dual-monitor full screen");

$files=array(
"Flood-SigEv-StaStat.png",
"Flood-StationStatus.png",
"Flood-StationStatusDetail.png",
"Flood-ActivityLogOneItem.png",
"Flood-EM-Prog-Stat.png",
"Flood-SEOC-Dashboard.png",
"Flood-EM-Dashboard.png",
"Flood-NWS-Dashboard.png",
"Flood-IncidentTimeline.png",
"Flood-IAP-List.png",
"Flood-IAP-Partial.png",
"Flood-Battle-Rhythm.png",
"Flood-SitRep-List.png",
"Flood-SitRep-Partial.png",
"T_E-Calendar.png",
"T_E-Detail.png",
"Flood-ScrollingPhotos.png",
"Flood-WideScreenShot.png");

    include('includes/session.inc');
    $title=_('Michigan Section ARES/RACES');

    include('includes/functions.inc');

    // Remember the launch time
    $starttime = strftime("%A, %B %d %Y, %H:%M");

    // Open the database
    $db = mysql_connect($host , $dbuser, $dbpassword);
    mysql_select_db($DatabaseName,$db);

    ARESheader($title,"ARES RACES ARPSC Michigan Section");

    ARESleftBar( $db );

echo "  <div id=\"main\">\n";
echo "<h1>MI CIMS Screenshots</h1>\n";
echo "<ul>\n";
for ( $i=0; $i<18; $i++ )
  {
    echo "  <li>\n    <p><a href=\"MICIMS-image.php?image=" . $files[$i] . "\">" . $titles[$i] . "</a>\n  </p></li>\n";
  }
echo "</ul>\n";

?>
    </div>
  </div>
<?php
    sectLeaders($db);
    footer($starttime,$maxdate,
      "\$Revision: 1.2 $ - \$Date: 2012-02-04 17:02:35-04 $");
?>
</div>
</body>
</html>

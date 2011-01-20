<?php
//    Summary.php
//    $Revision: 1.2 - $Date: 2008-08-10 10:12:48-04 $
//
//    arpsc_ecrept displays the FSD-212 results for a month.  If there
//    are no parameters, the most recent period is displayed, otherwise
//	the requested period is displayed.
//

include('includes/session.inc');
$title=_('Michigan Section Performance Summary');

include('includes/functions.inc');

// Remember the launch time
$starttime = strftime("%A, %B %d %Y, %H:%M");

// Open the database
$db = mysql_connect($host , $dbuser, $dbpassword);
mysql_select_db($DatabaseName,$db);

// $period=92;

    // Get the requested period, if blank choose the latest
    $period = $_GET['period'];
    if ( $period < 1 )
    {
	$SQL="SELECT MAX(`period`) FROM `arpsc_ecrept`";
	$period = singleResult($SQL,$db);;
    }

    $p9 = $period;
    $p1 = (int)((int)$p9 - 11);

    // Display the month name for this report

    $SQL = 'SELECT lastday FROM `periods` WHERE `periodno`=' . $period;
    $usedate=singleResult($SQL,$db);
    $monthnames = array('J','F','M','A','M','J','J','A','S','O','N','D');

ARESheader($title,"ARPSC Michigan Section Summary");

ARESleftBar( $db );

echo '  <div id="main">' . "\n";
echo "    <center>\n";

// CSS classes used in the table
$s0="class=\"rowS0\"";
$s1="class=\"rowS1\"";
$s1B="class=\"rowS1B\"";
$s1S="class=\"rowS1S\"";
$s1L="class=\"rowS1L\"";
$s2="class=\"rowS2\"";
$s2S="class=\"rowS2S\"";

//==================================================================================================
// F S D   2 1 2   T a b l e
//==================================================================================================
echo "    <p><h1>FSD-212 12-month Summary</h1></p>\n";
echo "  </center>\n";
echo "    <p>Each month amateur radio emergency coordinators for each county report the time spent\n";
echo "      by their programs in a number of categories.  In the table below, the total hours \n";
echo "      represents not only the emergency and public service hours, but also hours spent in nets, \n";
echo "      training, equipment maintenance and administration.</p>\n";
echo "  <center>\n";

$pshours = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
$emghours = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
$totalhours = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
$reports = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);

$p1=84;
$nmos=33;
for ($i=0; $i<$nmos; $i++)
{
    $period = $p1 + $i;

    $q3='SELECT `period`, `drillshrs`,`pseshrs`,`eopshrs`,`aresops` ' .
	"FROM `arpsc_ecrept` WHERE " .
	"`period` = " . $period;
    $r3=getResult($q3,$db);
    while ( $row3 = getRow($r3,$db) )
    {
	$pshours[$i]=$pshours[$i]+$row3[2];
	$emghours[$i]=$emghours[$i]+$row3[3];
	$totalhours[$i]=$totalhours[$i]+
	    $row3[1]+$row3[2]+$row3[3]+$row3[4];
	$reports[$i] = $reports[$i] + 1;
    }
}

echo "    <table width=\"90%\">\n";

$monthlongnames = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
$class=$s1;
echo "      <tr><th>Period</th><th>Public Service</th><th>Emergency</th><th>Total Hours</th></tr>\n";
for ($i=0; $i<$nmos; $i++)
{
    echo "      <tr>\n";
    $monthindex = ($p1 + $i) % 12;
    $year = 2000+(int)(($p1 + $i) / 12);
    echo "        <td " . $class . "><a target=\"fsd\" href=\"http://www.mi-arpsc.org/arpsc_ecrept.php?period=" . ($p1 + $i) . "\">" . $monthlongnames[$monthindex] . " " . $year . "</a></td>\n";
    echo "        <td " . $class . ">" . (int)($pshours[$i]) . "</td>\n";
    echo "        <td " . $class . ">" . (int)($emghours[$i]) . "</td>\n";
    echo "        <td " . $class . ">" . (int)($totalhours[$i]) . "</td>\n";
    echo "      </tr>\n";
}
echo "    </table>\n";
echo "    <p>&nbsp;</p>\n";
echo "    <img src=\"sum212gl.php?period=" . $p9 . "\">\n";

//==================================================================================================
// Ns e t   R e p o r t
//==================================================================================================
echo "    <p>&nbsp;</p>\n";
echo "    <p><h1>Net Report 12-month Summary</h1></p>\n";
echo "  </center>\n";
echo "    <p>Each month NTS net managers report on checkins, traffic and time for their nets.  The \n";
echo "      following table summarizes those results.  Not all nets record individual net manhours, \n";
echo "      so for some nets the manhours are estimated.  The method of estimation consistently \n";
echo "      underestimates those hours.</p>\n";
echo "  <center>\n";
echo "    <table width=\"90%\">\n";
echo "      <tr><th>Period</th><th>Checkins</th><th>Messages</th><th>Time</th><th>Sessions</th><th>Total Hours</th></tr>\n";
for ($i=0; $i<$nmos; $i++)
{
    $period = $p1 + $i;
    $q4='SELECT `period`, `qni`,`qtc`,`qtr`,`sessions`, `manhours` ' .
	"FROM `netreport` WHERE " . 
	"`period` = " . $period;
    $r4=getResult($q4,$db);
    $qni=0;
    $qtc=0;
    $qtr=0;
    $sessions=0;
    $manhours=0;
    while ( $row4 = getRow($r4,$db) )
	{
	$qni = $qni + $row4[1];
	$qtc = $qtc + $row4[2];
	$qtr = $qtr + $row4[3];
	$sessions = $sessions + $row4[4];
	if ( $row4[5] > 0 )
	    {
	    $manhours = $manhours + $row4[5];
	    }
	else
	    {
	    $manhours = $manhours + ($row4[1]*$row4[3]/$row4[4]);
	    }
	}
    echo "      <tr>\n";
    $monthindex = ($p1 + $i) % 12;
    $year = 2000+(int)(($p1 + $i) / 12);
    echo "        <td " . $class . "><a target=\"netreport\" href=\"http://www.mi-nts.org/netreport.php?period=" . ($p1 + $i) . "\">" . $monthlongnames[$monthindex] . " " . $year . "</a></td>\n";
    echo "        <td " . $class . ">" . $qni . "</td>\n";
    echo "        <td " . $class . ">" . $qtc . "</td>\n";
    echo "        <td " . $class . ">" . (int)($qtr+0.5) . "</td>\n";
    echo "        <td " . $class . ">" . $sessions . "</td>\n";
    echo "        <td " . $class . ">" . (int)($manhours+0.5) . "</td>\n";
    echo "      </tr>\n";
}
echo "    </table>\n";
echo "    <p>&nbsp;</p>\n";
echo "    <img src=\"sumnetgl.php?period=" . $p9 . "\">\n";


//==================================================================================================
// P S H R
//==================================================================================================
echo "    <p>&nbsp;</p>\n";
echo "    <p><h1>Public Service Honor Roll</h1></p>\n";
echo "  </center>\n";
echo "    <p>A small number of more active amateurs submit a monthly report to qualify for\n";
echo "      the American Radio Relay League's Public Service Honor Roll.  Categories making up \n";
echo "      the total score include not only public service and emergency operations time, but also \n";
echo "      traffic handled, nets joined, and appointments held.  Many amateurs only report these \n";
echo "      results when they have a sufficient score to qualify for inclusion in QST, so these\n";
echo "      hours do not represent a complete total.  On the other hand, some of these hours may\n";
echo "      have been included by ECs in the FSD-212 results.</p>\n";
echo "  <center>\n";
echo "    <table width=\"90%\">\n";
echo "      <tr><th>Period</th><th>Public Service Hrs</th><th>Emergency Ops Hrs</th><th>Total Score</th></tr>\n";
for ($i=0; $i<$nmos; $i++)
{
    $period = $p1 + $i;
    $q5='SELECT `period`, `cat4`,`cat5`,`total` ' .
	"FROM `pshr` WHERE " . 
	"`period` = " . $period;
    $r5=getResult($q5,$db);
    $plan=0;
    $unplan=0;
    $score=0;
    while ( $row5 = getRow($r5,$db) )
	{
	$plan = $plan + $row5[1];
	$unplan = $unplan + $row5[2];
	$score = $score + $row5[3];
	}
    echo "      <tr>\n";
    $monthindex = ($p1 + $i) % 12;
    $year = 2000+(int)(($p1 + $i) / 12);
    echo "        <td " . $class . "><a target=\"pshr\" href=\"http://www.mi-nts.org/pshr.php?period=" . ($p1 + $i) . "\">" . $monthlongnames[$monthindex] . " " . $year . "</a></td>\n";
    echo "        <td " . $class . ">" . (int)($plan/5) . "</td>\n";
    echo "        <td " . $class . ">" . (int)($unplan/5) . "</td>\n";
    echo "        <td " . $class . ">" . (int)($score) . "</td>\n";
    echo "      </tr>\n";
}
echo "    </table>\n";
echo "    <p>&nbsp;</p>\n";
echo "    <img src=\"sumpshrgl.php?period=" . $p9 . "\">\n";

//==================================================================================================
// N a v i g a t i o n   L i n k s
//==================================================================================================

echo "    <p>&nbsp;</p>\n";

$rowclass="OsRowL";
echo "    <table border=0 width=\"80%\">\n";

$q6="SELECT COUNT(*) FROM `arpsc_ecrept` WHERE `period`=";
$testperiod = (int)$p1 - 3;
$targetperiod = (int)$p9 - 3;
$q6=$q6 .  $testperiod;
$r6=getResult($q6,$db);
$row6=getRow($r6,$db);
if ($row6[0]>0)
{
    $url="Summary.php?period=" . $targetperiod;
    echo "      <td class=\"" . $rowclass . "\"><a href=\"" . $url . "\">&lt;&lt;Back 1Q</a></td>\n";
}
else
{
    echo "      <td class=\"" . $rowclass . "\">&lt;&lt;Back 1Q</td>\n";
}

$q6="SELECT COUNT(*) FROM `arpsc_ecrept` WHERE `period`=";
$testperiod = (int)$p1 - 1;
$targetperiod = (int)$p9 - 1;
$q6=$q6 .  $testperiod;
$r6=getResult($q6,$db);
$row6=getRow($r6,$db);
if ($row6[0]>0)
{
    $url="Summary.php?period=" . $targetperiod;
    echo "      <td class=\"" . $rowclass . "\"><a href=\"" . $url . "\">&lt;Back 1m</a></td>\n";
}
else
{
    echo "      <td class=\"" . $rowclass . "\">&lt;Back 1m</td>\n";
}

    echo "      <td class=\"" . $rowclass . "\">&nbsp; &nbsp;</td>\n";

$q6="SELECT COUNT(*) FROM `arpsc_ecrept` WHERE `period`=";
$testperiod = (int)$p9 + 1;
$q6=$q6 .  $testperiod;
$r6=getResult($q6,$db);
$row6=getRow($r6,$db);
if ($row6[0]>0)
{
    $url="Summary.php?period=" . $testperiod;
    echo "      <td class=\"" . $rowclass . "\"><a href=\"" . $url . "\">Fwd 1m&gt;</a></td>\n";
}
else
{
    echo "      <td class=\"" . $rowclass . "\">Fwd 1m&gt;</td>\n";
}

$q6="SELECT COUNT(*) FROM `arpsc_ecrept` WHERE `period`=";
$testperiod = (int)$p9 + 3;
$q6=$q6 .  $testperiod;
$r6=getResult($q6,$db);
$row6=getRow($r6,$db);
if ($row6[0] > 0)
{
    $url="Summary.php?period=" . $testperiod;
    echo "      <td class=\"" . $rowclass . "\"><a href=\"" . $url . "\">Fwd 1Q&gt;&gt;</a></td>\n";
}
else
{
    echo "      <td class=\"" . $rowclass . "\">Fwd 1Q&gt;&gt;</td>\n";
}

echo "    </table>\n";
echo "    <p>&nbsp;</p>\n";
echo "    </center>";
echo "  </div>\n\n";

sectLeaders($db);
footer($starttime . "Z",$maxdate,
       "\$Revision: 1.0 $ - \$Date: 2008-10-15 15:08:57-04 $");
?>
</div>
</body>
</html>

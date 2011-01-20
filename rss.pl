#! /usr/bin/perl2 -w
#
use DBI;
use POSIX;
#============================================================================
# Print the required content type
#============================================================================
sub PrintBanner
{
#  print "Content-Type: text/html\n\n";
  print "Content-Type: application/rss+xml\n\n";
#  print "<!-- Text from script -->\n";
}

#============================================================================
# Connect to the database
#============================================================================
sub ConnectToDatabase
{
  $DSN = "DBI:mysql:database=mi-nts_org_-_website";

  my $dbt = DBI->connect($DSN, "wb8rcraaaaaaaaaa", "wb8rcr" )
    or die "Can't connect";
  $dbt;
}

#============================================================================
# Get current date as RFC
#============================================================================
sub nowRFC
{
    #($sec,$min,$hr,$da,$mon,$yr,$day,$yday,$isdst) = gmtime();
    ($sec,$min,$hr,$da,$mon,$yr,$day) = gmtime();
    $str=strftime("%a, %d %b %Y %H:%M:%S GMT",
		  $sec,$min,$hr,$da,$mon,$yr);
    return $str;
}

#============================================================================
#  Convrt ISO date to RFC-822
#============================================================================
sub ISOtoRFC
{
    ($yr, $mon, $day, $hr, $min, $sec) =
	unpack("A4 x1 A2 x1 A2 x1 A2 x1 A2 x1 A2",$_[0]);
    $str=strftime("%a, %d %b %Y %H:%M:%S -0500",
		  $sec,$min,$hr,$day,$mon-1,$yr-1900);
    return $str;
}

#============================================================================
# Get the text pages
#============================================================================
sub textBlocks
{
  # Find all the pageid's for this website (>5000)
  my $q1="SELECT DISTINCT `pageid` FROM `textblocks` WHERE `pageid`>5000";
  my $st1 = $dbh->prepare($q1);
  $st1->execute();
  while ( my $r1 = $st1->fetchrow_hashref() )
    {
	# Find the most recently updated paragraph for this pageid
	my $q2="SELECT MAX(`updated`) FROM `textblocks` WHERE `pageid`="
	  . $r1->{'pageid'};
	my $st2 = $dbh->prepare($q2);
	$st2->execute();
	$r2 = $st2->fetchrow_hashref();
	my $updated=$r2->{'MAX(`updated`)'};

	# Find the title for this pageid
	my $q3="SELECT `content` FROM `textblocks` WHERE `pageid`="
	  . $r1->{'pageid'} . " AND `sequence`=0";
	my $st3 = $dbh->prepare($q3);
	$st3->execute();
	if ( $r3 = $st3->fetchrow_hashref() )
	{
	    $url = "http://arpsc.mi-nts.org/txtblk.php?topic=" . $r1->{'pageid'};
	    $sqlurl = "txtblk.php?topic=" . $r1->{'pageid'};
	    print "    <item>\n";
	    print "      <title>" . $r3->{'content'} . "</title>\n";
	    print "      <link>" . $url . "</link>\n";
	    print "      <guid isPermaLink=\"false\">" . $url . "_" . $updated . "</guid>\n";
	    print "      <pubDate>" . ISOtoRFC($updated) . "</pubDate>\n";
	    my $q4 = "SELECT `RSSDESC` FROM `webpages` WHERE `URL` = '" . $sqlurl . "'";
	    my $st4 = $dbh->prepare($q4);
	    $st4->execute();
	    if ( $r4 = $st4->fetchrow_hashref() )
	    {
		print "      <description>" . $r4->{'RSSDESC'} . "</description>\n";
	    }
	    print "    </item>\n";
        }
    }
}

#============================================================================
# Get the date for the FSD-212 page
#============================================================================
sub ecReport
{
  my $q1="SELECT MAX(`updated`) FROM `arpsc_ecrept`";
  my $st1 = $dbh->prepare($q1);
  $st1->execute();
  my $r1 = $st1->fetchrow_hashref();
  my $updated=$r1->{'MAX(`updated`)'};
  print "    <item>\n";
  print "      <title>Most recent FSD-212 Results</title>\n";
  print "      <link>http://arpsc.mi-nts.org/arpsc_ecrept.php</link>\n";
  print "      <guid isPermaLink=\"false\">http://arpsc.mi-nts.org/arpsc_ecrept.php" . $updated . "</guid>\n";
  print "      <pubDate>" . ISOtoRFC($updated) . "</pubDate>\n";
  print "      <category>\n";
  print "        <![CDATA[ Performance ]]>\n";
  print "      </category>\n";
  print "      <description>&lt;p&gt;&lt;img src=\"http://arpsc.mi-nts.org/images/fsd212.jpg\" align=\"left\" height=\"70\" width=\"50\" /&gt;Each month ECs around the state report their activity to the SEC.  This data is later summarized and forwarded to headquarters.  This page shows the most recent data available.  The data tends to change rapidly the first few days of the month.&lt;/p&gt;&lt;br clear=\"all\"&gt;</description>\n";
  print "    <media:content url=\"http://arpsc.mi-nts.org/images/fsd212.jpg\" type=\"image/jpeg\" height=\"70\" width=\"50\" />\n";
  print "    <media:text type=\"html\">Thumbnail image of FSD-212 online report</media:text>\n";
  print "    </item>\n";
}

#============================================================================
# Get the date for the net info page
#============================================================================
sub netInfo
{
  my $q1="SELECT MAX(`updated`) FROM `nets`";
  my $st1 = $dbh->prepare($q1);
  $st1->execute();
  my $r1 = $st1->fetchrow_hashref();
  my $updated=$r1->{'MAX(`updated`)'};
  print "    <item>\n";
  print "      <title>Michigan Nets</title>\n";
  print "      <link>http://arpsc.mi-nts.org/netinfo.php</link>\n";
  print "      <guid isPermaLink=\"false\">http://arpsc.mi-nts.org/netinfo.php" . $updated . "</guid>\n";
  print "      <pubDate>" . ISOtoRFC($updated) . "</pubDate>\n";
  print "      <category>\n";
  print "        <![CDATA[ Reference ]]>\n";
  print "      </category>\n";
  print "      <description>&lt;p&gt;&lt;img src=\"http://arpsc.mi-nts.org/images/nets.jpg\" align=\"left\" height=\"136\" width=\"150\" /&gt;This page shows Michigan nets, their schedules, frequencies and net managers.&lt;/p&gt;&lt;br clear=\"all\" /&gt;</description>\n";
  print "    </item>\n";
}

#============================================================================
# Get the date for the Event Calendar
#============================================================================
sub eventCalendar
{
  my $q1="SELECT MAX(`updated`) FROM `arpsc_events`";
  my $st1 = $dbh->prepare($q1);
  $st1->execute();
  my $r1 = $st1->fetchrow_hashref();
  my $updated=$r1->{'MAX(`updated`)'};
  print "    <item>\n";
  print "      <title>Event Calendar</title>\n";
  print "      <link>http://arpsc.mi-nts.org/eventcal.php</link>\n";
  print "      <guid isPermaLink=\"false\">http://arpsc.mi-nts.org/eventcal.php" . $updated . "</guid>\n";
  print "      <pubDate>" . ISOtoRFC($updated) . "</pubDate>\n";
  print "      <category>\n";
  print "        <![CDATA[ Reference ]]>\n";
  print "      </category>\n";
  print "      <description>&lt;p&gt;&lt;img src=\"http://arpsc.mi-nts.org/images/events.jpg\" align=\"left\" height=\"150\" width=\"128\" /&gt;Upcoming events for the next 90 days.&lt;/p&gt;&lt;br clear=\"all\" /&gt;</description>\n";
  print "    </item>\n";
}

#============================================================================
# Get the date for the EC list
#============================================================================
sub ecList
{
  my $q1="SELECT MAX(`updated`) FROM `arpsc_counties`";
  my $st1 = $dbh->prepare($q1);
  $st1->execute();
  my $r1 = $st1->fetchrow_hashref();
  my $upd=$r1->{'MAX(`updated`)'};
  print "    <item>\n";
  print "      <title>EC List</title>\n";
  print "      <link>http://arpsc.mi-nts.org/EClist.php</link>\n";
  print "      <guid isPermaLink=\"false\">EClist.php_" . $upd . "</guid>\n";
  print "      <pubDate>" . ISOtoRFC($upd) . "</pubDate>\n";
  print "      <category>\n";
  print "        <![CDATA[ Reference ]]>\n";
  print "      </category>\n";
  print "      <description>&lt;p&gt;&lt;img src=\"http://arpsc.mi-nts.org/images/ecs1.jpg\" align=\"left\" height=\"150\" width=\"128\" /&gt;List of Emergency Coordinators and District Emergency Coordinators for the Michigan Section.&lt;/p&gt;&lt;br clear=\"all\" /&gt;</description>\n";
  print "    </item>\n";
}


&PrintBanner;
$dbh=&ConnectToDatabase;

# Get the current time
#($SECONDS, $MINUTES, $HOURS, $DAY, $MONTH, $YEAR) = (localtime)[0,1,2,3,4,5];
$displaydate = localtime();
$displaydate=nowRFC();

print "<?xml version=\"1.0\" ?>\n";
print "<rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\" xmlns:media=\"http://search.yahoo.com/mrss/\">\n";
print "  <channel>\n";
print "    <title>Michigan Section ARES/RACES</title>\n";
print "    <link>http://arpsc.mi-nts.org</link>\n";
print "    <pubDate>" . $displaydate . "</pubDate>\n";
#print "    <pubDate>" . $YEAR+1900 . "-" . $MONTH+1 . "-" . $DAY .
#	" " . $HOURS . ":" . $MINUTES . ":" . $SECONDS . </pubDate>;
print "    <lastBuildDate>" . $displaydate . "</lastBuildDate>\n";
print "    <description>Keep up to date on what is happening within the Michigan ARES/RACES organization.  Visit this site frequently.</description>\n";
print "    <category>Amateur Radio/ARES-RACES</category>\n";
print "    <image>\n";
print "      <url>http://arpsc.mi-nts.org/images/racessm.gif</url>\n";
print "      <title>Michigan Section ARES/RACES</title>\n";
print "      <link>http://arpsc.mi-nts.org</link>\n";
print "    </image>\n";
&ecList;
&ecReport;
&netInfo;
&eventCalendar;
&textBlocks;
print "    <atom:link href=\"http://arpsc.mi-nts.org/rss.pl\" rel=\"self\" type=\"application/rss+xml\" />\n";
print "  </channel>\n";
print "</rss>\n";
exit;

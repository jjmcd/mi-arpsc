<?php
//    index.php
//    $Revision: 1.1 $ - $Date: 2007-11-07 07:44:55-05 $
//
//    index is the opening page of the mi-nts website.  It displays the
//    standard menu, and then only some text introducing the site.
//
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
?>
  <div id="main">
    <h1>Welcome</h1>
<p>
This is the site of the Michigan Section of the Amateur Radio Emergency Services.
</p>
<p>Michigan ARPSC is 
the Amateur Radio Public Service Corps and encompasses 
<a href="http://www.arrl.org/FandES/field/pscm/sec1-ch1.html">ARES</a> (Amateur Radio Emergency Services), <a href="http://www.races.net/what.html">RACES</a> (Radio Amateur Civil Emergency Services) and <a href="http://www.skywarn.org/">Skywarn</a> (a service provided in cooperation 
with the National Weather Service). ARES Events can be any event where a Communications need exists of a non-commercial nature (<a href="http://www.arrl.org/FandES/field/regulations/news/part97/">FCC Part 97 Rules</a>). ARES is administered by the <a href="http://www.arrl.org/">ARRL - American Radio Relay League</a>. RACES is any event that the Government sees a need for additional communications needs (Generally the Governor of a state or their duly appointed Representative makes the call.&nbsp;For clarification please read <a href="http://www.arrl.org/FandES/field/regulations/news/part97/">
Part 97 Rules</a>) and is administered by <a href="http://www.fema.gov/">FEMA</a>. Skywarn is administered by the <a href="http://www.noaa.gov/">NOAA - National Oceanic and Atmospheric Administration</a> National Weather Service Division. There are four 
		National Weather Service sites in Michigan -
		<a href="http://www.crh.noaa.gov/dtx/">Detroit/White Lake</a>,
		<a href="http://www.crh.noaa.gov/grr/">Grand Rapids</a>,
		<a href="http://www.crh.noaa.gov/apx/">Gaylord</a> and
		<a href="http://www.crh.noaa.gov/mqt/">Marquette</a>. </p>
<p>
Michigan is unique in that it was one of the first if not the first 
		state in the United States to recognize the need for Amateur Radio 
		Operators to be cross-trained in both ARES and RACES protocols. This 
		aids the process of assistance we provide in the sense that 
		cross-trained individuals who start out as an ARES Operator can just 
		change hats and continue the operations wearing the RACES hat. This was 
		in no small part to several individuals in the Eighth Region (Ohio and 
		Michigan in particular!) and most 
		especially to George Race, WB8BGY as well as others. </p>
<p>
Amateur Radio Operators are unique people who have a love for 
		Communications. This is one of the first Wireless Mediums that was 
		invented and has been used for everything from World Wide Communications 
		in the HF (High Frequency) Spectrum, to local Communications in the 
		VHF/UHF (Very High Frequency/Ultra High Frequency) Spectrum, to 
		Microwave Communications. Different types of ways have also been 
		perfected or discovered via Amateur Radio such as SSB (Single-Side 
		Band), AM (Amplitude Modulation), FM (Frequency Modulation), Packet (the 
		forerunner to what we know as an Internet Protocol), ATV (Amateur 
		Television), RTTY (Radio-Teletype), PSK-31 (Phase-Shift Keying) and many 
		other types of communications.
</p>
<p>
This Web Site was developed as a way for you the reader to learn 
		something about Amateur Radio and what is happening in the State of 
		Michigan.
</p>
<p>
In the sidebar on the left you will notice several items 
		including the buttons that will take you to additional pages on this 
		site. The top two items are propagation aids for Radio Operators. When 
		there are Flares or Geomagnetic Storms communications can be hard at 
		best for certain locations and bands. The Third item is the current 
		state of the Homeland Security level. All of these are important 
		functions of which we should all be aware of at any given time. 
</p>

<h1>Our Mission Statement</h1>
<p>
<ul>
<i>&quot;Develop the Michigan ARPSC Program in to a fully integrated 
communications team ready, willing and able to provide radio 
communications support to Public Service Agencies and the citizens of 
Michigan.&quot;</i>
</ul>
</p>

		<h1>MIARPSC Guidelines Document</h1>
<p>
Over approximately the past year, the DECs and a number of ECs have been working on a document to help focus who we are and what we do.  This has been a long hard road, but the result has been worth it.  After many edits, we have a document that the ARPSC leadership and the Michigan State Police leadership can all agree to.  This document is now available <a href="downloads/ARPSC_Mich_Guidelines.pdf">here</a>.<p>We will discuss this document at the upcoming EC meeting.  Those ECs wishing to roll it out to their programs before the EC meeting might want to take advantage of the <a href="downloads/Presentation_ARPSC_Guidelines.ppt">PowerPoint Presentation</a> that has also been prepared.  <i><b>Warning:</b> PowerPoint, Word and Excel documents can contain active content.  Whenever you download one of these documents or receive it in an email, you should always scan it for viruses before opening.</i><p>

		<h1>EC Meeting</h1>
<p>
A synopsis of the EC meeting which was held on October 27th is available
<a href="txtblk.php?topic=10210">here</a>.
<p>



    <div class="submitted">
      <p class="author">
        73 de WB8RCR
      </p>
    </div>
    <center>
      <img src="http://www.spc.noaa.gov/exper/mesoanalysis/activity_loop.gif" width="480px" height="346px" />
    </center>
  </div>
<?php
    sectLeaders($db);
    footer($starttime,$maxdate,
      "\$Revision: 1.1 $ - \$Date: 2007-11-07 07:44:55-05 $");
?>
</div>
</body>
</html>

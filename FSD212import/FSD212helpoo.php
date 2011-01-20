<?php


include ('../includes/session.inc');
$title=_('ARES Data Entry Help');
include('../includes/functions.inc');

$db=mysql_connect($host,$dbuser,$dbpassword);
mysql_select_db($DatabaseName,$db);

NTSheader($title,"FSD-212");
leftbar($db);


?>
<div id="main" style="background-color:lightcyan;">
    <center>
      <h2>Detailed Instructions for FSD-212 Download</h2>
    </center>
    <p>Note that if you click on one of the images below,
       a full size image will be opened in another window or tab
       allowing you to read all the text.</p>
    <h3>Save file as .csv</h3>
      <p>Open your report in Open Office Calc, Navigate to the Report tab
        and Select File->Save As...</p>
      <p><center>
        <a target="image" href="helpoo/01SaveAs.png">
          <img src="helpoo/01SaveAs.png" width="130" height="216" />
        </a></center>
      </p>
      <p>Navigate to the desired directory and select file
        type as Text CSV. (Any directory you can find again
        will do. </p>
      <p><center>
        <a target="image" href="helpoo/02csv.png">
          <img src="helpoo/02csv.png" width="312" height="128" />
        </a></center>
      <p>Be sure that the filename includes your county name.
        Also check that the file type is .csv.  You will get an
        error later if this is not correct. Click Save.
      </p>
      </p>
      <p>Dismiss the dialog noting that you may loose some features
        (Click Keep Current Format)</p>
      <p><center>
        <a target="image" href="helpoo/03format.png">
          <img src="helpoo/03format.png" width="361" height="141" />
        </a></center>
      </p>
      <p>Dismiss the dialog about field options (Click OK)</p>
      <p><center>
        <a target="image" href="helpoo/04format.png">
          <img src="helpoo/04format.png" width="419" height="129" />
        </a></center>
      </p>
      <p>Dismiss the dialog noting that you are only saving a
         single sheet (Click OK)</p>
      <p><center>
        <a target="image" href="helpoo/05single.png">
          <img src="helpoo/05single.png" />
        </a></center>
      </p>
    <h3>Log in to the upload application</h3>
      <p>Click on the upload link and when the web page appears,
        enter your call and password and click Submit.</p>
      <p><center>
        <a target="image" href="help/05login.png">
          <img src="help/05login.png" width="313" height="180" />
        </a></center>
      </p>
    <h3>Open your saved .csv</h3>
      <p>Click on the Browse button.</p>
      <p><center>
        <a target="image" href="help/06browse.png">
          <img src="help/06browse.png" width="332" height="144" />
        </a></center>
      </p>
      <p>Navigate to your saved file and click open.</p>
      <p><center>
        <a target="image" href="helpoo/07Choose.png">
          <img src="helpoo/07Choose.png" width="397" height="383" />
        </a></center>
      </p>
    <h3>Upload your file</h3>
      <p>Click on the Submit button.</p>
      <p><center>
        <a target="image" href="help/08success.png">
          <img src="help/08success.png" width="313" height="280" />
        </a></center>
      </p>
      <p><b>Important:</b> Be sure to check for the green Insert Success bar.</p>
      <p>There are a number of possible errors.  Most will result in
	 a red error bar, but some may not.  However, a successful
	 upload will always result in the green bar.
      </p>
      <p>The application will only enter data for the current month.
        If you have data for earlier months, they must be updated
        manually.  This also means you must wait for the 1st
        of the month.  The SEC may send out reports as early as the
        tenth, so try to get your reports in by the fifth.
      <p>If you are a DEC and need to upload additional files,
        simply click on Browse again.  Note that you may only upload
        valid counties, and counties for which you have jurisdiction.
      </p>
    <h3>Check your results</h3>
      <p>As soon as your upload is successful, it will
        be available in the
        <a href="http://www.mi-arpsc.org/arpsc_ecrept.php" target="rpt">
	online report.</a>  If you detect an error, you may
        resubmit the report.
      </p>

  </div>
<?php
sectLeaders($db);
footer($starttime,$maxdate,
       "\$Revision: 1.10 $ - \$Date: 2009-12-11 17:52:00-05 $");
?>
</div>
</body>
</html>

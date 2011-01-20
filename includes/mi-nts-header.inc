<?php
{

	if ( $keywords == "" )
	    $keywords="NTS Michigan 'National Traffic System' 'Michigan Section' MI";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">

<head>
<?php
	echo '  <title>' . $title . '</title>' . "\n";
	echo '  <meta name="KEYWORDS" content="' . $keywords . '">' . "\n";
?>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <style type="text/css" media="all">@import "style/drupal.css";</style>
  <link rel="alternate" type="application/rss+xml" title="RSS" href="http://www.arrl-mi.org/?q=rss.xml" />
  <link rel="shortcut icon" href="/misc/favicon.ico" type="image/x-icon" />
  <style type="text/css" media="all">@import "style/bluemarine.css";</style>
  <script type="text/javascript"> </script>
</head>

<body>
<table border="0" cellpadding="0" cellspacing="0" id="header">
  <tr>
    <td id="logo"></td>
    <td id="logo" width="200px">
      <a href="/" title="Home"><img src="images/arrlmi.gif" alt="Home" /></a></td>
      <td id="logo"><h1 class='site-name'><a href="/" title="Home">Michigan Section NTS</a></h1>
      <div class='site-slogan'>part of the National Traffic System</div>   </td>
      <td id="logo" width="125px">
      <img align="right" src="images/nts.gif" alt="NTS" /></td>
    </td>
  </tr>
  <tr>
    <td colspan="2"><div></div></td>
  </tr>
</table>
<?php
      makeMenu($db);
}
?>
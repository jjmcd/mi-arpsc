<?php
  /*! \file login2.php

\brief checks password against database

If valid password/call combination, login2.php
starts a session, adds the call to the session
data, and redirects to aresinput.php.  If invalid,
redirects back to login.
  */

  include ('../includes/session.inc');
  include('../includes/functions.inc');

  // Connect to the database
  //! Database handle
  $db=mysql_connect($host,$dbuser,$dbpassword);
  mysql_select_db($DatabaseName,$db);


  // If the call has been set by the form, grab it
  if ( isset($_POST['call']) )
    {
      $call=strtoupper($_POST['call']);
      // Too-long call might be SQL injection attempt
      if ( strlen($call) > 7 )
	$call = 'fail';
      else
	{
	  $pwd=$_POST['pwd'];
	  // Extra long pwd might be SQL injection attempt
	  if ( strlen($pwd) > 24 )
	    $call = 'fail';
	  else
	    {
	      // Read the password from the database and compare it
	      $q = "SELECT `password` FROM `ares_password` WHERE `call`='" .
		$call . "'";
	      $tpw=singleResult($q,$db);
	      // If wrong password, set to fail
	      if ( $tpw != $pwd )
		$call = 'fail';
	    }
	}
    }
// If call not set, set to fail
  else
    $call = 'fail';

  // If call == fail, return to login screen
  if ( $call == 'fail' )
    {
      session_destroy();
      header("Location: login.php");
    }
  // If call is valid, start session, set call in session
  // variable, and enter aresinput.
  else
    {
      session_start();
      $_SESSION['call'] = $call;
      header("Location: aresinput.php");
    }
?>
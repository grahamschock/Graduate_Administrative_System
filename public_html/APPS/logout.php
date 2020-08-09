<?php

  // TODO: If the user is logged in, delete the session vars to log them out 
  require_once('nav.php');

  // start the session
  session_start();
  
  if((isset($_SESSION["userid"]))) { 
    // Unset all of the session variables.
    $_SESSION = array();

    session_unset();
    session_destroy();
    session_write_close();
    setcookie(session_name(),'',0,'/');
    session_regenerate_id(true);
  } 

  // TODO: Redirect to the login page
  $home_url = 'http://' . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"],2) . '/index.php';
  header('Location: ' . $home_url);
?>

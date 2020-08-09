<?php
  // start session
  session_start();  

  // If the user is logged in, delete the session vars to log them out
  if(isset($_SESSION["userid"])) {
    $_SESSION = array(); // clears the variables in the session
    session_destroy();
  }

  header('Location: index.php');
?>

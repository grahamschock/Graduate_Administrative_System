<?php
    // Start Session
    session_start();
    
    // If user is logged in, delete the session vars and log out
    if(isset($_SESSION['user_id'])){
        session_destroy();
    }

    // Redirect to login page
    $home_url = 'http://' . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . '/login.php';
    header('Location: ' . $home_url);
?>

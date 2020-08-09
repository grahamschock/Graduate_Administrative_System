<?php 
    
    session_start();

    require_once('connectvars.php');

    $user_id = $_SESSION['userid'];
    $fname = $_POST['firstname'];
    $lname = $_POST['lastname'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $city = $_POST['city'];
    $state = $_POST['state'];

    $query = "UPDATE allusers SET fname = '$fname', lname = '$lname', address = '$address', email = '$email', city = '$city', state = '$state' WHERE ID = '$user_id'"; 
    
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  
    $data = mysqli_query($dbc, $query);

    $home_url = 'http://' . $_SERVER["HTTP_HOST"] . dirname($_SERVER['PHP_SELF']) . '/personalinfo.php';
	header('Location: ' . $home_url);


?>

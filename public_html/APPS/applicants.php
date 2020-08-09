<?php
require_once('../connectvars.php');
session_start();
$db = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$username = "";
$password = "";
$fname = "";
$lname = "";
$errors = array();

if (isset($_POST['login'])) {
	$username = mysqli_real_escape_string($db, $_POST['username']);
	$password = mysqli_real_escape_string($db, $_POST['password']);
	if (empty($username)) {
		array_push($errors, "Username is required");
	}
	if (empty($password)) {
		array_push($errors, "Password is required");
	}
	if (count($errors) == 0) {
		$query = "SELECT * FROM applicant WHERE username='$username' AND password='$password'";
		$results = mysqli_query($db, $query);
		if (mysqli_num_rows($results) == 1) {
			$_SESSION['username'] = $username;
			$_SESSION['success'] = "You are now logged in";
			header('location: viewapp.php');
		} else {
			array_push($errors, "Wrong username/password combination");
		}
	}
} else if (isset($_POST['register'])) {
	header('location: register.php');
}
?>

<!DOCTYPE html>
<html>

<head>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>

<body>
	<div class="p-3 mb-2 bg-primary text-white">
        <h1 style="text-align:center">Bronco University Application Portal</h1>
    </div>

	<div class="p-3 mb-2 bg-light text-dark">
	<form method="post" action="applicants.php">
		<div class="form-group">
			<label name="errorbox" style="color: #FF0000"><?php include('errors.php'); ?></label><br />
			<input type="text" class="form-control" name="username" placeholder="Username">
		</div>
		<div class="form-group">
			<input type="password" class="form-control" name="password" placeholder="Password">
		</div>
		<button id="btn-register" name="login" class="btn btn-primary" style="margin-bottom:5px;">Continue on Existing Application</button><br />
		<button id="btn-register" name="register" class="btn btn-primary" style="margin-bottom:5px;">Start a New Application</button>
	</form>

	<button id="backbutton" class="btn btn-danger" onclick="window.location.href = '../login.php';">Back</button>
	</div>
</body>

</html>
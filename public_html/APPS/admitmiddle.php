<?php
// get mysql database information
require_once('../connectvars.php');

session_start();

$erorr = "";
$email1 = $email2 = $email3 = "";
$aid = "";
$db = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$query = "SELECT MAX(recid) AS maxrecid FROM recLet;";
$result = mysqli_query($db, $query);
$newrecid = mysqli_fetch_array($result)['maxrecid'] + 1;

if (isset($_SESSION['aid'])) {
	$aid = $_SESSION['aid'];
	$query = "SELECT A.fname, A.lname, A.email,B.finaldeci,B.admitYear,B.admitSemes FROM applicant AS A,application AS B WHERE B.aid=$aid AND A.uid=B.uid;";
	echo $query;
	$result = mysqli_query($db, $query);
	$names = mysqli_fetch_array($result);
	$fname = $names['fname'];
	$lname = $names['lname'];
	$email = $names['email'];
	$finaldeci = $names['finaldeci'];
	$semester = $names['admitSemes'];
	$year = $names['admitYear'];
	$title = "Update on Your Application to Bronco University";
	if (!empty($email)) {
		if ($finaldeci == 1 || $finaldeci == 2) {
			$link = dirname($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) . "/acceptOffer.php";
			$link = $link . "?aid=$aid";
			echo $link;
			$msg = "Congratuations $fname $lname!\n\nYou have been admitted into Bronco University for $semester $year. Please click on the link below to accept your offer!\n$link";
			mail($email, $title, $msg);
		} else if($finaldeci == 0){
			$msg = "Dear $fname $lname!\n\nThank you for your interest in Graduate Studies at the Bronco University. We reviewed your application very carefully and note several strong features.\nThere is rigorous competition for entry into our graduate programs and your application was not among those that we were able to accept.\n\nWe encourage you to apply to other graduate schools and we wish you every success with your studies and beyond.";
			mail($email, $title, $msg);
		}
	}

	header('location: ../review.php');
} else {
	echo "Missing aid, please report to admin";
}

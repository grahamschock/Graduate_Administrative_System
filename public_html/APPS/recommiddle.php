<?php
	// get mysql database information
	require_once('../connectvars.php');  

	// get the header and nav bar
	require_once('header.php');
	require_once('nav.php');

	session_start();

	$erorr = "";
	$email1 = $email2 = $email3 = "";
	$aid = "";
	$db = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    $query = "SELECT MAX(recid) AS maxrecid FROM recLet;";
    $result = mysqli_query($db,$query);
	$newrecid = mysqli_fetch_array($result)['maxrecid']+1;

	if(isset($_SESSION['aid'])){
		$aid = $_SESSION['aid'];
		$query = "SELECT A.fname, A.lname FROM applicant AS A,application AS B WHERE B.aid=$aid AND A.uid=B.uid;";
		echo $query;
		$result = mysqli_query($db,$query);
		$names = mysqli_fetch_array($result);
		$fname = $names['fname'];
		$lname = $names['lname'];
		$title = "Inivitation to Submit Recommendation Letter";
		if(isset($_SESSION['email1'])){
			$email1 = $_SESSION['email1'];
			$recName1 = "";
			if(isset($_SESSION['recName1'])) { $recName1 = $_SESSION['recName1']; }
			if($_SESSION['update']){
				$query = "SELECT recid FROM recLet WHERE aid=$aid;";
				$result1 = mysqli_query($db,$query);
				$newrecid = mysqli_fetch_row($result1)[0];
				echo $newrecid;
				$link = dirname($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])."/recommendation.php";
				$link = $link."?recid=$newrecid&aid=$aid";
				mail($email1,$title,"Dear $recName1\nPlease submit your recommendation letter for $fname $lname at the link below:\n$link");
				$query = "UPDATE recLet SET recName = '$recName1' WHERE aid=$aid AND recid=$newrecid;";
			} else {
				$link = dirname($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])."/recommendation.php";
				$link = $link."?recid=$newrecid&aid=$aid";
				mail($email1,$title,"Dear $recName1\nPlease submit your recommendation letter for $fname $lname at the link below:\n$link");
				$query = "INSERT INTO recLet (recid,aid,recName) VALUES ('$newrecid','$aid','$recName1');";
			}
			$result = mysqli_query($db,$query);
		}

		if(isset($_SESSION['email2'])){
			$newrecid = $newrecid+1;
			$email2 = $_SESSION['email2'];
			$recName2 = "";
			if(isset($_SESSION['recName2'])) { $recName2 = $_SESSION['recName2']; }
			if($_SESSION['update']){
				$newrecid = mysqli_fetch_row($result1)[0];
				echo $newrecid;
				$link = dirname($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])."/recommendation.php";
				$link = $link."?recid=$newrecid&aid=$aid";
				mail($email2,$title,"Dear $recName2\nPlease submit your recommendation letter for $fname $lname at the link below:\n$link");
				$query = "UPDATE recLet SET recName = '$recName2' WHERE aid=$aid AND recid=$newrecid;";
			} else {
				$link = dirname($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])."/recommendation.php";
				$link = $link."?recid=$newrecid&aid=$aid";
				mail($email2,$title,"Dear $recName2\nPlease submit your recommendation letter for $fname $lname at the link below:\n$link");
				$query = "INSERT INTO recLet (recid,aid,recName) VALUES ('$newrecid','$aid','$recName2');";
			}
			$result = mysqli_query($db,$query);
		}

		if(isset($_SESSION['email3'])){
			$newrecid = $newrecid+1;
			$email3 = $_SESSION['email3'];
			$recName3 = "";
			if(isset($_SESSION['recName3'])) { $recName3 = $_SESSION['recName3']; }
			if($_SESSION['update']){
				$newrecid = mysqli_fetch_row($result1)[0];
				echo $newrecid;
				$link = dirname($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])."/recommendation.php";
				$link = $link."?recid=$newrecid&aid=$aid";
				mail($email3,$title,"Dear $recName3\nPlease submit your recommendation letter for $fname $lname at the link below:\n$link");
				$query = "UPDATE recLet SET recName = '$recName3' WHERE aid=$aid AND recid=$newrecid;";
				echo $query;
			} else {
				$link = dirname($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])."/recommendation.php";
				$link = $link."?recid=$newrecid&aid=$aid";
				mail($email3,$title,"Dear $recName3\nPlease submit your recommendation letter for $fname $lname at the link below:\n$link");
				$query = "INSERT INTO recLet (recid,aid,recName) VALUES ('$newrecid','$aid','$recName3');";
			}
			$result = mysqli_query($db,$query);
		}
		header('location: viewapp.php');
	} else {
		echo "Missing aid, please report to admin";
	}
?>

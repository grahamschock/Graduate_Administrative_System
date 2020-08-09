<?php
$page_title = "Recommendation Letter Submission";
require_once('../connectvars.php');
require_once('../header.php');
session_start();
$db = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$aid = $_GET['aid'];
$recid = $_GET['recid'];
$recname = "";//$_GET['recName'];

$query = "SELECT applicant.fname,applicant.lname FROM applicant,application WHERE application.uid=applicant.uid AND application.aid=$aid;";
$result = mysqli_query($db, $query);
$names = mysqli_fetch_array($result);
$fname = $names["fname"];
$lname = $names["lname"];
$errors = array();
$success = array();

if (isset($_FILES["fileToUpload"])) {
	$root = realpath(dirname(__FILE__));
	$target_dir = "/"."recomLetters/";
	$uploadOk = 1;
	$fileType = strtolower(pathinfo(basename($_FILES["fileToUpload"]["name"]), PATHINFO_EXTENSION));
	$target_file = $root.$target_dir . "recid=$recid"."aid=$aid.$fileType";
	// Check if image file is a actual image or fake image
	if (isset($_POST["submit"])) {
		$uploadOk = 1;
	}
	if (file_exists($target_file)) {
		array_push($errors, "Sorry, file already exists.");
		$uploadOk = 0;
	}
	// Check file size
	if ($_FILES["fileToUpload"]["size"] > 500000) {
		array_push($errors, "Sorry, your file is too large.");
		$uploadOk = 0;
	}
	// Allow certain file formats
	if (
		$fileType != "pdf" && $fileType != "doc" && $fileType != "docx"
	) {
		array_push($errors, "Sorry, only PDF, DOC and DOCX files are allowed.");
		$uploadOk = 0;
	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		array_push($errors, "Sorry, your file was not uploaded.");
		// if everything is ok, try to upload file
	} else {
		$remove_these = array(' ','`','"','\\','\'');
		$newname = str_replace($remove_these, '', $target_file);
		$tempArr = explode("/",$newname);
		$recLink = "";
		foreach($tempArr as $temp) {
			if($temp == "public_html" || $temp == "home" || $temp == "ead") {
				continue;
			}
			if($temp == "ubuntu" || $temp == "sp20DBp2-BroncoPlusPlus") {
				$recLink = $recLink . "~" . $temp;
			}
			else {
				$recLink = $recLink . "/" . $temp;
			}
		}
		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $newname)) {
			array_push($errors, "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.\n Thank you!");
			$datetime = date("Y-m-d H:i:s");
			$query = "UPDATE recLet SET date = '$datetime', recLink='$recLink' WHERE aid=$aid AND recid=$recid;";
			$result = mysqli_query($db,$query);
		} else {
			array_push($errors, "Sorry, there was an error uploading your file.");
		}
	}
}
?>

<!DOCTYPE html>
<html>

<head>
	<link rel="stylesheet" type="text/css" href="recomStyle.css">
</head>

<body>
	<div class="header">
		<h2>Please upload the recommendation letter below to recommend </h2>
		<h2 style="color: BLUE;"><?php echo $fname . " " . $lname; ?></h2>
	</div>
	<label name = "errorbox" style="color: #FF0000"><?php include('errors.php'); ?></label><br>
	<form action="" method="post" enctype="multipart/form-data">
		<div class="form-group">
			<label>Select File to upload:</label><br>
			<input style="display:block;margin: 5px auto;text-align:center;" type="file" name="fileToUpload" id="fileToUpload"><br>
			<button id="btn-submitFinal" name="submit">Upload File</button>
		</div>
	</form>
</body>

</html>

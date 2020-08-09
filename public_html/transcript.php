<?php
	session_start();

	if(!isset($_SESSION["userid"]))
		header("Location: login.php");

	$page_title = 'Transcript';
	require_once('connectvars.php');
	require_once('header.php');

	// Split into three separate digits
        $first = intdiv($_SESSION['acctype'], 100);
	$second = intdiv($_SESSION['acctype'], 10) % 10;
	$third = $_SESSION['acctype'] % 10;

	//if($_SESSION['acctype'] != 2 && $first != 6 && $second != 6 && $third != 6)
		require_once('navmenu.php');

	$gpa = 0;
	$totalCreditsTaken = 0;
	$totalCreditsEarned = 0;
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title>Transcripts</title>
  </head>
  <body>

<?php

	if(($_SESSION["acctype"] == "1") ) {
		echo '</br>';
		echo '<form action="transcript.php" method="post">';
		echo '<input type="text" id="studentIDLookup" name="studentIDLookup" placeholder="Student ID Lookup">';
		echo '</form>';

		if(!isset($_POST["studentIDLookup"]))
			return;
	}

?>	

<h3 style = "text-align:left">Transcript</h3>

<div class="container">
<!-- TEMPLATE / HEADER --!>
<div class="card text-center">
<div class="row">
<div class="col-8">
<div class="card-body">
	<p class="card-title">COURSE</p>
</div>
</div>
<div class="col-2">
<div class="card-body">
	<p class="card-title">CREDITS</p>
</div>
</div>
<div class="col-2">
<div class="card-body">
	<p class="card-title">GRADE</p>
</div>
</div>
</div>
<!-- END TEMPLATE TEST DATA BELOW --!>
<?php
	if(!isset($_SESSION["userid"])) {
		echo 'NOT SIGNED IN';
	}
	else {

	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        
	//View for traffic coming from GS page
	if (isset($_POST['view'])){
	    if($_POST['view'] != -1){
		$data = mysqli_query($dbc, "SELECT * FROM takes WHERE studentID = ". $_POST['view'] ." ORDER BY semester DESC");
		$_POST['view'] = -1;
	    }
 	//View for traffic coming from FA page
	}else if (isset($_POST['viewB'])){
	    if($_POST['viewB'] != -1){
		$data = mysqli_query($dbc, "SELECT * FROM takes WHERE studentID = ". $_POST['viewB'] ." ORDER BY semester DESC");
                $_POST['viewB'] = -1;
	    }
	}else if(!isset($_POST['studentIDLookup'])) {
		$data = mysqli_query($dbc, "SELECT * FROM takes WHERE studentID = ". $_SESSION['userid'] ." ORDER BY semester DESC");
	//If student ID loopup IS SET, we are coming from admin
	}else {
		$data = mysqli_query($dbc, "SELECT * FROM takes WHERE studentID = ". $_POST['studentIDLookup'] ." ORDER BY semester DESC");
	}

	if(mysqli_num_rows($data) >= 1) {
		$fetchingCourses = true;
		
		$row = mysqli_fetch_array($data);
		

		while($fetchingCourses) {
			$semester = $row["semester"];	
			echo '<div class="card text-center">
			<div class="p-3 mb-2 bg-secondary text-white">';
			echo $semester;
			echo "</div>";
			echo "</div>";

			

			while( ($row != NULL) && ($semester == $row["semester"]) ) {
				$data2 = mysqli_query($dbc, "SELECT title, credits FROM courses JOIN takes ON (CID = courseID) WHERE CID = '". $row["courseID"]."'");
				$row2 = mysqli_fetch_array($data2);
				$courseTitle = $row2["title"];
				$courseCredits = $row2["credits"];

				echo '<div class="card text-center">
				<div class="row">
				<div class="col-8">
				<div class="card-body">
				<h5 class="card-title">'.$courseTitle.'</h5>
				</div>
				</div>
				<div class="col-2">
				<div class="card-body">
				<h5 class="card-title">'.$courseCredits.'</h5>
				</div>
				</div>
				<div class="col-2">
				<div class="card-body">
				<h5 class="card-title">'.$row["grade"].'</h5>
				</div>
				</div>
				</div>';

				if($row["grade"] != "IP") {
					$totalCreditsTaken += $row2["credits"];

					switch($row["grade"]) {
						case "A":
							$totalCreditsEarned += ($row2["credits"] * 1);
							break;
						case "A-":
							$totalCreditsEarned += ($row2["credits"] * .925);
							break;
						case "B+":
							$totalCreditsEarned += ($row2["credits"] * .825);
							break;
						case "B":
							$totalCreditsEarned += ($row2["credits"] * .75);
							break;
						case "B-":
							$totalCreditsEarned += ($row2["credits"] * .675);
							break;
						case "C+":
							$totalCreditsEarned += ($row2["credits"] * .575);
							break;
						case "C":
							$totalCreditsEarned += ($row2["credits"] * .5);
							break;
						case "C-":
							$totalCreditsEarned += ($row2["credits"] * .425);
							break;
						case "D+":
							$totalCreditsEarned += ($row2["credits"] * .325);
							break;
						case "D":
							$totalCreditsEarned += ($row2["credits"] * .25);
							break;
						case "F":
							$totalCreditsEarned += ($row2["credits"] * 0);
							break;
					}
				}
				$row = mysqli_fetch_array($data);
			}

			 
				echo '</div>';

				if($row == NULL)
					$fetchingCourses = false;
		}

	}
	}
?>
<!-- PUT FOLLOWING ENTRY HERE AKA LOOP TO THIS POINT WITH ECHO --!>
</div>
</div>
</br>

<?php
	echo '<h4 style = "text-align:center">Total Credits: '.$totalCreditsTaken.'</h4>';
	if($totalCreditsTaken > 0) {
		$gpa = 4 * ($totalCreditsEarned / $totalCreditsTaken);
	}
	echo '<h4 style = "text-align:center">GPA: '.number_format($gpa, 2).'</h4>';
?>
		
<?php
	require_once('footer.php');
?>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  </body>
</html>



<?php
// start the session
session_start();

// get mysql database information
require_once('../connectvars.php');

// get the header and nav bar
require_once('header.php');
require_once('nav.php');

// CREATE VARIABLES
// names
$currentYear =  date("Y");

$fname = $lname = "";
$degree = $admitSemes = $admitYear = "";
$username = $password = "";
$sid = $dobirth = $ssn = $address = "";

$priorDegree1 = $priorDegree2 = "";
$pd1Place = $pd2Place = "";
$pd1GPA = $pd2GPA = 0.00;

// exams
$gre_verbal = $gre_quantitative = $gre_year = "";
$gre_adv_score = $gre_adv_subject = $gre_adv_year = "";
$toefl_score = $toefl_year = "";

// email
$email = $email1 = $email2 = $email3 = "";
$recName1 = $recName2 = $recName3 = "";

// interest and experence 
$interest = $experience = "";

// name errors
$error = "";
$fnameErr = $lnameErr = "";
$degreeErr = $admitSemesErr = $admitYearErr = "";
$usernameErr = $passwordErr = "";
$sidErr = $dobirthErr = $ssnErr = $addressErr = "";

$priorDegree1Err = $priorDegree2Err = "";

// exam erros
$gre_verbalErr = $gre_quantitativeErr = $gre_yearErr = "";
$gre_adv_scoreErr = $gre_adv_subjectErr = $gre_adv_yearErr = "";
$toefl_scoreErr = $toefl_yearErr = "";

//email
$emailErr = $email1Err = $email2Err = $email3Err = "";
$recName1Err = $recName2Err = $recName3Err = "";

//
$priordegreeErr = "";

// interest and experence
$interestErr = $experienceErr = "";

// establish connetion 
// Create connection
$db = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Check connection
if (!$db) {
	die("Connection failed: " . mysqli_connect_error());
}

// get information from the database
if (isset($_SESSION['username'])) {
	// get the username 
	$username = $_SESSION['username'];

	// get the user's applicant information
	$query = "SELECT * FROM applicant WHERE username = '$username'";
	$result = mysqli_query($db, $query);
	$row = mysqli_fetch_array($result);

	// set user applicant information
	$fname = $row['fname'];
	$lname = $row['lname'];
	$email = $row['email'];
	$sid = $row['uid'];
	$dobirth = $row['dobirth'];
	$ssn = $row['ssn'];
	$address = $row['address'];

	// set uid as session variable
	$_SESSION['uid'] = $sid;

	// get the user's application information
	$query = "SELECT * FROM applicant, application WHERE applicant.uid = application.uid AND applicant.username = '$username'";
	$result = mysqli_query($db, $query);
	$row = mysqli_fetch_array($result);

	$submit = $row['submit'];
	// if the application has been submiited please redirect 
	if (!empty($submit)) {
		if ($submit == 1) {
			echo ("<script>location.href = 'viewapp.php';</script>");
		}
	}


	// set user application information
	$aid = $row['aid'];
	$degree = $row['applyfor'];
	$experience = $row['priorExp'];
	$interest = $row['interest'];
	$admitYear = $row['admitYear'];
	$admitSemes = $row['admitSemes'];
	$priorDegree1 = $row['priorDegree1'];
	$pd1Place = $row['pd1Place'];
	$pd1GPA = $row['pd1GPA'];
	$priorDegree2 = $row['priorDegree2'];
	$pd2Place = $row['pd2Place'];
	$pd2GPA = $row['pd2GPA'];
	$email = $row['email'];
	$email1 = $row['email1'];
	$email2 = $row['email2'];
	$email3 = $row['email3'];
	$recName1 = $row['recName1'];
	$recName2 = $row['recName2'];
	$recName3 = $row['recName3'];

	// set aid as session variable
	$_SESSION['aid'] = $aid;

	// get the user's GRE information
	$query = "SELECT * FROM applicant, application, gre WHERE applicant.uid = application.uid AND applicant.username = '$username' AND application.aid = gre.aid";
	$result = mysqli_query($db, $query);
	$row = mysqli_fetch_array($result);

	// set user GRE information
	$gre_verbal = $row['verbalscore'];
	$gre_quantitative = $row['quantscore'];
	$gre_year = $row['year'];

	// get the user's GRE advanced information
	$query = "SELECT * FROM applicant, application, gresubject WHERE applicant.uid = application.uid AND applicant.username = '$username' AND application.aid = gresubject.aid";
	$result = mysqli_query($db, $query);
	$row = mysqli_fetch_array($result);

	// set user GRE advanced information
	$gre_adv_subject = $row['subject'];
	$gre_adv_score = $row['score'];
	$gre_adv_year = $row['year'];


	// get the user's TOEFL information
	$query = "SELECT * FROM applicant, application, toefl WHERE applicant.uid = application.uid AND applicant.username = '$username' AND application.aid = toefl.aid";
	$result = mysqli_query($db, $query);
	$row = mysqli_fetch_array($result);

	// set user TOEFL information
	$toefl_score = $row['score'];
	$toefl_year = $row['year'];
}
// the user is not logged in, redirect them to viewapp
else {
	echo ("<script>location.href = 'viewapp.php';</script>");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	// STUDENT INFO
	// get student name
	$fname = test_input($_POST['fname']);
	if (strlen($fname) > 32) {
		$fnameErr .= '<p style="color:red;">INVALID length</p>';
	}

	$lname = test_input($_POST['lname']);
	if (strlen($lname) > 32) {
		$lnameErr .= '<p style="color:red;">INVALID length</p>';
	}

	// (FORMAT: DATE YYYY-MM-DD)
	// get the date of birth
	if (!empty($_POST['dobirth'])) {
		$dobirth = $_POST['dobirth'];

		// get substring is from date
		$M = (int) substr($dobirth, 5, 6);
		$D = (int) substr($dobirth, 8, 9);
		$Y = (int) substr($dobirth, 0, 4);

		if (checkdate($M, $D, $Y) == false || strlen($dobirth) > 10) {
			$dobirthErr .= '<p style="color:red;">INVALID date format</p>';
		}
	}

	// get the SSN
	if (!empty($_POST['ssn'])) {
		$ssn = $_POST['ssn'];

		// check if the length and data type is correct
		if (!is_numeric($ssn) || strlen($ssn) != 9) {
			$ssn = "";
			$ssnErr .= '<p style="color:red;">INVALID input</p>';
		} else {
			// check if the SSN already exist on the databsae
			$query = "SELECT ssn FROM applicant WHERE ssn='$ssn' AND username <> '$username'";
			$result = mysqli_query($db, $query);
			$row = mysqli_fetch_assoc($result);
			if (strcasecmp($row['ssn'], $ssn) == 0) {
				$ssn = "";
				$ssnErr .= '<p style="color:red;"> Please enter a valid SSN </p>';
			}
		}
	}

	// if the SSN is empty set it as NULL
	if (empty($ssn)) {
		$ssn = 'NULL';
	}

	// get address
	$address = $_POST['address'];
	// get if the address is formated correctly
	if (!empty($address) && !preg_match('/\d+ [0-9a-zA-Z ]+/', $address)) {
		$addressErr .= '<p style="color:red;">INVALID address format </p>';
	}

	if (strlen($address) > 100) {
		$addressErr .= '<p style="color:red;">INVALID address length </p>';
	}

	// get degree information
	if (isset($_POST['degree'])) {
		$degree = $_POST['degree'];
	}

	if (isset($_POST['priorDegree1'])) {
		$priorDegree1 = $_POST['priorDegree1'];
	} else {
		$priorDegree1 = "";
	}

	if (isset($_POST['pd1Place'])) {
		$pd1Place = $_POST['pd1Place'];
	} else {
		$pd1Place = "";
	}

	if (isset($_POST['pd1GPA'])) {
		$pd1GPA = $_POST['pd1GPA'];
	} else {
		$pd1GPA = 0.00;
	}

	if (isset($_POST['priorDegree2'])) {
		$priorDegree2 = $_POST['priorDegree2'];
	} else {
		$priorDegree2 = "";
	}

	if (isset($_POST['pd2Place'])) {
		$pd2Place = $_POST['pd2Place'];
	} else {
		$pd2Place = "";
	}

	if (isset($_POST['pd2GPA'])) {
		$pd2GPA = $_POST['pd2GPA'];
	} else {
		$pd2GPA = 0.00;
	}

	// get addmission semister
	// TODO: DROP DOWN MENU FOR SEMISTER
	if (!empty($_POST['admitSemes'])) {
		if ($_POST['admitSemes'] == 'Fall') {
			$admitSemes = $_POST['admitSemes'];
		} else if ($_POST['admitSemes'] == 'Spring') {
			$admitSemes = $_POST['admitSemes'];
		} else {
			$admitSemes = "";
		}
	}

	// get admission year
	if (isset($_POST['admitYear'])) {
		$admitYear = $_POST['admitYear'];
		// check if year is formated correctly
		if (strlen($admitYear) != 4) {
			$admitYear = "";
			$admitYearErr .= '<p style="color:red;">INVALID admission year</p>';
		}
	}

	// if year is empty set it to null
	if (empty($admitYear)) {
		$admitYear = 'NULL';
	}

	// EXAM INFO
	// get gre information
	$gre_verbal = $_POST['gre_verbal'];
	if (!empty($gre_verbal) && (strlen($gre_verbal) != 3 || $gre_verbal > 170 || $gre_verbal < 130)) {
		$gre_verbal = "";
		$gre_verbalErr .= '<p style="color:red;">INVALID GRE verbal score </p>';
	}

	if (empty($gre_verbal)) {
		$gre_verbal = 'NULL';
	}

	$gre_quantitative = $_POST['gre_quantitative'];
	if (!empty($gre_quantitative) && (strlen($gre_quantitative) != 3 || $gre_quantitative > 170 || $gre_quantitative < 130)) {
		$gre_quantitative = "";
		$gre_quantitativeErr .= '<p style="color:red;">INVALID GRE quantitative score </p>';
	}

	if (empty($gre_quantitative)) {
		$gre_quantitative = 'NULL';
	}

	$gre_year = $_POST['gre_year'];
	if ((!empty($gre_year)) && strlen($gre_year) != 4) {
		$gre_year = "";
		$gre_yearErr .= '<p style="color:red;">INVALID GRE exam year</p>';
	}

	if (!empty($gre_year)) {
		if ($gre_year > $currentYear || $gre_year < 2001) {
			$gre_yearErr = '<p style="color:red;">INVALID GRE year</p>';
		}
	}

	if (empty($gre_year)) {
		$gre_year = 'NULL';
	}

	$gre_adv_score = $_POST['gre_adv_score'];
	if (!empty($gre_adv_score) && (strlen($gre_adv_score) != 3 || $gre_adv_score < 200 || $gre_adv_score > 990)) {
		$gre_adv_score = "";
		$gre_adv_scoreErr .= '<p style="color:red;">INVALID GRE advanced score </p>';
	}

	if (empty($gre_adv_score)) {
		$gre_adv_score = 'NULL';
	}

	$gre_adv_subject = $_POST['gre_adv_subject'];
	if (!empty($gre_adv_subject) && !(strcasecmp($gre_adv_subject, "biology") || strcasecmp($gre_adv_subject, "literature in english") || strcasecmp($gre_adv_subject, "mathmatics") || strcasecmp($gre_adv_subject, "physics") || strcasecmp($gre_adv_subject, "psychology"))) {
		$gre_adv_subject = "";
		$gre_adv_subjectErr .= '<p style="color:red;">INVALID GRE advanced exam subject </p>';
	}

	$gre_adv_year = $_POST['gre_adv_year'];
	if (!empty($gre_adv_year) && strlen($gre_adv_year) != 4) {
		$gre_adv_year = "";
		$gre_adv_yearErr .= '<p style="color:red;">INVALID GRE advanced exam year</p>';
	}

	if (!empty($gre_adv_year)) {
		if ($gre_adv_year > $gre_adv_year || $gre_adv_year < 2001) {
			$gre_adv_yearErr = '<p style="color:red;">INVALID GRE year</p>';
		}
	}

	if (empty($gre_adv_year)) {
		$gre_adv_year = 'NULL';
	}

	$toefl_score = $_POST['toefl_score'];
	if (!empty($toefl_score) && ($toefl_score > 120 || $toefl_score < 0)) {
		$toefl_score = "";
		$toefl_scoreErr .= '<p style="color:red;">INVALID TOEFL exam score </p>';
	}

	if (empty($toefl_score)) {
		$toefl_score = 'NULL';
	}

	$toefl_year = $_POST['toefl_year'];
	if (!empty($toefl_year) && strlen($toefl_year) != 4) {
		$toefl_year = "";
		$toefl_yearErr .= '<p style="color:red;">INVALID TOEFL exam year</p>';
	}

	if (!empty($toefl_year)) {
		if ($toefl_year > $toefl_year || $toefl_year < 2001) {
			$toefl_yearErr = '<p style="color:red;">INVALID GRE year</p>';
		}
	}

	if (empty($toefl_year)) {
		$toefl_year = 'NULL';
	}

	// EMIAL VARIFICATION
	//CHECK EMAIL FORMAT - Generate error messsage

	$email = $_POST['email'];
	if (!empty($email)) {
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$emailErr .= '<p style="color:red;"> Invalid format and please re-enter valid email </p>';
		}

		if (strlen($email) > 100) {
			$emailErr .= '<p style="color:red;"> Invalid email length </p>';
		}
	}

	$email1 = $_POST['email1'];
	if (!empty($email1)) {
		if (!filter_var($email1, FILTER_VALIDATE_EMAIL)) {
			$email1Err .= '<p style="color:red;"> Invalid format and please re-enter valid email </p>';
		}

		if (strlen($email1) > 100) {
			$email1Err .= '<p style="color:red;"> Invalid email length </p>';
		}
	}

	$email2 = $_POST['email2'];
	if (!empty($email2)) {
		if (!filter_var($email2, FILTER_VALIDATE_EMAIL)) {
			$email2Err .= '<p style="color:red;"> Invalid format and please re-enter valid email </p>';
		}

		if (strlen($email2) > 100) {
			$email2Err .= '<p style="color:red;"> Invalid email length </p>';
		}
	}

	$email3 = $_POST['email3'];
	if (!empty($email3)) {
		if (!filter_var($email3, FILTER_VALIDATE_EMAIL)) {
			$email3Err .= '<p style="color:red;"> Invalid format and please re-enter valid email </p>';
		}

		if (strlen($email3) > 100) {
			$email3Err .= '<p style="color:red;"> Invalid email length </p>';
		}
	}

	$recName1 = $_POST['recName1'];
	if (strlen($recName1) > 64) {
		$recName1Err .= '<p style="color:red;"> Invalid name length </p>';
	}

	$recName2 = $_POST['recName2'];
	if (strlen($recName2) > 64) {
		$recName2Err .= '<p style="color:red;"> Invalid name length </p>';
	}

	$recName3 = $_POST['recName3'];
	if (strlen($recName3) > 64) {
		$recName3Err .= '<p style="color:red;"> Invalid name length </p>';
	}

	// AREA OF INTEREST
	$interest = test_input($_POST['interest']);
	if (strlen($interest) > 256) {
		$interestErr .= '<p style="color:red;">INVALID length: your input should be less than 40 characters</p>';
	}

	$experience = test_input($_POST['experience']);
	if (strlen($experience) > 256) {
		$experienceErr .= '<p style="color:red;">INVALID length: your input should be less than 40 characters</p>';
	}

	if (isset($_POST['priorDegree1']) && empty($_POST['pd1Place'])) {
		$priorDegree1Err = '<p style="color:red;"> *Please specify where you completed your Bachelor\'s Degree</p>';
	}

	if (isset($_POST['priorDegree2']) && empty($_POST['pd2Place'])) {
		$priorDegree2Err = '<p style="color:red;"> *Please specify where you completed your Master\'s Degree</p>';
	}

	// SUBMIT INFORMATION 
	if (isset($_POST['submit'])) {

		// check for null values
		if (empty($fname)) {
			$fnameErr .= '<p style="color:red;"> *required </p>';
		}

		if (empty($lname)) {
			$lnameErr .= '<p style="color:red;"> *required </p>';
		}

		if (empty($dobirth)) {
			$dobirthErr .= '<p style="color:red;"> *required </p>';
		}

		if (empty($ssn)) {
			$ssnErr .= '<p style="color:red;"> *required </p>';
		}

		if (empty($address)) {
			$addressErr .= '<p style="color:red;"> *required </p>';
		}

		if (empty($degree)) {
			$degreeErr .= '<p style="color:red;"> *required </p>';
		}

		if (empty($admitSemes)) {
			$admitSemesErr .= '<p style="color:red;"> *required </p>';
		}

		if (empty($admitYear) || $admitYear == 0) {
			$admitYearErr .= '<p style="color:red;"> *required </p>';
		}

		if ($degree == 'PhD') {
			if (empty($gre_verbal) || $gre_verbal == 0) {
				$gre_verbalErr .= '<p style="color:red;"> *required for PhD students </p>';
			}

			if (empty($gre_quantitative) || $gre_quantitative == 0) {
				$gre_quantitativeErr .= '<p style="color:red;"> *required for PhD students</p>';
			}

			if (empty($gre_year) || $gre_year == 0) {
				$gre_yearErr .= '<p style="color:red;"> *required for PhD students</p>';
			}
		}

		// combaniation and email combination
		if (empty($email1) xor empty($recName1)) {
			$recName1Err .= '<p style="color:red;">INVALID length: both name and email needs to be filled out</p>';
		}

		if (empty($email2) xor empty($recName2)) {
			$recName2Err .= '<p style="color:red;">INVALID length: both name and email needs to be filled out</p>';
		}

		if (empty($email3) xor empty($recName3)) {
			$recName3Err .= '<p style="color:red;">INVALID length: both name and email needs to be filled out</p>';
		}

		$flag = 0;
		if (($gre_adv_score == 0) && (empty($gre_adv_subject)) && ($gre_adv_year == 0)) {
			$flag = 0;
		} else if ((!($gre_adv_score == 0)) && (!empty($gre_adv_subject)) && (!($gre_adv_year == 0))) {
			$flag = 0;
		} else {
			$gre_adv_scoreErr .= '<p style="color:red;"> *all entries must be filled out or left blank </p>';
			$gre_adv_subjectErr .= '<p style="color:red;"> *all entries must be filled out or left blank </p>';
			$gre_adv_yearErr .= '<p style="color:red;"> *all entries must be filled out or left blank </p>';
		}

		if (($gre_verbal == 0) && ($gre_quantitative == 0) && ($gre_year == 0)) {
			$flag = 0;
		} else if ((!($gre_verbal == 0)) && (!($gre_quantitative == 0)) && (!($gre_year == 0))) {
			$flag = 0;
		} else {
			$gre_verbalErr .= '<p style="color:red;"> *all entries must be filled out or left blank </p>';
			$gre_quantitativeErr .= '<p style="color:red;"> *all entries must be filled out or left blank </p>';
			$gre_yearErr .= '<p style="color:red;"> *all entries must be filled out or left blank </p>';
		}

		if (($toefl_score == 0) && ($toefl_year == 0)) {
			$flag = 0;
		} else if ((!($toefl_score == 0)) && (!($toefl_year == 0))) {
			$flag = 0;
		} else {
			$toefl_scoreErr .= '<p style="color:red;"> *all entries must be filled out or left blank </p>';
			$toefl_yearErr .= '<p style="color:red;"> *all entries must be filled out or left blank </p>';
		}


		// OPTIONAL
		/*
			if(empty($gre_adv_score) || $gre_adv_score == 0){
				$gre_adv_score = "";
				$gre_adv_scoreErr = '<p style="color:red;"> *required </p>';
			}
			if(empty($gre_adv_subject)){
				$gre_adv_subject = "";
				$gre_adv_subjectErr = '<p style="color:red;"> *required </p>';
			}
			if(empty($gre_adv_year) || $gre_adv_year == 0){
				$gre_adv_year = "";
				$gre_adv_yearErr = '<p style="color:red;"> *required </p>';
			}
	
			if(empty($toefl_score) || $toefl_score == 0){
				$toefl_score = "";
				$toefl_scoreErr = '<p style="color:red;"> *required </p>';
			}
			if(empty($toefl_year) || $toefl_year == 0){
				$toefl_year = "";
				$toefl_yearErr = '<p style="color:red;"> *required </p>';
			}
			*/

		if (empty($interest)) {
			$interestErr = '<p style="color:red;"> *required </p>';
		}

		if (empty($experience)) {
			$experienceErr = '<p style="color:red;"> *required </p>';
		}

		// check duplicates
		if (!empty($email1) && strcasecmp($email1, $email2) == 0) {
			$email1Err = '<p style="color:red;"> Duplicate email </p>';
			$email2Err = '<p style="color:red;"> Duplicate email </p>';
		}

		if (!empty($email1) && strcasecmp($email1, $email3) == 0) {
			$email1Err = '<p style="color:red;"> Duplicate email </p>';
			$email3Err = '<p style="color:red;"> Duplicate email </p>';
		}

		if (!empty($email2) && strcasecmp($email2, $email3) == 0) {
			$email2Err = '<p style="color:red;"> Duplicate email </p>';
			$email3Err = '<p style="color:red;"> Duplicate email </p>';
		}

		// SET email varibles 
		if (empty($email1Err) && !empty($email1)) {
			$_SESSION['email1'] = $email1;
		}

		if (empty($email2Err) && !empty($email2)) {
			$_SESSION['email2'] = $email2;
		}

		if (empty($email3Err) && !empty($email3)) {
			$_SESSION['email3'] = $email3;
		}

		if (empty($recName1Err) && !empty($recName1)) {
			$_SESSION['recName1'] = $recName1;
		}

		if (empty($recName2Err) && !empty($recName2)) {
			$_SESSION['recName2'] = $recName2;
		}

		if (empty($recName3Err) && !empty($recName3)) {
			$_SESSION['recName3'] = $recName3;
		}

		if (!(isset($_SESSION['username']))) {
			$error = '<p style="color:red;"> Error - logout and try again</p>';
		} else {
			//echo "submiting";
			if (empty($fname) || empty($lname) || empty($degree) || empty($admitSemes) || empty($admitYear) || empty($sid) || empty($dobirth) || empty($ssn) || empty($address) || empty($interest) || empty($experience) || !empty($fnameErr) || !empty($lnameErr) || !empty($degreeErr) || !empty($admitSemesErr) || !empty($admitYearErr) || !empty($sidErr) || !empty($dobirthErr) || !empty($ssnErr) || !empty($addressErr) || !empty($gre_verbalErr) || !empty($gre_quantitativeErr) || !empty($gre_yearErr) || !empty($gre_adv_scoreErr) || !empty($gre_adv_subjectErr) || !empty($gre_adv_yearErr) || !empty($toefl_scoreErr) || !empty($toefl_yearErr) || !empty($interestErr) || !empty($experienceErr) || !empty($emailErr) || !empty($email1Err) || !empty($email2Err) || !empty($email3Err) || !empty($recName1Err) || !empty($recName2Err) || !empty($recName3Err)) {

				$error = '<p style="color:red;"> Error - please complete the required fields</p>';
			} else {

				$sql = "UPDATE applicant SET fname='$fname', lname='$lname', dobirth='$dobirth', email='$email', ssn=$ssn, address='$address' WHERE uid='$sid'";
				$result = mysqli_query($db, $sql);
				if ($result) {
					//echo "Success <br> ";
				} else {
					echo "Error" . mysqli_error($db) . "<br>";
					$error = "error";
				}

				$query = 'SELECT CURDATE()';
				$date = mysqli_query($db, $query);
				$date = mysqli_fetch_assoc($date);
				$date = $date['CURDATE()'];

				$sql = "UPDATE application SET applyfor='$degree', priorDegree1='$priorDegree1', pd1Place='$pd1Place', pd1GPA='$pd1GPA', priorDegree2='$priorDegree2', pd2Place='$pd2Place',pd2GPA='$pd2GPA',priorExp='$experience', interest='$interest', admitYear=$admitYear, admitSemes='$admitSemes', complete='0',submit='1', dateSubmission='$date', email1='$email1', email2='$email2', email3='$email3', recName1='$recName1', recName2='$recName2', recName3='$recName3' WHERE aid='$aid'";
				$result = mysqli_query($db, $sql);
				if ($result) {
					//echo "Success <br> ";
				} else {
					echo "Error" . mysqli_error($db) . "<br>";
					$error = "error";
				}

				// SUBMIT GRE
				$sql = "SELECT * FROM gre WHERE aid='$aid';";
				$result = mysqli_query($db, $sql);
				if (mysqli_num_rows($result) == 0) {
					$sql = "INSERT INTO gre VALUES('$aid',$gre_verbal,$gre_quantitative,$gre_year);";
				} else {
					$sql = "UPDATE gre SET verbalscore=$gre_verbal, quantscore=$gre_quantitative, year=$gre_year WHERE aid='$aid'";
				}
				echo $sql;
				$result = mysqli_query($db, $sql);
				if ($result) {
					//echo "Success <br> ";
				} else {
					echo "Error" . mysqli_error($db) . "<br>";
					$error = "error";
				}

				// SUBMIT GRE ADVANCED
				// $sql = "SELECT * FROM gresubject WHERE aid='$aid' AND subject='$gre_adv_subject';";
				// $result = mysqli_query($db, $sql);
				// if (mysqli_num_rows($result) == 0) {
				// 	$sql = "INSERT INTO gresubject VALUES('$aid','$gre_adv_subject',$gre_adv_score,$gre_adv_year);";
				// } else {
				$sql = "UPDATE gresubject SET score=$gre_adv_score, year=$gre_adv_year WHERE aid='$aid' AND subject='$gre_adv_subject'";
				//}
				echo $sql;
				$result = mysqli_query($db, $sql);
				if ($result) {
					//echo "Success <br> ";
				} else {
					echo "Error" . mysqli_error($db) . "<br>";
					$error = "error";
				}

				// SUBMIT TOEFL
				$sql = "SELECT * FROM toefl WHERE aid='$aid';";
				$result = mysqli_query($db, $sql);
				if (mysqli_num_rows($result) == 0) {
					$sql = "INSERT INTO toefl VALUES('$aid',$toefl_score,$toefl_year);";
				} else {
					$sql = "UPDATE toefl SET score=$toefl_score, year=$toefl_year WHERE aid='$aid'";
				}
				echo $sql;
				$result = mysqli_query($db, $sql);
				if ($result) {
					//echo "Success <br> ";
				} else {
					echo "Error" . mysqli_error($db) . "<br>";
					$error = "error";
				}
			}

			if (empty($error)) {
				$_SESSION['submit'] = 1;
				echo ("<script>location.href = 'recommiddle.php';</script>");
			}
		}
	}

	// SUBMIT INFORMATION 
	if (isset($_POST['save'])) {
		//CHECK IF APPLICATION ALREADY EXISTS
		$query = "SELECT aid FROM applicant, application WHERE application.uid='$sid'";
		$result = mysqli_query($db, $query);
		$row = mysqli_fetch_assoc($result);
		$aid = $row['aid'];

		if (!(isset($_SESSION['username']))) {
			$error = '<p style="color:red;"> Error - logout and try again</p>';
		} else {
			//echo "submiting";
			if (!empty($fnameErr) || !empty($lnameErr) || !empty($degreeErr) || !empty($admitSemesErr) || !empty($admitYearErr) || !empty($sidErr) || !empty($dobirthErr) || !empty($ssnErr) || !empty($addressErr) || !empty($priorDegree1Err) || !empty($priorDegree2Err) || !empty($gre_verbalErr) || !empty($gre_quantitativeErr) || !empty($gre_yearErr) || !empty($gre_adv_scoreErr) || !empty($gre_adv_subjectErr) || !empty($gre_adv_yearErr) || !empty($toefl_scoreErr) || !empty($toefl_yearErr) || !empty($interestErr) || !empty($experienceErr) || !empty($emailErr) || !empty($email1Err) || !empty($email2Err) || !empty($email3Err) || !empty($recName1Err) || !empty($recName2Err) || !empty($recName3Err)) {

				$error = '<p style="color:red;"> Error - INVALID inputs please try again before submiting</p>';
			} else {

				if (empty($dobirth)) {
					$sql = "UPDATE applicant SET fname='$fname', lname='$lname', email='$email', ssn=$ssn, address='$address' WHERE uid='$sid'";
				} else {
					$sql = "UPDATE applicant SET fname='$fname', lname='$lname', email='$email', dobirth='$dobirth', ssn=$ssn, address='$address' WHERE uid='$sid'";
				}

				$result = mysqli_query($db, $sql);
				if ($result) {
					//echo "Success <br> ";
				} else {
					echo "Error" . mysqli_error($db) . "<br>";
					$error = "error";
				}

				$sql = "UPDATE application SET applyfor='$degree', priorDegree1='$priorDegree1',pd1Place='$pd1Place', pd1GPA='$pd1GPA', priorDegree2='$priorDegree2',pd2Place='$pd2Place', pd2GPA='$pd2GPA', priorExp='$experience', interest='$interest', admitYear=$admitYear, admitSemes='$admitSemes', email1='$email1', email2='$email2', email3='$email3', recName1='$recName1', recName2='$recName2', recName3='$recName3' WHERE aid='$aid'";
				$result = mysqli_query($db, $sql);
				if ($result) {
					//echo "Success <br> ";
				} else {
					echo "Error" . mysqli_error($db) . "<br>";
					$error = "error";
				}

				// SUBMIT GRE
				$sql = "SELECT * FROM gre WHERE aid='$aid';";
				$result = mysqli_query($db, $sql);
				if (mysqli_num_rows($result) == 0) {
					$sql = "INSERT INTO gre VALUES('$aid',$gre_verbal,$gre_quantitative,$gre_year);";
				} else {
					$sql = "UPDATE gre SET verbalscore=$gre_verbal, quantscore=$gre_quantitative, year=$gre_year WHERE aid='$aid'";
				}
				echo $sql;
				$result = mysqli_query($db, $sql);
				if ($result) {
					//echo "Success <br> ";
				} else {
					echo "Error" . mysqli_error($db) . "<br>";
					$error = "error";
				}

				// SUBMIT GRE ADVANCED
				// $sql = "SELECT * FROM gresubject WHERE aid='$aid' AND subject='$gre_adv_subject';";
				// $result = mysqli_query($db, $sql);
				// if (mysqli_num_rows($result) == 0) {
				// 	$sql = "INSERT INTO gresubject VALUES('$aid','$gre_adv_subject',$gre_adv_score,$gre_adv_year);";
				// } else {
					$sql = "UPDATE gresubject SET score=$gre_adv_score, year=$gre_adv_year WHERE aid='$aid' AND subject='$gre_adv_subject'";
				//}
				echo $sql;
				$result = mysqli_query($db, $sql);
				if ($result) {
					//echo "Success <br> ";
				} else {
					echo "Error" . mysqli_error($db) . "<br>";
					$error = "error";
				}

				// SUBMIT TOEFL
				$sql = "SELECT * FROM toefl WHERE aid='$aid';";
				$result = mysqli_query($db, $sql);
				if (mysqli_num_rows($result) == 0) {
					$sql = "INSERT INTO toefl VALUES('$aid',$toefl_score,$toefl_year);";
				} else {
					$sql = "UPDATE toefl SET score=$toefl_score, year=$toefl_year WHERE aid='$aid'";
				}
				echo $sql;
				$result = mysqli_query($db, $sql);
				if ($result) {
					//echo "Success <br> ";
				} else {
					echo "Error" . mysqli_error($db) . "<br>";
					$error = "error";
				}
			}

			if (empty($error)) {
				$_SESSION['save'] = 1;
				echo ("<script>location.href = 'viewapp.php';</script>");
			}
		}
	}
}

// SET THESE VARIABLE TO NULL to display "" because they're NULL on the database
if ($ssn == 0) {
	$ssn = "";
}

if ($admitYear == 0) {
	$admitYear = "";
}

if ($gre_verbal == 0) {
	$gre_verbal = "";
}

if ($gre_quantitative == 0) {
	$gre_quantitative = "";
}

if ($gre_year == 0) {
	$gre_year = "";
}

if ($gre_adv_score == 0) {
	$gre_adv_score = "";
}

if ($gre_adv_year == 0) {
	$gre_adv_year = "";
}

if ($toefl_score == 0) {
	$toefl_score = "";
}

if ($toefl_year == 0) {
	$toefl_year = "";
}

// test input for invalid entry
function test_input($input)
{
	$input = trim($input);
	$input = stripcslashes($input);
	$input = htmlspecialchars($input);
	return $input;
}

// defug function
function debug()
{
	echo "First Name : " . $fname . "<br>";
	echo "Last Name : " . $lname . "<br>";

	echo "SID : " . $sid . "<br>";
	echo "Date of Birth : " . $dobirth . "<br>";
	echo "SSN : " . $ssn . "<br>";
	echo "Address : " . $address . "<br>";

	echo "Admit seems : " . $admitSemes . "<br>";
	echo "Admit year : " . $admitYear . "<br>";

	echo "Degree : " . $degree . "<br>";

	echo "GRE Verbal : " . $gre_verbal . "<br>";
	echo "GRE Quantitative : " . $gre_quantitative . "<br>";
	echo "GRE Year : " . $gre_year . "<br>";

	echo "GRE Advanced Score : " . $gre_adv_score . "<br>";
	echo "GRE Advanced Subject : " . $gre_adv_subject . "<br>";
	echo "GRE Advanced Year : " . $gre_adv_year . "<br>";

	echo "TOEEFL Score : " . $toefl_score . "<br>";
	echo "TOEEFL Year : " . $toefl_year . "<br>";

	echo "Interest : " . $interest . "<br>";
	echo "Experience : " . $experience . "<br>";

	echo $fnameErr;
	echo $lnameErr;
	echo $degreeErr;
	echo $admitYearErr;
	echo $admitYearErr;
	echo $sidErr;
	echo $dobirthErr;
	echo $ssnErr;
	echo $addressErr;
	echo $gre_verbalErr;
	echo $gre_quantitativeErr;
	echo $gre_yearErr;
	echo $gre_adv_scoreErr;
	echo $gre_adv_subjectErr;
	echo $gre_adv_yearErr;
	echo $toefl_scoreErr;
	echo $toefl_yearErr;
	echo $interestErr;
	echo $experienceErr;
}
?>

<!DOCTYPE html>
<html>

<body>
	<!-- APPLICATION -->
	<div class="apps">

		<!-- FORM -->
		<form action="#" method="post">

			<!-- NAME -->
			<section id="applicant-information" class="name-section">
				<div class="container">
					<div class="card">
						<h1> APPS </h1>
						<?php echo $error ?>
						<h2 class="card-header">APPLICANT INFORMATION</h2>
						<div class="card-body">

							<!-- APPLICANT INFORMATION -->
							<div class="form-row">

								<!-- NAME -->
								<div class="col">
									<label for="name"> First Name: </label>
									<?php echo $fnameErr ?>
									<input type="text" class="form-control" name="fname" placeholder="First name" value=<?php echo $fname ?>><br>
								</div>

								<div class="col">
									<label for="name"> Last Name: </label>
									<?php echo $lnameErr ?>
									<input type="text" class="form-control" name="lname" placeholder="Last name" value=<?php echo $lname ?>><br>
								</div>

								<!-- Email Address -->
								<div class="col">
									<label for="student-id"> Email: </label>
									<?php echo $emailErr; ?>
									<input type="text" class="form-control" name="email" placeholder="Email" value=<?php echo $email ?>><br><br>
								</div>

								<!-- STUDENT ID -->
								<div class="col">
									<label for="student-id"> Student ID: *can not be changed </label>
									<?php echo $sidErr ?>
									<input type="text" class="form-control" name="sid" placeholder="Student ID" value=<?php echo $sid ?> disabled><br><br>
								</div>


								<!-- INFORMATION -->
								<div class="col">
									<label for="dobirth"> Date of Birth (YYYY-MM-DD): </label>
									<?php echo $dobirthErr ?>
									<input type="text" class="form-control" name="dobirth" placeholder="Date of Birth" value=<?php echo $dobirth ?>><br><br>
								</div>

								<!-- SSN -->
								<div class="col">
									<label for="ssn"> SSN: </label>
									<?php echo $ssnErr ?>
									<input type="password" class="form-control" name="ssn" placeholder="SSN" id="ssn" value=<?php echo $ssn ?>>
									<input type="checkbox" onclick="myFunction()"> Show SSN <br><br>
								</div>

								<script>
									function myFunction() {
										var x = document.getElementById("ssn");
										if (x.type === "password") {
											x.type = "text";
										} else {
											x.type = "password";
										}
									}
								</script>

								<!-- ADDRESS -->
								<div class="col">
									<label for="address"> Address: </label>
									<?php echo $addressErr ?>
									<input type="text" class="form-control" name="address" placeholder="Address" value=<?php echo '"' . $address . '"' ?>><br><br>
								</div>
							</div>

							<!-- Prior Degree -->
							<div class="form-check">
								<label for="degree">Prior Degrees: </label><br>
								<?php echo $priordegreeErr ?>
								<?php echo $priorDegree1Err ?>
								<input type="checkbox" onclick="pdFunc('pd1holder',this.checked)" class="form-check-input" name="priorDegree1" value="B" <?php if ($priorDegree1 == 'B') {
																																								echo "checked";
																																							} ?>>
								<label class="form-check-label" for="B"> Bachelor's Degree </label>
								<div id="pd1holder" <?php if ($priorDegree1 != 'B') {
														echo "style=\"display:none;\"";
													} else {
														echo "style=\"display:inline;\"";
													} ?>>
									<input name="pd1Place" id="pd1Place" placeholder="Please specify where did you complete your Bachelor's Degree." style="width:500px;" type="text" value=<?php if ($pd1Place != "") {
																																																echo '"' . $pd1Place . '"';
																																															} ?>>
									<input name="pd1GPA" id="pd1GPA" placeholder="GPA" style="width:50px;" type="number" min="0.00" max="4.00" step="0.01" value=<?php if ($pd1GPA != NULL) {
																																										echo '"' . $pd1GPA . '"';
																																									} ?>>
								</div><br>
								<?php echo $priorDegree2Err ?>
								<input type="checkbox" onclick="pdFunc('pd2holder', this.checked)" class="form-check-input" name="priorDegree2" value="MS" <?php if ($priorDegree2 == 'MS') {
																																								echo "checked";
																																							} ?>>
								<label class="form-check-label" for="exampleCheck1"> Master's Degree </label>
								<div id="pd2holder" <?php if ($priorDegree2 != 'MS') {
														echo "style=\"display:none;\"";
													} else {
														echo "style=\"display:inline;\"";
													} ?>>
									<input name="pd2Place" id="pd2Place" placeholder="Please specify where did you complete your Master's Degree." style="width:500px;margin-left:15px;" type="text" value=<?php if ($pd2Place != "") {
																																																				echo '"' . $pd2Place . '"';
																																																			} ?>>
									<input name="pd2GPA" id="pd2GPA" placeholder="GPA" style="width:50px;" type="number" min="0.00" max="4.00" step="0.01" value=<?php if ($pd2GPA != NULL) {
																																										echo '"' . $pd2GPA . '"';
																																									} ?> type="hidden">
								</div><br><br>
							</div>

							<script>
								function pdFunc(id, checked) {
									if (checked == false) {
										document.getElementById(id).style.display = "none";
										if (id == "pd1holder") {
											document.getElementById('pd1Place').setAttribute('placeholder', 'Please specify where did you complete your Bachelor\'s Degree.');
											<?php $pd1Place = ""; ?>
										} else {
											document.getElementById('pd2Place').setAttribute('placeholder', 'Please specify where did you complete your Master\'s Degree.');
											<?php $pd2Place = ""; ?>
										}
									} else {
										document.getElementById(id).style.display = "inline";
									}
								}
							</script>

							<!-- DEGREE -->
							<div class="form-check">
								<label for="degree">Applying for Degree: </label><br>
								<?php echo $degreeErr ?>

								<input type="radio" class="form-check-input" name="degree" value="MS" <?php if (strcasecmp($degree, 'MS') == 0) {
																											echo 'checked=checked';
																										} ?>>
								<label class="form-check-label" for="ms">MS</label><br>

								<input type="radio" class="form-check-input" name="degree" value="PhD" <?php if (strcmp($degree, 'PhD') == 0) {
																											echo 'checked=checked';
																										} ?>>
								<label class="form-check-label" for="phd">PhD</label><br><br>
							</div>

							<div class="form-row">

								<div class="col">
									<label for="admitSemes">Admission semester: </label>
									<?php echo $admitSemesErr ?>
									<select class="form-control" id="admitSemes" name="admitSemes">
										<option value="0" selected disabled>Semester</option>
										<option value="Fall" <?php if ($admitSemes == 'Fall') {
																	echo 'selected="selected"';
																} ?>> Fall </option>
										<option value="Spring" <?php if ($admitSemes == 'Spring') {
																	echo 'selected="selected"';
																} ?>> Spring </option>
									</select><br>
								</div>

								<div class="col">
									<label for="admitYear">Admission year: </label>
									<?php echo $admitYearErr; ?>
									<select class="form-control" id="admitYear" name="admitYear">
										<option value="" selected disabled>Year</option>
										<option value=<?php echo '"' . $currentYear . '"'; ?> <?php if ($admitYear == $currentYear) {
																									echo 'selected="selected"';
																								} ?>> <?php echo $currentYear; ?> </option>
										<option value=<?php echo '"' . ($currentYear + 1) . '"';  ?> <?php if ($admitYear == ($currentYear + 1)) {
																										echo 'selected="selected"';
																									} ?>> <?php echo $currentYear + 1; ?> </option>
									</select><br>
								</div>

							</div>
						</div>
					</div>
				</div>
			</section>


			<!-- EXAM -->
			<section id="exam" class="exam-section">
				<div class="container">
					<div class="card">
						<h2 class="card-header">EXAM</h2>
						<div class="card-body">

							<div class="form-row">

								<!-- GRE -->
								<h3 for="gre">GRE</h3>
								<div class="col">
									<label for="gre">Verbal:</label>
									<?php echo $gre_verbalErr ?>
									<input type="text" class="form-control" name="gre_verbal" placeholder="Verbal" value=<?php echo $gre_verbal ?>><br>
								</div>

								<div class="col">
									<label for="gre">Quantitative:</label>
									<?php echo $gre_quantitativeErr ?>
									<input type="text" class="form-control" name="gre_quantitative" placeholder="Quantitative" value=<?php echo $gre_quantitative ?>><br>
								</div>

								<div clas="col">
									<label for="gre">Year:</label>
									<?php echo $gre_yearErr ?>
									<input type="text" class="form-control" name="gre_year" placeholder="Year" value=<?php echo $gre_year ?>><br><br>
								</div>

								<!-- GRE Advanced -->
								<h3 for="gre_advanced">GRE Advanced</h3>
								<div class="col">
									<label for="gre_advanced">Score:</label>
									<?php echo $gre_adv_scoreErr ?>
									<input type="text" class="form-control" name="gre_adv_score" placeholder="Score" value=<?php echo $gre_adv_score ?>><br>
								</div>

								<div class="col">
									<label for="gre_advanced">Subject:</label>
									<?php echo $gre_adv_subjectErr ?>
									<input type="text" class="form-control" name="gre_adv_subject" placeholder="Subject" value=<?php echo $gre_adv_subject ?>><br>
								</div>

								<div class="col">
									<label for="gre_advanced">Year:</label>
									<?php echo $gre_adv_yearErr ?>
									<input type="text" class="form-control" name="gre_adv_year" placeholder="Year" value=<?php echo $gre_adv_year ?>><br><br>
								</div>


								<!-- TOEEFL -->
								<h3 for="toefl">TOEEFL</h3>
								<div class="col">
									<label for="toefl">Score:</label>
									<?php echo $toefl_scoreErr ?>
									<input type="text" class="form-control" name="toefl_score" placeholder="Score" value=<?php echo $toefl_score ?>><br>
								</div>

								<div class="col">
									<label for="toefl">Year:</label>
									<?php echo $toefl_yearErr ?>
									<input type="text" class="form-control" name="toefl_year" placeholder="Year" value=<?php echo $toefl_year ?>><br><br>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>


			<!-- RECOMMENDER -->
			<section id="rec" class="rec-section">
				<div class="container">
					<div class="card">
						<h2 class="card-header">RECOMMENDER</h2>
						<div class="card-body">
							<div class="form-group">

								<label for="rec">Recommender Name:</label>
								<?php echo $recName1Err ?>
								<input type="text" class="form-control" name="recName1" placeholder="Recommender name" value=<?php echo '"' . $recName1 . '"' ?>><br>

								<label for="rec">Recommender Email:</label>
								<?php echo $email1Err ?>
								<input type="text" class="form-control" name="email1" placeholder="Recommender email" value=<?php echo $email1 ?>><br><br><br>


								<label for="rec">Recommender Name:</label>
								<?php echo $recName2Err ?>
								<input type="text" class="form-control" name="recName2" placeholder="Recommender name" value=<?php echo '"' . $recName2 . '"' ?>><br>

								<label for="rec">Recommender Email:</label>
								<?php echo $email2Err ?>
								<input type="text" class="form-control" name="email2" placeholder="Recommender email" value=<?php echo $email2 ?>><br><br><br>


								<label for="rec">Recommender Name:</label>
								<?php echo $recName3Err ?>
								<input type="text" class="form-control" name="recName3" placeholder="Recommender name" value=<?php echo '"' . $recName3 . '"' ?>><br>

								<label for="rec">Recommender Email:</label>
								<?php echo $email3Err ?>
								<input type="text" class="form-control" name="email3" placeholder="Recommender email" value=<?php echo $email3 ?>><br>

							</div>
						</div>
					</div>
				</div>
			</section>


			<!-- AREA OF INTEREST -->
			<section id="interest" class="interest-section">
				<div class="container">
					<div class="card">
						<h2 class="card-header">AREA OF INTEREST</h2>
						<div class="card-body">
							<div class="form-group">
								<label for="interest"> Area of Interest: </label>
								<?php echo $interestErr ?>
								<textarea class="form-control" type="text" name="interest" rows="3"> <?php echo $interest ?> </textarea><br>
							</div>
						</div>
					</div>
				</div>
			</section>



			<!-- EXPERIENCE -->
			<section id="experience" class="experience-section">
				<div class="container">
					<div class="card">
						<h2 class="card-header">EXPERIENCE</h2>
						<div class="card-body">
							<div class="form-group">
								<label for="experience"> Experience: </label><br>
								<?php echo $experienceErr ?>
								<textarea class="form-control" type="text" name="experience" rows="3"> <?php echo $experience ?> </textarea> <br>
							</div>
						</div>
					</div>
				</div>
			</section>



			<!-- SUBMIT -->
			<section id="submit" class="submit-section">
				<div class="container">
					<div class="card">
						<div class="card-body">
							<input type="submit" class="btn btn-primary" value="Submit" name="submit">
							<input type="submit" class="btn btn-dark" value="Save" name="save">
						</div>
					</div>
				</div>
			</section>
		</form>
	</div>

	<?php
	// get the footer
	require_once('footer.php');
	?>
</body>

</html>
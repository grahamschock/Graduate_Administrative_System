<?php
$page_title = "Offer";
require_once('../connectvars.php');
require_once('../header.php');
session_start();
$db = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$aid = $_GET['aid'];
$recname = "";//$_GET['recName'];

$query = "SELECT A.fname,A.lname,A.email,A.address,A.dobirth,A.ssn,A.password,B.applyfor,B.admitSemes,B.admitYear FROM applicant AS A,application AS B WHERE A.uid=B.uid AND B.aid=$aid;";
$result = mysqli_query($db, $query);
$names = mysqli_fetch_array($result);
$fname = $names["fname"];
$lname = $names["lname"];
$email = $names["email"];
$address = $names["address"];
$dobirth = $names["dobirth"];
$semester = $names["admitSemes"];
$year = $names["admitYear"];
$ssn = $names["ssn"];
$pwd = $names["password"];
if($names["applyfor"] == "MS") {
    $degree = "masters";
}
else{ 
    $degree = "doctorate";
}
$errors = array();
$success = array();
$id = NULL;

if(isset($_POST['submit'])) {
    $query = 'SELECT MAX(ID) AS max FROM allusers';
    $maxvalue = mysqli_query($db, $query);
    $maxvalue = mysqli_fetch_assoc($maxvalue);
    $id = $maxvalue['max'] - 1;
    $query = "INSERT INTO allusers VALUES ($id,'$pwd',8,'$degree','$fname','$lname','$email','$address',NULL,NULL,'1999-11-11', 'newacc.png','$ssn','$semester','$year')";
    $result = mysqli_query($db,$query);
    if($result) {
        $query = "INSERT INTO students VALUES ($id,0,0,0)";
        $result = mysqli_query($db,$query);
        $query = "SELECT recomAdv FROM application WHERE aid=$aid;";
        $result = mysqli_query($db,$query);
        $recomAdv = mysqli_fetch_array($result)['recomAdv'];
        $query = "INSERT INTO advises VALUES ($recomAdv,$id,0);";
        $result = mysqli_query($db,$query);
        $errors = array();
        $title = "Officially a Bronco!";
        $webLink = "http://gwupyterhub.seas.gwu.edu/~sp20DBp2-BroncoPlusPlus/";
        $msg = "Congratuations $fname $lname!<br><br> You have now officially become a Bronco! Use $id to <a href=$webLink>Log in</a> as a student.";
        mail($email,$title,$msg,'Content-type: text/html; charset=iso-8859-1' . "\r\n");
        if(isset($_SESSION['userid'])) {
            unset($_SESSION['userid']);
        }
        header('Location: ../index.php');
    } else {
        if(!empty(mysqli_error($db))){
            array_push($errors,$query." ".mysqli_error($db));
            array_push($errors,"Sorry there is an internal error, please contact admin.");
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
		<h1>Congratulations, <?php echo $fname . " " . $lname; ?><br> You have been Admitted into the Bronco University</h1>
	</div>
	<label name = "errorbox" style="color: #FF0000"><?php include('errors.php'); ?></label> <br />
	<form action="" method="post" enctype="multipart/form-data">
		<label>Click the Button Below to Accept your Offer and Become a Bronco</label><br />
		<button id="btn-submitFinal" name="submit">ACCEPT OFFER</button>
    </form>
</body>

</html>

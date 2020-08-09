<?php
  // start the session
  session_start();

  // get mysql database information
  require_once('../connectvars.php');  

  // get the header and nav bar
  require_once('header.php');
  require_once('nav.php');

  // establish connetion 
  // Create connection
  $db = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  
  // Check connection
  if (!$db) {
	die("Connection failed: " . mysqli_connect_error());
  }

  $message="";
  if(isset($_SESSION['submit'])){
	  if($_SESSION['submit'] == 1){
	  	$message = '<p style="color:green;"> Your application has been submmited </p>';
	  	$_SESSION['submit'] = 0;
	  }
  }

  if(isset($_SESSION['save'])){
	  if($_SESSION['save'] == 1){
	  	$message = '<p style="color:green;"> Your application has been saved </p>';
	  	$_SESSION['save'] = 0;
	  }
  }

  if(isset($_SESSION['update'])){
	  if($_SESSION['update'] == 1){
	  	$message = '<p style="color:green;"> Your recommenders has been updated </p>';
	  	$_SESSION['update'] = 0;
	  }
  }

  if(isset($_SESSION['updateinfo'])){
	if($_SESSION['updateinfo'] == 1){
		$message = '<p style="color:green;"> Your personal information has been updated </p>';
		$_SESSION['updateinfo'] = 0;
	}
}

  $complete = "";
  $error = " ";
  $fname = " ";
  $lname = " ";
  $sid = " ";

  if(isset($_SESSION['username'])){
  	$username = $_SESSION['username'];

	$query = "SELECT * FROM applicant WHERE username = '$username'";
	$result = mysqli_query($db, $query);
	$row = mysqli_fetch_array($result);

	$fname =$row['fname'];
	$lname = $row['lname'];
	$sid = $row['uid'];

	$dobirth = $row['dobirth'];
	$ssn = $row['ssn'];
	$fname = $row['fname'];
	$lname = $row['lname'];
	$address = $row['address'];

	$query = "SELECT * FROM applicant, application WHERE applicant.uid = application.uid AND applicant.username = '$username'";
	$result = mysqli_query($db, $query);
	$row = mysqli_fetch_array($result);

	$aid = $row['aid'];
	$complete = $row['complete'];
	$submit = $row['submit'];
	$reason = $row['reason'];
	$finaldeci = $row['finaldeci'];

	$applyfor = $row['applyfor'];
	$priorExp = $row['priorExp'];
	$interest = $row['interest'];
	$admitYear = $row['admitYear'];
	$admitSemes = $row['admitSemes'];

   } else {
  	$error = "PLEASE LOGIN";
  	if(!empty($error)){
    	echo("<script>location.href = '../index.php';</script>");
    }
  }

?>

<!DOCTYPE html>
<html>

<!-- FORM -->
<form action="#" method = "post">

 <section id="view-app" class="view-app-section">
	<div class = "container">
		<div class = "card">
			<?php echo '<h2 class= "card-title">' . $error . ' </h2>' ?>
			<?php echo '<h2 class= "card-title">' . $message . ' </h2>' ?>
		  	<h2 class= "card-title">Application</h2>
		  		<div class="card-body">
		  	  	  	<div class="form-group">

		  	  	  		<h3 for="view"> Applicant Information: </h3>
		  	  	  		<?php
		  	  	  			echo "First Name : $fname <br>";
		  	  	  			echo "Last Name : $lname <br>";
		  	  	  			echo "Student ID : $sid <br><br>";
		  	  	  		?>

				  		<input type="submit" class="btn btn-primary" value="View Application" name = "view">
				  		<?php
				  			if($submit == 1 && is_null($finaldeci)){
				  				echo '<input type="submit" class="btn btn-primary" value="Update Email" name = "update">';
							}
							if($submit == 1 && is_null($finaldeci)) {
								echo '<input type="submit" style="margin-left:5px;"class="btn btn-primary" value="Update Personal Information" name = "updateinfo">';
							}
				  		?>
				  		<br>

				  		<h3 for="view"> Status: </h3>
				  		<?php
				  			if($submit == 1){
				  				echo "Status: application submmited <br>";

				  				if($complete == 1){
					  				if($finaldeci == 0 && !is_null($finaldeci)){
					  					echo "Final Decision: Reject <br>";
					  					if($reason == 'A'){
					  						echo "Reason: Incomplete Record <br>";
					  					} else if ($reason == 'B'){
					  						echo "Reason: Does not meet minimum Requirements <br>";
					  					} else if ($reason == 'C'){
					  						echo "Reason: Problems with Letters <br>";
					  					} else if ($reason == 'D'){
					  						echo "Reason: Not competitive <br>";
					  					} else if ($reason == 'E'){
					  						echo "Reason: Other reason <br>";
					  					}
					  				} else if($finaldeci == 1){
					  					echo "Final Decision: Admit without Aid <br>";

					  				} else if($finaldeci == 2){
					  					echo "Final Decision: Admit with Aid <br>";

					  				} else {
										$isSubmited = 0;
										$recommendation = 0;

										$query = "SELECT * FROM recLet, application WHERE recLet.aid='$aid' AND application.aid='$aid' AND application.recName1 = recLet.recName";
						  				$result = mysqli_query($db, $query);
										$row = mysqli_fetch_array($result);

										if(!is_null($row['recLink'])){
											$isSubmited = 1;
											echo "Status: " . $row['recName'] . " has submmited their recommendation <br>";
										}

										if(!is_null($row['recName'])){
											$recommendation = 1;
										}

										$query = "SELECT * FROM recLet, application WHERE recLet.aid='$aid' AND application.aid='$aid' AND application.recName2 = recLet.recName";
						  				$result = mysqli_query($db, $query);
										$row = mysqli_fetch_array($result);

										if(!is_null($row['recLink'])){
											$isSubmited = 1;
											echo "Status: " . $row['recName'] . " has submmited their recommendation <br>";
										}

										if(!is_null($row['recName'])){
											$recommendation = 1;
										}

										$query = "SELECT * FROM recLet, application WHERE recLet.aid='$aid' AND application.aid='$aid' AND application.recName3 = recLet.recName";
						  				$result = mysqli_query($db, $query);
										$row = mysqli_fetch_array($result);

										if(!is_null($row['recLink'])){
											$isSubmited = 1;
											echo "Status: " . $row['recName'] . " has submmited their recommendation <br>";
										}

										if(!is_null($row['recName'])){
											$recommendation = 1;
										}

										if ($recommendation == 0){
											echo "Status: no recommenders added <br>";
										} else if($isSubmited == 0){
											echo "Status: waiting for recommendation <br>";
										}

										echo "Status: application completed <br>";

					  					$query = "SELECT * FROM review WHERE review.aid='$aid'";
						  				$result = mysqli_query($db, $query);
										$row = mysqli_fetch_array($result);
										if(!is_null($row['rating'])){
											echo "Status: application has been reviewed waiting for final decision <br>";
										} else {
											echo "Status: waiting for review <br>";
										}
									}
					  			} else {
								
									$isSubmited = 0;
									$recommendation = 0;

									$query = "SELECT * FROM recLet, application WHERE recLet.aid='$aid' AND application.aid='$aid' AND application.recName1 = recLet.recName";
					  				$result = mysqli_query($db, $query);
									$row = mysqli_fetch_array($result);

									if(!is_null($row['recLink'])){
										$isSubmited = 1;
										echo "Status: " . $row['recName'] . " has submmited their recommendation <br>";
									}

									if(!is_null($row['recName'])){
										$recommendation = 1;
									}

									$query = "SELECT * FROM recLet, application WHERE recLet.aid='$aid' AND application.aid='$aid' AND application.recName2 = recLet.recName";
					  				$result = mysqli_query($db, $query);
									$row = mysqli_fetch_array($result);

									if(!is_null($row['recLink'])){
										$isSubmited = 1;
										echo "Status: " . $row['recName'] . " has submmited their recommendation <br>";
									}

									if(!is_null($row['recName'])){
										$recommendation = 1;
									}

									$query = "SELECT * FROM recLet, application WHERE recLet.aid='$aid' AND application.aid='$aid' AND application.recName3 = recLet.recName";
					  				$result = mysqli_query($db, $query);
									$row = mysqli_fetch_array($result);

									if(!is_null($row['recLink'])){
										$isSubmited = 1;
										echo "Status: " . $row['recName'] . " has submmited their recommendation <br>";
									}

									if(!is_null($row['recName'])){
										$recommendation = 1;
									}

									if ($recommendation == 0){
										echo "Status: no recommenders added <br>";
									} else if($isSubmited == 0){
										echo "Status: waiting for recommendation <br>";
									}
								

					  				echo "Status: application is incomplete (waiting for transcript) <br>";
					  			}

				  			} else {
				  				echo "Status: application needs to be submited (click view application and submit your application) <br>";

				  				echo "Missing Field: ";
				  				$flag = 0;
				  				if(empty($fname)){
				  					echo "First Name, ";
				  					$flag = 1;
				  				}

				  				if(empty($lname)){
				  					echo "Last Name, ";
				  					$flag = 1;
				  				}

				  				if(empty($ssn)){
				  					echo "SSN, ";
				  					$flag = 1;
				  				}

				  				if(empty($dobirth)){
				  					echo "Date of Birth, ";
				  					$flag = 1;
				  				}

				  				if(empty($address)){
				  					echo "Address, ";
				  					$flag = 1;
				  				}

				  				if(empty($priorExp)){
				  					echo "Prior Experience, ";
				  					$flag = 1;
				  				}

				  				if(empty($applyfor)){
				  					echo "Apply For, ";
				  					$flag = 1;
				  				}

				  				if(empty($interest)){
				  					echo "Interest, ";
				  					$flag = 1;
				  				}

				  				if(empty($admitYear) || $admitYear == 0){
				  					echo "Admit year, ";
				  					$flag = 1;
				  				}

				  				if(empty($admitSemes)){
				  					echo "Admit Semes ";
				  					$flag = 1;
				  				}

				  				if($flag == 0){
				  					echo "None <br>";
				  				} else {
				  					echo "are missing <br>";
				  				}
				  			}
				  		?>

				  	</div>
				</div>
		</div>
	</div>
 </section>
</form>

<?php
  if(isset($_SESSION['username'])){
	if(isset($_POST['view'])){
		if($submit == 1){
			echo("<script>location.href = 'completedapp.php';</script>");
		} else {
			echo("<script>location.href = 'app.php';</script>");
		}
	}

	if(isset($_POST['update'])){
		if($submit == 1){
			echo("<script>location.href = 'update.php';</script>");
		}
	}

	if(isset($_POST['updateinfo'])) {
		if($submit == 1){
			echo("<script>location.href = 'editApplicantInfo.php';</script>");
		}
	}
  }
?>

</body>

</html>

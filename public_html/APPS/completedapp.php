<?php

  // start the session
  session_start();

  // get mysql database information
  require_once('../connectvars.php');  

  // get the header and nav bar
  require_once('header.php');
  require_once('nav.php');


  // create variables
  // names
  $fname = $lname = "";
  $degree = $admitSemes = $admitYear = "";
  $username = $password = "";
  $sid = $dobirth = $ssn = $address = "";
  $priorDegree1 = $priorDegree2 = "";

  // exams
  $gre_verbal = $gre_quantitative = $gre_year = "";
  $gre_adv_score = $gre_adv_subject = $gre_adv_year = "";
  $toefl_score = $toefl_year = "";

  //email
  $email = $email1 = $email2 = $email3 = "";

  // interest and experence 
  $interest = $experience = "";

  // establish connetion 
  // Create connection
  $db = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  
  // Check connection
  if (!$db) {
	die("Connection failed: " . mysqli_connect_error());
  }

  // get information
  if(isset($_SESSION['username'])){
  	$username = $_SESSION['username'];

	$query = "SELECT * FROM applicant WHERE username = '$username'";
	$result = mysqli_query($db, $query);
	$row = mysqli_fetch_array($result);

	$fname = $row['fname'];
	$lname = $row['lname'];
	$email = $row['email'];
	$sid = $row['uid'];
	$dobirth = $row['dobirth'];
	$ssn = $row['ssn'];
	$address = $row['address'];

	$query = "SELECT * FROM applicant, application WHERE applicant.uid = application.uid AND applicant.username = '$username'";
	$result = mysqli_query($db, $query);
	$row = mysqli_fetch_array($result);

	$aid = $row['aid'];
	$degree = $row['applyfor'];
	$priorDegree1 = $row['priorDegree1'];
	$pd1Place = $row['pd1Place'];
	$pd1GPA = $row['pd1GPA'];
	if(!is_null($pd1Place) && !empty($pd1Place)){
		$pd1Place = '<input disabled type="text" class="form-control" name="lname" placeholder="" value= "' . $pd1Place . '" >';
		$pd1GPA = '<input disabled type="number" class="form-control" name="lname" placeholder="" value= "' . $pd1GPA . '" >';
	}

	$priorDegree2 = $row['priorDegree2'];
	$pd2Place = $row['pd2Place'];
	$pd2GPA = $row['pd2GPA'];
	if(!is_null($pd2Place) && !empty($pd2Place)){
		$pd2Place = '<input disabled type="text" class="form-control" name="lname" placeholder="" value= "' . $pd2Place . '" >';
		$pd2GPA = '<input disabled type="number" class="form-control" name="lname" placeholder="" value= "' . $pd2GPA . '" >';
	}

	$experience = $row['priorExp'];
	$interest = $row['interest'];
	$admitYear = $row['admitYear'];
	$email1 = $row['email1'];
	$email2 = $row['email2'];
	$email3 = $row['email3'];
	$recName1 = $row['recName1'];
	$recName2 = $row['recName2'];
	$recName3 = $row['recName3'];

	$submit = $row['submit'];
	if($submit == 0){
		echo("<script>location.href = 'viewapp.php';</script>");
	}


	if($admitYear == 0){
		$admitYear = "";
	}

	$admitSemes = $row['admitSemes'];

	$query = "SELECT * FROM applicant, application, gre WHERE applicant.uid = application.uid AND applicant.username = '$username' AND application.aid = gre.aid";
	$result = mysqli_query($db, $query);
	$row = mysqli_fetch_array($result);

	$gre_verbal = $row['verbalscore'];
	if($gre_verbal == 0){
		$gre_verbal = "";
	}

	$gre_quantitative = $row['quantscore'];
	if($gre_quantitative == 0){
		$gre_quantitative = "";
	}

	$gre_year = $row['year'];
	if($gre_year == 0){
		$gre_year = "";
	}

	$query = "SELECT * FROM applicant, application, gresubject WHERE applicant.uid = application.uid AND applicant.username = '$username' AND application.aid = gresubject.aid";
	$result = mysqli_query($db, $query);
	$row = mysqli_fetch_array($result);

	$gre_adv_subject = $row['subject'];
	$gre_adv_score = $row['score'];
	if($gre_adv_score == 0){
		$gre_adv_score = "";
	}

	$gre_adv_year = $row['year'];
	if($gre_adv_year == 0){
		$gre_adv_year = "";
	}

	$query = "SELECT * FROM applicant, application, toefl WHERE applicant.uid = application.uid AND applicant.username = '$username' AND application.aid = toefl.aid";
	$result = mysqli_query($db, $query);
	$row = mysqli_fetch_array($result);

	$toefl_score = $row['score'];
	if($toefl_score == 0){
		$toefl_score = "";
	}

	$toefl_year = $row['year'];
	if($toefl_year == 0){
		$toefl_year = "";
	}
}
// the user is not logged in, redirect them to viewapp
else {
	echo("<script>location.href = 'viewapp.php';</script>");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if(isset($_POST['home'])){
		echo("<script>location.href = 'viewapp.php';</script>");
	}
}

?>

<!DOCTYPE html>
<html>

<body>
	<!-- APPLICATION -->
	<div class="apps">
		
		  <!-- FORM -->
		  <form action="#" method = "post">
		  	
		  	  <!-- NAME -->
		  	  <section id="applicant-information" class="name-section">
		  	  	<div class = "container">
		  	  	  	<div class="card">
		  	  	  	  <h1> APPS </h1>
		  	  	  	  <h3> Application Submited. This form is viewonly </h3>
 					  <h2 class= "card-header">APPLICANT INFORMATION</h2>
 					  	<div class="card-body">

 					  	  <!-- APPLICANT INFORMATION -->
 					  	  <div class = "form-row">

 					  	  	<!-- NAME -->
 					  	  	<div class = "col">
 					  	  	  <label for="name"> First Name: </label>
							  <input disabled type="text" class="form-control" name="fname" placeholder="First name" value=<?php echo $fname ?>><br>
							</div>

							<div class = "col">
							  <label for="name"> Last Name: </label>
							  <input disabled type="text" class="form-control" name="lname" placeholder="Last name" value=<?php echo $lname ?> ><br>
							</div>

							<!-- Email Address -->
							<div class = "col">
							  <label for="student-id"> Email:</label>
							  <input type="text" class="form-control" name="sid" placeholder="Email" value=<?php echo $email ?> disabled><br><br>
							</div>

							<!-- STUDENT ID -->
							<div class = "col">
							  <label for="student-id"> Student ID: </label>
							  <input disabled type="text" class="form-control" name="sid" placeholder="Student ID" value=<?php echo $sid ?> ><br><br>
							</div>


							<!-- INFORMATION -->
							<div class = "col">
							  <label for="dobirth"> Date of Birth (YYYY-MM-DD): </label>
							  <input disabled type="text" class="form-control" name="dobirth" placeholder="Date of Birth" value=<?php echo $dobirth ?> ><br><br>
							</div>
							  
							<!-- SSN -->
							<div class = "col">
							  <label for="ssn"> SSN: </label>
							  <input disabled type="password" class="form-control" name="ssn" placeholder="SSN" id="ssn" value=<?php echo $ssn ?> >
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
							<div class = "col">
							  <label for="address"> Address: </label>
							  <input disabled type="text" class="form-control" name="address" placeholder="Address" value=<?php echo '"' . $address . '"' ?> ><br><br>
							</div>
						</div>

						<!-- Prior Degree -->
						<div class="form-check">
						<label for="degree">Prior Degrees: </label><br>
						    <input disabled type="checkbox" class="form-check-input" name="priorDegree1" value="B" <?php if($priorDegree1 == 'B'){ echo "checked"; }?> >
						    <label class="form-check-label" for="B"> Bachelor's Degree </label><br>
						    <?php
						    	if(!is_null($pd1Place) && !empty($pd1Place)){
								echo $pd1Place;
								echo $pd1GPA;
						    	}
						    ?>
						    <br>

						    <input disabled type="checkbox" class="form-check-input" name="priorDegree2" value="MS" <?php if($priorDegree2 == 'MS'){ echo "checked"; }?>>
						    <label class="form-check-label" for="exampleCheck1"> Master's Degree </label><br>
						    <?php
						    	if(!is_null($pd2Place) && !empty($pd2Place)){
								echo $pd2Place;
								echo $pd2GPA;
						    	}
						    ?>

						    <br><br>

						</div>

						<!-- DEGREE -->
						<div class="form-check">
							  <label for="degree">Applying for Degree: </label><br>
							  <input disabled type="text" class="form-control" name="degree" placeholder="Degree" value=<?php echo '"' . $degree . '"' ?> ><br><br>
						</div>

						<div class = "form-row">
							<div class ="col">
							  <label for="aditSemes">Admission semester: </label>
							  <input disabled type="text" class="form-control" name="admitSemes" placeholder="Admission semester" value=<?php echo $admitSemes ?> ><br><br>
							</div>

							<div class ="col">
							  <label for="admitYear">Admission year: </label>
							  <input disabled type="text" class="form-control" name="admitYear" placeholder="Admission year" value=<?php echo $admitYear ?> ><br><br>
							</div>

						  </div>
						</div>
					</div>
				</div>
			 </section>


			  <!-- EXAM --> 
			  <section id="exam" class="exam-section">
		  	  	<div class = "container">
		  	  	  <div class = "card">
		  	  	  	<h2 class= "card-header">EXAM</h2>
		  	  	  	  <div class="card-body">

		  	  	  	  	<div class = "form-row">

				  	  	  	<!-- GRE -->
				  	  	  	<h3 for="gre">GRE</h3>
				  	  	  	<div class="col">
							  <label for="gre">Verbal:</label>
							  <input disabled type="text" class="form-control" name="gre_verbal" placeholder="Verbal" value=<?php echo $gre_verbal ?>><br>
							</div>

							<div class="col">
							  <label for="gre">Quantitative:</label>
							  <input disabled type="text" class="form-control" name="gre_quantitative" placeholder="Quantitative" value =<?php echo $gre_quantitative ?> ><br>
							</div>

							<div clas="col">
							  <label for="gre">Year:</label>
							  <input disabled type="text" class="form-control" name="gre_year" placeholder="Year" value =<?php echo $gre_year ?>><br><br>
							</div>

							<!-- GRE Advanced -->
							<h3 for="gre_advanced">GRE Advanced</h3>
							<div class="col">
							  <label for="gre_advanced">Score:</label>
							  <input disabled type="text" class="form-control" name="gre_adv_score" placeholder="Score" value =<?php echo $gre_adv_score ?> ><br>
							</div>

							<div class="col">
							  <label for="gre_advanced">Subject:</label>
							  <input disabled type="text" class="form-control" name="gre_adv_subject" placeholder="Subject" value =<?php echo $gre_adv_subject ?>><br>
							</div>

							<div class="col">
							  <label for="gre_advanced">Year:</label>
							  <input disabled type="text" class="form-control" name="gre_adv_year" placeholder="Year" value =<?php echo $gre_adv_year ?>><br><br>
							</div>


							<!-- TOEEFL -->
							<h3 for="toefl">TOEEFL</h3>
							<div class="col">
							  <label for="toefl">Score:</label>
							  <input disabled type="text" class="form-control" name="toefl_score" placeholder="Score" value =<?php echo $toefl_score ?> ><br>
							</div>

							<div class="col">
							  <label for="toefl">Year:</label>
							  <input disabled type="text" class="form-control" name="toefl_year" placeholder="Year" value =<?php echo $toefl_year ?> ><br><br>
							</div>
						</div>
					  </div>
			  	  </div>
				</div>
			 </section>


			 <!-- RECOMMENDER -->
			 <section id="rec" class="rec-section">
		  	  	<div class = "container">
		  	  	  <div class = "card">
		  	  	  	<h2 class= "card-header">RECOMMENDER</h2>
		  	  	  	  <div class="card-body">
		  	  	  		<div class="form-group">

		  	  	  		  <label for="rec">Recommender Name:</label>
				  		  <input disabled type="text" class="form-control" name="recName1" placeholder="Recommender name" value = <?php echo '"' . $recName1 . '"' ?> ><br>

		  	  	  	      <label for="rec">Recommender Email:</label>
				  		  <input disabled type="text" class="form-control" name="email1" placeholder="Recommender email" value = <?php echo $email1 ?> ><br><br><br>


				  		  <label for="rec">Recommender Name:</label>
				  		  <input disabled type="text" class="form-control" name="recName2" placeholder="Recommender name" value = <?php  echo '"' . $recName2 . '"' ?> ><br>

				  		  <label for="rec">Recommender Email:</label>
				  		  <input disabled type="text" class="form-control" name="email2" placeholder="Recommender email" value = <?php echo $email2 ?> ><br><br><br>


				  		  <label for="rec">Recommender Name:</label>
				  		  <input disabled type="text" class="form-control" name="recName3" placeholder="Recommender name" value = <?php echo '"' . $recName3 . '"' ?>><br>

				  		  <label for="rec">Recommender Email:</label>
				  		  <input disabled type="text" class="form-control" name="email3" placeholder="Recommender email" value = <?php echo $email3 ?> ><br>
				  		  
						</div>
					</div>
				  </div>
				</div>
			 </section>


			 <!-- AREA OF INTEREST -->
			 <section id="interest" class="interest-section">
		  	  	<div class = "container">
		  	  	  <div class = "card">
		  	  	  	<h2 class= "card-header">AREA OF INTEREST</h2>
		  	  	  	  <div class="card-body">
		  	  	  		<div class="form-group">
				  		  <label for="interest"> Area of Interest: </label>
				  		  <textarea class="form-control" type="text" name="interest" rows="3" disabled> <?php echo $interest ?> </textarea><br>
						</div>
					</div>
				  </div>
				</div>
			 </section>



			<!-- EXPERIENCE -->
			<section id="experience" class="experience-section">
			  	<div class = "container">
		  	  	  <div class = "card">
		  	  	  	<h2 class= "card-header">EXPERIENCE</h2>
		  	 		  <div class="card-body">
		  	  	  		<div class="form-group">
				  		 <label for="experience"> Experience: </label><br>
				  		 <textarea class="form-control" type="text" name="experience" rows="3" disabled> <?php echo $experience ?> </textarea> <br>
				  		</div>
				  	</div>
				  </div>
				</div>
			 </section>

			 <!-- SUBMIT -->
			<section id="submit" class="submit-section">
			  	<div class = "container">
		  	  	  <div class = "card">
		  	 		  <div class="card-body">
			  			<input type="submit" class="btn btn-primary" value="Home" name = "home">
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

<?php
	// get the footer
		require_once('footer.php');
?>	

</html>

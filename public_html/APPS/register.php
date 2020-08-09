<?php
  // start the session
  session_start();

  // get mysql database information
  require_once('../connectvars.php');  

  // get the header and nav bar
  require_once('header.php');
  require_once('nav.php');

  $username = $password = $fname = $lname = $email = $ssn = $address ="";
  $usernameErr = $passwordErr = $fnameErr = $lnameErr = $emailErr = $ssnErr =  $addressErr= "";

  // Create connection
  $db = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  
  // Check connection
  if (!$db) {
	die("Connection failed: " . mysqli_connect_error());
  }

  $error = "";

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$username = $_POST['username'];
	if(strlen($username) > 15){
		$usernameErr = 'INVALID username length';
	}

	$password = $_POST['password'];
	if(strlen($password) > 40){
		$passwordErr = 'INVALID password length';
	}

	if(!empty($_POST['ssn'])){
		$ssn = $_POST['ssn'];
		if(!is_numeric($ssn) || strlen($ssn) != 9){
			$ssnErr = 'INVALID SSN input';
		}
	}

	$fname = $_POST['fname'];
	if(strlen($fname) > 32){
		$fnameErr = 'INVALID First Name length';
	}

	$lname = $_POST['lname'];
	if(strlen($lname)> 32){
		$lnameErr = 'INVALID Last Name length';
	}

	$email = $_POST['email'];
	if(strlen($email) > 100) {
		$emailErr = 'INVALID email Length';
	}

	// get address
	$address = $_POST['address'];
	// get if the address is formated correctly
	if (!empty($address) && !preg_match('/\d+ [0-9a-zA-Z ]+/', $address)) {
	    $addressErr = 'INVALID address format';
	}

	if(strlen($address) > 100){
		$addressErr = 'INVALID address length';
	}

  }

 if(isset($_POST['register'])){
	// check for null values
	if(empty($username)){
		$usernameErr = '*username is required';
	}

	if(empty($password)){
		$passwordErr = '*password is required';
	}

	if(empty($ssn)){
		$ssnErr = '*SSN is required';
	}

	if(empty($fname)){
		$fnameErr = '*firstname is required';
	}

	if(empty($lname)){
		$lnameErr = '*lastname is required';
	}

	if(empty($email)) {
		$emailErr = '*email is required';
	}

	if(empty($address)){
		$addressErr = '*address is required';
	}
  }

  if(empty($password) || empty($username) || empty($ssn) || empty($fname) || empty($lname) || !empty($emailErr) || !empty($usernameErr) || !empty($passwordErr) || !empty($ssnErr) || !empty($fnameErr) || !empty($lnameErr) || empty($address) || !empty($addressErr)){
  	$error = ' Error - please complete the required field';
  } else {

  	$query = "SELECT username, ssn FROM applicant WHERE username='$username'";
  	$result = mysqli_query($db, $query);
    $row = mysqli_fetch_assoc($result);

    if(strcasecmp($row['username'], $username) == 0){
    	$usernameErr = ' Username already exists. Please select a different username';
    } else {

    	$query = "SELECT ssn FROM applicant WHERE ssn='$ssn'";
		$result = mysqli_query($db, $query);
		$row = mysqli_fetch_assoc($result);

		if(strcasecmp($row['ssn'], $ssn) == 0){
    		$ssnErr = ' Please enter a valid SSN';
    	} else {

    		$query = 'SELECT MAX(uid) AS max FROM applicant';
		    $maxvalue = mysqli_query($db, $query);
		    $maxvalue = mysqli_fetch_assoc($maxvalue);
		    $uid = $maxvalue['max'] - 1;

		  	$sql = "INSERT INTO applicant VALUES ('$uid', '$username', '$password', '$fname', '$lname', '$email', NULL, '$ssn', '$address')";
		    $result = mysqli_query($db, $sql);
		    if($result){
		    	//echo "Success <br> ";
		    } else {
		    	echo "Error" . mysqli_error($db) . "<br>";
		    	$error = "error"; 
		    }

		    $query = 'SELECT MAX(aid) AS max FROM application';
            $maxvalue = mysqli_query($db, $query);
            $maxvalue = mysqli_fetch_assoc($maxvalue);
            $aid = $maxvalue['max'] + 1;

		  	$sql = "INSERT INTO application (aid, uid, trReced, transcript, applyfor, priorDegree1, pd1Place, priorDegree2, pd2Place, priorExp, interest, admitYear, admitSemes, complete, submit, finaldeci, reason, dateSubmission, email1, email2, email3, recName1, recName2, recName3) VALUES ('$aid', '$uid', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL,NULL, NULL, NULL)";
            $result = mysqli_query($db, $sql);
            if($result){
            	//echo "Success <br> ";
            } else {
            	echo "Error" . mysqli_error($db) . "<br>";
            	$error = "error"; 
            }

            // SUBMIT GRE
            $sql = "INSERT INTO gre (aid, verbalscore, quantscore, year) VALUES ('$aid', NULL, NULL, NULL)";
            $result = mysqli_query($db, $sql);
            if($result){
            	//echo "Success <br> ";
            } else {
            	echo "Error" . mysqli_error($db) . "<br>"; 
            	$error = "error";
            }

            // SUBMIT GRE ADVANCED
            $sql = "INSERT INTO gresubject (aid, subject, score, year) VALUES ('$aid', NULL, NULL, NULL)";
            $result = mysqli_query($db, $sql);
            if($result){
            	//echo "Success <br> ";
            } else {
            	echo "Error" . mysqli_error($db) . "<br>";
            	$error = "error";
            }

            // SUBMIT TOEFL
            $sql = "INSERT INTO toefl (aid, score, year) VALUES ('$aid', NULL, NULL)";
            $result = mysqli_query($db, $sql);
            if($result){
            	//echo "Success <br> ";
            } else {
            	echo "Error" . mysqli_error($db) . "<br>";
            	$error = "error";
            }

            if(empty($error)){
		  		$_SESSION['account'] = 1;
		  		$_SESSION['username'] = $username;
				echo("<script>location.href = 'viewapp.php';</script>");
		  	}

    	}
    }
  }

 ?>


 <body>
	<div class="apps">
	    <form action="#" method = "post">
		    
		    <section id="login" class="login-section">
				<center><h2> Register </h2></center>

					<div class="container-fluid col-md-4 col-md-offset-4">
						<div class="form-row">

							<div class="form-group">

								<label for="login"> First name: </label><br>
								<span style="color:red"> <i> <?php echo $fnameErr; ?> </i> </span>
								<input type="text" class="form-control" name="fname" placeholder="First name" value =<?php echo $fname ?>>
							</div>

							<div class="form-group">
								<label for="login"> Last name: </label><br>
								<span style="color:red"> <i> <?php echo $lnameErr; ?> </i> </span>
								<input type="text" class="form-control" name="lname" placeholder="Last name" value =<?php echo $lname ?>>
							</div>

							<div class="form-group">
								<label for="login"> Email Address: </label><br>
								<span style="color:red"> <i> <?php echo $emailErr; ?> </i> </span>
								<input type="text" class="form-control" name="email" placeholder="Email" value =<?php echo $email ?>>
							</div>

							<div class="form-group">
								<label for="login"> Username: </label><br>
								<span style="color:red"> <i> <?php echo $usernameErr; ?> </i> </span>
								<input type="text" class="form-control" name="username" placeholder="Username" value =<?php echo $username ?>>
							</div>

							<div class="form-group">
								<label for="login"> Password: </label><br>
								<span style="color:red"> <i> <?php echo $passwordErr; ?> </i> </span>
								<input type="password" class="form-control" name="password" id="password" placeholder="Password">
								<input type="checkbox" onclick="myPassword()"> Show Password 
							</div>

							<script>
							function myPassword() {
								var x = document.getElementById("password");
								if (x.type === "password") {
									x.type = "text";
								} else {
									x.type = "password";
								}
							}
						</script>

						</div>

						<div class="form-group">
							<label for="login"> Socail Security Number : </label><br>
							<span style="color:red"> <i> <?php echo $ssnErr; ?> </i> </span>
							<input type="password" class="form-control" name="ssn" placeholder="SSN" id="ssn" value =<?php echo $ssn ?>>
							<input type="checkbox" onclick="myFunction()"> Show SSN 
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

						<div class="form-group">
							<label for="login"> Address : </label><br>
							<span style="color:red"> <i> <?php echo $addressErr; ?>  </i> </span>
							<input type="text" class="form-control" name="address" placeholder="Address" value =<?php echo $address ?> >
						</div>
					</div>

			</section>

				<!-- SUBMIT -->
				<section id="register" class="register-section text-center">
				  	<div class = "container col-md-4 col-md-offset-4">
			  	  	  <div class = "card">
			  	 		  <div class="card-body">
				  			<input type="submit" class="btn btn-primary" value="Register" name = "register">
				  		  </div>
				  		</div>
				  	</div>
				</section>
		</form>
	</div>
</body>

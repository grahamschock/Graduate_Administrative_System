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


	// email
	$email1 = $email2 = $email3 = "";
	$recName1 = $recName2 = $recName3 = "";

	//email
	$email1Err = $email2Err = $email3Err = "";
	$recName1Err = $recName2Err = $recName3Err = "";

	if(isset($_SESSION['username'])){
		$username = $_SESSION['username'];

		$query = "SELECT * FROM applicant, application WHERE applicant.uid = application.uid AND applicant.username = '$username'";
		$result = mysqli_query($db, $query);
		$row = mysqli_fetch_array($result);

		$aid = $row['aid'];
		$email1 = $row['email1'];
		$email2 = $row['email2'];
		$email3 = $row['email3'];
		$recName1 = $row['recName1'];
		$recName2 = $row['recName2'];
		$recName3 = $row['recName3'];
	}

	$submmited1 = 0;
	$query = "SELECT * FROM recLet, application WHERE recLet.aid='$aid' AND application.aid='$aid' AND application.recName1 = recLet.recName";
	$result = mysqli_query($db, $query);
	$row = mysqli_fetch_array($result);

	if(!is_null($row['recLink'])){
		$submmited1 = 1;
	}

	$submmited2 = 0;
	$query = "SELECT * FROM recLet, application WHERE recLet.aid='$aid' AND application.aid='$aid' AND application.recName2 = recLet.recName";
	$result = mysqli_query($db, $query);
	$row = mysqli_fetch_array($result);

	if(!is_null($row['recLink'])){
		$submmited2 = 1;
	}

	$submmited3 = 0;
	$query = "SELECT * FROM recLet, application WHERE recLet.aid='$aid' AND application.aid='$aid' AND application.recName3 = recLet.recName";
	$result = mysqli_query($db, $query);
	$row = mysqli_fetch_array($result);

	if(!is_null($row['recLink'])){
		$submmited3 = 1;
	}

	$flag1 = 0;
	$flag2 = 0;
	$flag3 = 0;
	$error = "";

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {

		if(strcmp($email1, $_POST['email1'])){
			$flag1 = 1;
		}

		$email1 = $_POST['email1'];
	    if(!empty($email1)){
	        if (!filter_var($email1, FILTER_VALIDATE_EMAIL)) {
	            $email1Err .= '<p style="color:red;"> Invalid format and please re-enter valid email </p>'; 
	        }

	        if(strlen($email1) > 100){
	        	$email1Err .= '<p style="color:red;"> Invalid email length </p>'; 
	        }
		}

		if(strcmp($email2, $_POST['email2'])){
			$flag2 = 1;
		}

		$email2 = $_POST['email2'];
		if(!empty($email2)){
		    if (!filter_var($email2, FILTER_VALIDATE_EMAIL)) {
		        $email2Err .= '<p style="color:red;"> Invalid format and please re-enter valid email </p>';
		    }

		    if(strlen($email2) > 100){
	        	$email2Err .= '<p style="color:red;"> Invalid email length </p>'; 
	        }
		}

		if(strcmp($email3, $_POST['email3'])){
			$flag3 = 1;
		}

		$email3 = $_POST['email3'];
		if(!empty($email3)){
		    if (!filter_var($email3, FILTER_VALIDATE_EMAIL)) {
		        $email3Err .= '<p style="color:red;"> Invalid format and please re-enter valid email </p>';
		    }

		    if(strlen($email3) > 100){
	        	$email3Err .= '<p style="color:red;"> Invalid email length </p>'; 
	        }
		}

		if(strcmp($recName1, $_POST['recName1'])){
			$flag1 = 1;
		}

		$recName1 = $_POST['recName1'];
		if(strlen($recName1) > 64){
        	$recName1Err .= '<p style="color:red;"> Invalid name length </p>'; 
        }

        if(strcmp($recName2, $_POST['recName2'])){
			$flag2 = 1;
		}

        $recName2 = $_POST['recName2'];
        if(strlen($recName2) > 64){
        	$recName2Err .= '<p style="color:red;"> Invalid name length </p>'; 
        }

        if(strcmp($recName3, $_POST['recName3'])){
			$flag3 = 1;
		}

        $recName3 = $_POST['recName3'];
        if(strlen($recName3) > 64){
        	$recName3Err .= '<p style="color:red;"> Invalid name length </p>'; 
        }
	}

	if(isset($_POST['home'])){
		echo("<script>location.href = 'viewapp.php';</script>");
	}

	// SUBMIT INFORMATION 
	if(isset($_POST['update'])){

		// unset session
		unset($_SESSION['email1']);
		unset($_SESSION['email2']);
		unset($_SESSION['email3']);
		unset($_SESSION['recName1']);
		unset($_SESSION['recName2']);
		unset($_SESSION['recName3']);

		// combaniation and email combination
		if(empty($email1) xor empty($recName1)){
			$recName1Err .= '<p style="color:red;">INVALID length: both name and email needs to be filled out</p>';
		}

		if(empty($email2) xor empty($recName2)){
			$recName2Err .= '<p style="color:red;">INVALID length: both name and email needs to be filled out</p>';
		}

		if(empty($email3) xor empty($recName3)){
			$recName3Err .= '<p style="color:red;">INVALID length: both name and email needs to be filled out</p>';
		}

		// check duplicates
		if(!empty($email1) && strcasecmp($email1, $email2) == 0){
			$email1Err = '<p style="color:red;"> Duplicate email </p>';
			$email2Err = '<p style="color:red;"> Duplicate email </p>';
		}

		if (!empty($email1) && strcasecmp($email1, $email3) == 0){
			$email1Err = '<p style="color:red;"> Duplicate email </p>';
			$email3Err = '<p style="color:red;"> Duplicate email </p>';
		}

		if (!empty($email2) && strcasecmp($email2, $email3) == 0){
			$email2Err = '<p style="color:red;"> Duplicate email </p>';
			$email3Err = '<p style="color:red;"> Duplicate email </p>';
		}

		$_SESSION['aid'] = $aid;

		// SET email varibles 
		if(empty($email1Err) && !empty($email1) && $submmited1 == 0){
			$_SESSION['email1'] = $email1;
		}

		if(empty($email2Err) && !empty($email2) && $submmited2 == 0){
			$_SESSION['email2'] = $email2;
		}

		if(empty($email3Err) && !empty($email3) && $submmited3 == 0){
			$_SESSION['email3'] = $email3;
		}

		if(empty($recName1Err) && !empty($recName1) && $submmited1 == 0){
			$_SESSION['recName1'] = $recName1;
		}

		if(empty($recName2Err) && !empty($recName2) && $submmited2 == 0){
			$_SESSION['recName2'] = $recName2;
		}

		if(empty($recName3Err) && !empty($recName3) && $submmited1 == 0){
			$_SESSION['recName3'] = $recName3;
		}

		if($flag1 == 0 && $flag2 == 0 && $flag3 == 0){
			$error = '<p style="color:red;"> No changes were made </p>';
		}

		if(empty($recName1Err) && empty($recName2Err) && empty($recName3Err) && empty($email1Err) && empty($email2Err) && empty($email3Err)){

			$sql = "UPDATE application SET email1='$email1', email2='$email2', email3='$email3', recName1='$recName1', recName2='$recName2', recName3='$recName3' WHERE aid='$aid'";
            $result = mysqli_query($db, $sql);
            if($result){
            	//echo "Success <br> ";
            } else {
            	echo "Error" . mysqli_error($db) . "<br>";
            	$error = "sql error"; 
            }

        	if(empty($error)){
				$_SESSION['update'] = 1;
	        	echo("<script>location.href = 'recommiddle.php';</script>");
        	}
		}
	}
?>
	
<html>
	<body>
		<!-- RECOMMENDER -->
		<form action="<?php echo $_SERVER['PHP_SELF'];?>" method = "post">
			 <section id="rec" class="rec-section">
		  	  	<div class = "container">

		  	  	  <div class = "card">
		  	  	  	<?php echo $error ?>
		  	  	  	<h2 class= "card-header">RECOMMENDER</h2>
		  	  	  	  <div class="card-body">
		  	  	  		<div class="form-group">

		  	  	  		  <label for="rec">Recommender Name:</label>
		  	  	  		  <?php 
		  	  	  		    if($submmited1 == 1){
								echo '<span style="color:red"> <i> recommendation has been submmited. Email cannot be changed </i> </span>';
		  	  	  		    }
		  	  	  		  	echo $recName1Err; 
		  	  	  		  ?>
				  		  <input <?php if($submmited1 == 1) { echo "readonly"; }?> type="text" class="form-control" name="recName1" placeholder="Recommender name" value = <?php echo '"' . $recName1 . '"' ?> ><br>

		  	  	  	      <label for="rec">Recommender Email:</label>
		  	  	  	      <?php echo $email1Err ?>
				  		  <input <?php if($submmited1 == 1) { echo "readonly"; }?> type="text" class="form-control" name="email1" placeholder="Recommender email" value = <?php echo $email1 ?> ><br><br><br>
		

				  		  <label for="rec">Recommender Name:</label>
				  		   <?php 
		  	  	  		    if($submmited2 == 1){
								echo '<span style="color:red"> <i> recommendation has been submmited. Email cannot be changed </i> </span>';
		  	  	  		    }
		  	  	  		  	echo $recName2Err; 
		  	  	  		  ?>
				  		  <input <?php if($submmited2 == 1) { echo "readonly"; }?> type="text" class="form-control" name="recName2" placeholder="Recommender name" value = <?php echo '"' . $recName2 . '"' ?> ><br>

				  		  <label for="rec">Recommender Email:</label>
				  		  <?php echo $email2Err ?>
				  		  <input <?php if($submmited2 == 1) { echo "readonly"; }?> type="text" class="form-control" name="email2" placeholder="Recommender email" value = <?php echo $email2 ?> ><br><br><br>


				  		  <label for="rec">Recommender Name:</label>
				  		   <?php 
		  	  	  		    if($submmited3 == 1){
								echo '<span style="color:red"> <i> recommendation has been submmited. Email cannot be changed </i> </span>';
		  	  	  		    }
		  	  	  		  	echo $recName3Err; 
		  	  	  		  ?>
				  		  <input <?php if($submmited3 == 1) { echo "readonly"; }?> type="text" class="form-control" name="recName3" placeholder="Recommender name" value = <?php echo '"' . $recName3 . '"' ?>><br>

				  		  <label for="rec">Recommender Email:</label>
				  		  <?php echo $email3Err ?>
				  		  <input <?php if($submmited3 == 1) { echo "readonly"; }?> type="text" class="form-control" name="email3" placeholder="Recommender email" value = <?php echo $email3 ?> ><br>
				  		  
						</div>
					</div>
				  </div>

				  <input type="submit" class="btn btn-primary" value="Update Email" name = "update">
				  <input type="submit" class="btn btn-secondary" value="Home" name = "home">

				</div>
			 </section>
		</form>
	</body>
</html>

<?php
	// get the footer
	require_once('footer.php');
?>

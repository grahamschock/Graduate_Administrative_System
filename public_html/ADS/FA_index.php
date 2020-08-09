<?php
    session_start();
    $page_title = "Faculty Portal";
    require_once('../header.php');
    require_once('connectvars.php');
?>
<html lang="en">

  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Faculty Advisor Portal</title>
  </head>
  <body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">ADS System</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="FA_index.php">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="personalinfo.php">Personal Info</a>
      </li>
    </ul>
    <form id="namesearch" class="form-inline my-2 my-lg-0" method = "post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <input class="form-control mr-sm-2" type="text" placeholder="Name Search" aria-label="Search" name="searchname">
      <input type="hidden" id="nFlag" name="nFlag" value="1">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>
    <form id="idsearch" class="form-inline my-2 my-lg-0" method = "post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <input class="form-control mr-sm-2" type="text" placeholder="ID Search" aria-label="Search" name="searchid">
      <input type="hidden" id="iFlag" name="iFlag" value="1">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
      <a class="btn btn-outline-danger" href="../logout.php" role="button">Logout</a>
    </form>
  </div>
</nav>
<?php require_once('../navmenu.php'); ?>

    <h1>Advisees Search</h1>
<?php
    require_once('appvars.php');
    //Acquire login credentials
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    
    //Test for registration hold removal
    if(isset($_POST['hold'])){
        if($_POST['hold'] != -1){
            //update hold to removed
            $query = "UPDATE students set registrationHold = 1 WHERE SID=" . $_POST['hold'];
            $data = mysqli_query($dbc, $query);
            
	    //Reset post variable
            $_POST['hold'] = -1;	    
	}   
    }

    //Test for thesis approval
    if(isset($_POST['thesis'])){
    	if($_POST['thesis'] != -1){
    	    //update thesis to approved
	    $query = "UPDATE students set thesis = 1 WHERE SID =" . $_POST['thesis'];
	    $data = mysqli_query($dbc, $query);
	    
	    //Reset post variable
	    $_POST['thesis'] = -1;
        }
    }
    
    if(isset($_SESSION['userid'])){
        $rowCount1 = 0;
	$rowCount2 = 0;

	// Search by name (MASTERS)
	if(isset($_POST['nFlag'])){
	    if($_POST['nFlag'] != 0){        
	        $query = "SELECT ID, fname, lname, email, picture, type, thesis FROM allusers, students, advises WHERE f_id=" . $_SESSION['userid'] . " and s_id=ID and type='masters' and acctype=8 and ID=SID and concat_ws(' ', fname, lname) like '%" . $_POST['searchname'] . "%'";
		$dataM = mysqli_query($dbc, $query);
		$rowCount1 = mysqli_num_rows($dataM);
	    }
	}
	// Search by name (PHD)
	if(isset($_POST['nFlag'])){
	    if($_POST['nFlag'] != 0){        
	        $query = "SELECT ID, fname, lname, email, picture, type, thesis FROM allusers, students, advises WHERE f_id=" . $_SESSION['userid'] . " and s_id=ID and type='doctorate' and acctype=8 and ID=SID and concat_ws(' ', fname, lname) like '%" . $_POST['searchname'] . "%'";
		$dataP = mysqli_query($dbc, $query);
		$rowCount2 = mysqli_num_rows($dataP);
	    }
	}
        $_POST['nFlag'] = 0;

	// Search by id (MASTERS)
	if(isset($_POST['iFlag'])){
	    if($_POST['iFlag'] != 0){        
	        $query = "SELECT ID, fname, lname, email, picture, type, thesis FROM allusers, students, advises WHERE f_id=" . $_SESSION['userid'] . " and s_id=ID and type='masters' and acctype=8 and ID=SID and ID=" . $_POST['searchid'];
		$dataM = mysqli_query($dbc, $query);
		if($dataM != FALSE){
		    $rowCount1 = mysqli_num_rows($dataM);
		}
	    }
	}
	// Search by id (PHD)
	if(isset($_POST['iFlag'])){
	    if($_POST['iFlag'] != 0){        
	        $query = "SELECT ID, fname, lname, email, picture, type, thesis FROM allusers, students, advises WHERE f_id=" . $_SESSION['userid'] . " and s_id=ID and type='doctorate' and acctype=8 and ID=SID and ID=" . $_POST['searchid'];
		$dataP = mysqli_query($dbc, $query);
		if($dataP != FALSE){
		    $rowCount2 = mysqli_num_rows($dataP);
		}
	    }
	}
	$_POST['iFlag'] = 0;

	//Default
	if($rowCount1 == 0 && $rowCount2 == 0){
	    //Select query listing masters advisees
	    $query = "SELECT ID, fname, lname, email, picture, type, thesis FROM allusers, students, advises WHERE f_id=" . $_SESSION['userid'] . " and s_id=ID and type='masters' and acctype=8 and ID=SID";
	    $dataM = mysqli_query($dbc, $query);
	    $rowCount1 = mysqli_num_rows($dataM);

            //Select query listing doctorate advisees
	    $query = "SELECT ID, fname, lname, email, picture, type, thesis FROM allusers, students, advises WHERE f_id=" . $_SESSION['userid'] . " and s_id=ID and type='doctorate' and acctype=8 and ID=SID";
	    $dataP = mysqli_query($dbc, $query);
	    $rowCount2 = mysqli_num_rows($dataP);
	}

	echo '<table class="table table-hover table-dark">';
	echo '<thead><tr>';
	echo '<th scope="col">Profile</th>';
	echo '<th scope="col">Student ID</th>';
	echo '<th scope="col">Last Name</th>';
	echo '<th scope="col">First Name</th>';
	echo '<th scope="col">Email</th>';
	echo '<th scope="col">Degree</th>';
	echo '<th scope="col">View Transcript</th>';
	echo '<th scope="col">View Form 1</th>';
	echo '<th scope="col">Approve Thesis</th>';
	echo '<th scope="col">Message</th>';
	echo '<th scope="col">Inbox</th>';
	echo '</tr></thead>';
	echo '<tbody>';
	if($rowCount1 != 0){
	    echo '<tr><td align="center" colspan="10"><h1>Masters Degree Advisees</h1></td></tr>';
	}
	while($row = mysqli_fetch_array($dataM)){
	    echo '<tr>';
	    if(is_file(MM_UPLOADPATH . $row['picture']) && filesize(MM_UPLOADPATH . $row['picture']) > 0){
	    	echo '<td><img src="' . MM_UPLOADPATH . $row['picture'] . '" alt="' . $row['lname'] . ', ' . $row['fname'] . '" width="150" height="150"/></td>';
	    }
	    echo '<td>' . $row['ID'] . '</td>';
	    echo '<td>' . $row['lname'] . '</td>';
	    echo '<td>' . $row['fname'] . '</td>';
	    echo '<td>' . $row['email'] . '</td>';
	    echo '<td>' . $row['type'] . '</td>';

	    //View transcript
	    echo '<form id="' . $row['ID'] . '" method="post" action="../transcript.php">';
            echo '<input type="hidden" id="viewB" name="viewB" value="' . $row['ID'] . '">';
	    echo '<td><button type="submit" class="btn btn-outline-success my-2 my-sm-0">' . $row['ID'] . '</button></td>';
	    echo '</form>';
            
	    //View form 1
	    echo '<form id="F' . $row['ID'] . '" method="post" action="form1.php">';
            echo '<input type="hidden" id="viewB" name="viewB" value="' . $row['ID'] . '">';
	    echo '<td><button type="submit" class="btn btn-outline-success my-2 my-sm-0">Form 1</button></td>';
	    echo '</form>';
            
	    //No thesis needed
	    echo '<td>No Thesis Required</td>';
	    
	    //Message button
	    echo '<form id="EM' . $row['ID'] . '" method="post" action="message.php">';
	    echo '<input type="hidden" id="msg" name="msg" value="' . $row['ID'] . '">';
	    echo '<td><button type="submit" class="btn btn-outline-success my-2 my-sm-0">Message</button></td>';
	    echo '</form>';
	   
	    //View Inbox
	    echo '<form id="IN' . $row['ID'] . '" method="post" action="inbox.php">';
	    echo '<input type="hidden" id="inID" name="inID" value="' . $row['ID'] . '">';
	    echo '<td><button type="submit" class="btn btn-outline-success my-2 my-sm-0">View Inbox</button></td>';
	    echo '</form>';
	    echo '</tr>';
	}
	if($rowCount2 != 0){
	    echo '<tr><td align="center" colspan="10"><h1>PhD Advisees</h1></td></tr>';
	}  
	  while($row = mysqli_fetch_array($dataP)){
	    echo '<tr>';
	    if(is_file(MM_UPLOADPATH . $row['picture']) && filesize(MM_UPLOADPATH . $row['picture']) > 0){
	    	echo '<td><img src="' . MM_UPLOADPATH . $row['picture'] . '" alt="' . $row['lname'] . ', ' . $row['fname'] . '" width="150" height="150"/></td>';
	    }
	    echo '<td>' . $row['ID'] . '</td>';
	    echo '<td>' . $row['lname'] . '</td>';
	    echo '<td>' . $row['fname'] . '</td>';
	    echo '<td>' . $row['email'] . '</td>';
	    echo '<td>' . $row['type'] . '</td>';

	    //View transcript
	    echo '<form id="' . $row['ID'] . '" method="post" action="../transcript.php">';
            echo '<input type="hidden" id="viewB" name="viewB" value="' . $row['ID'] . '">';
	    echo '<td><button type="submit" class="btn btn-outline-success my-2 my-sm-0">' . $row['ID'] . '</button></td>';
	    echo '</form>';
            
	    //View form 1
	    echo '<form id="F' . $row['ID'] . '" method="post" action="form1.php">';
            echo '<input type="hidden" id="viewB" name="viewB" value="' . $row['ID'] . '">';
	    echo '<td><button type="submit" class="btn btn-outline-success my-2 my-sm-0">Form 1</button></td>';
	    echo '</form>';
            
	    if($row['thesis'] == 1){
	        echo '<td>Thesis Approved</td>';
	    }else{
	        //Approve Thesis
	        echo '<form id="A' . $row['ID'] . '" method="post" action="' . $_SERVER['PHP_SELF'] . '">';
	        echo '<input type="hidden" id="thesis" name="thesis" value="' . $row['ID'] . '">';
	        echo '<td><button type="submit" class="btn btn-outline-success my-2 my-sm-0">Approve</button></td>';
	        echo '</form>';
	    }
            
	    // Message button
	    echo '<form id="EM' . $row['ID'] . '" method="post" action="message.php">';
	    echo '<input type="hidden" id="msg" name="msg" value="' . $row['ID'] . '">';
	    echo '<td><button type="submit" class="btn btn-outline-success my-2 my-sm-0">Message</button></td>';
	    echo '</form>';
	   
	    // View Inbox
	    echo '<form id="IN' . $row['ID'] . '" method="post" action="inbox.php">';
	    echo '<input type="hidden" id="inID" name="inID" value="' . $row['ID'] . '">';
	    echo '<td><button type="submit" class="btn btn-outline-success my-2 my-sm-0">View Inbox</button></td>';
	    echo '</form>';
	    echo '</tr>';
	}

	echo '</tbody>';
	echo '</table>';
	mysqli_close($dbc);
    }
?>
    
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>

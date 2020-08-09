<?php
    session_start();
    $page_title = "Graduate Secretary Portal";
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

    <title>Grad Secretary Portal</title>
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
        <a class="nav-link" href="GS_index.php">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="personalinfo.php">Personal Info</a>
      </li>
    </ul>

    <form id="querySearch" class="form-inline my-2 my-lg-0" method = "post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <input class="form-control mr-sm-2" type="text" placeholder="Admission/Grad Year" aria-label="Search" name="searchyear">
      <select class="custom-select my-1 mr-sm-2" name="degree">
          <option value="default" selected>Degree</option>
          <option value="masters">Masters</option>
          <option value="doctorate">Doctorate</option>
      </select>
      <select class="custom-select my-1 mr-sm-2" name="semester">
          <option value="default" selected>Semester</option>
          <option value="Fall">Fall</option>
          <option value="Spring">Spring</option>
      </select>
      <select class="custom-select my-1 mr-sm-2" name="type">
          <option value="8" selected>Student</option>
          <option value="9">Alumni</option>
          <option value="10">Graduating Student</option>
      </select>
      <input type="hidden" id="qFlag" name="qFlag" value="1">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>

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
<?php require_once('navmenu.php'); ?>
    <h1>Student Search</h1>
<?php
    require_once('appvars.php');

    //Acquire login credentials
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    
    //Test for GS approval for graduation
    if(isset($_POST['approveID'])){
    	if($_POST['approveID'] != -1){
    	    //remove from allusers
	    $query = "UPDATE allusers set acctype=9, year=" . date("Y") . " WHERE ID =" . $_POST['approveID'];
	    $data = mysqli_query($dbc, $query);

            //Remove form 1
	    $query = "DELETE FROM form1 WHERE f1_id =" . $_POST['approveID'];
	    $data = mysqli_query($dbc, $query);
           
            //Remove student status
	    $query = "DELETE FROM students WHERE SID='" . $_POST['approveID'] . "'";
	    $data = mysqli_query($dbc, $query);
           
            //Remove advises relationship
            $query = "DELETE FROM advises WHERE s_id=" . $_POST['approveID'];
	    $data = mysqli_query($dbc, $query);

	    //Reset post variable
	    $_POST['approveID'] = -1;
        }
    }
    
    // Check if faculty had been removed
    if(isset($_POST['Rfid'])){
        if($_POST['Rfid'] != -1){
	    $remove = "DELETE FROM advises WHERE f_id=" . $_POST['Rfid'] . " and s_id=" . $_POST['Rsid'];
	    $test = mysqli_query($dbc, $remove);
	}
    }
    // Reset post variables
    $_POST['Rfid'] = -1;
    $_POST['Rsid'] = -1;

    // Check if faculty had been assigned
    if(isset($_POST['fid'])){
        if($_POST['fid'] != -1){    
	    $assign = "INSERT INTO advises (f_id, s_id) VALUES(" . $_POST['fid'] . ", " . $_POST['sid'] . ")";
	    mysqli_query($dbc, $assign);
	}
    }
    // Reset post variable
    $_POST['fid'] = -1;

    if(isset($_SESSION['userid'])){
	$rowCount = 0;
        
	// Search by queries
	if(isset($_POST['qFlag'])){
	    if($_POST['qFlag'] != 0){
                // Everything empty or default except type
		if(empty($_POST['searchyear']) && strcmp($_POST['degree'], "default")==0 && strcmp($_POST['semester'], "default")==0){
                    // If student
                    if($_POST['type'] == 8){
		        $query = "SELECT ID, fname, lname, email, picture, type FROM allusers WHERE acctype=8"; 
                    // If student cleared for graduation
		    }else if($_POST['type'] == 10){
			$query = "SELECT ID, fname, lname, email, picture, type FROM allusers, advises WHERE acctype=8 and ID=s_id and gradstatus=1"; 
                    // If alumni
		    }else{
		        $query = "SELECT ID, fname, lname, email, picture, type FROM allusers WHERE acctype=9";    
		    }
		}

                // Everything is empty or default except degree and type
		if(empty($_POST['searchyear']) && strcmp($_POST['degree'], "default")!=0 && strcmp($_POST['semester'], "default")==0){
                    // If student
                    if($_POST['type'] == 8){
		        $query = "SELECT ID, fname, lname, email, picture, type FROM allusers WHERE acctype=8 and type='" . $_POST['degree'] . "'"; 
                    // If student cleared for graduation
		    }else if($_POST['type'] == 10){
			$query = "SELECT ID, fname, lname, email, picture, type FROM allusers, advises WHERE acctype=8 and ID=s_id and gradstatus=1 and type='" . $_POST['degree'] . "'"; 
                    // If alumni
		    }else{
		        $query = "SELECT ID, fname, lname, email, picture, type FROM allusers WHERE acctype=9 and type='" . $_POST['degree'] . "'";    
		    }
		}

                // Everything empty or default except semester and type
		if(empty($_POST['searchyear']) && strcmp($_POST['degree'], "default")==0 && strcmp($_POST['semester'], "default")!=0){
                    // If student
                    if($_POST['type'] == 8){
		        $query = "SELECT ID, fname, lname, email, picture, type FROM allusers WHERE acctype=8 and semester='" . $_POST['semester'] . "'"; 
                    // If student cleared for graduation
		    }else if($_POST['type'] == 10){
			$query = "SELECT ID, fname, lname, email, picture, type FROM allusers, advises WHERE acctype=8 and ID=s_id and gradstatus=1 and semester='" . $_POST['semester'] . "'"; 
                    // If alumni
		    }else{
		        $query = "SELECT ID, fname, lname, email, picture, type FROM allusers WHERE acctype=9 and semester='" . $_POST['semester'] . "'";    
		    }
		}

                // Everything empty or default except year and type
		if(!empty($_POST['searchyear']) && strcmp($_POST['degree'], "default")==0 && strcmp($_POST['semester'], "default")==0){
                    // If student
                    if($_POST['type'] == 8){
		        $query = "SELECT ID, fname, lname, email, picture, type FROM allusers WHERE acctype=8 and year=" . $_POST['searchyear']; 
                    // If student cleared for graduation
		    }else if($_POST['type'] == 10){
			$query = "SELECT ID, fname, lname, email, picture, type FROM allusers, advises WHERE acctype=8 and ID=s_id and gradstatus=1 and year=" . $_POST['searchyear']; 
                    // If alumni
		    }else{
		        $query = "SELECT ID, fname, lname, email, picture, type FROM allusers WHERE acctype=9 and year=" . $_POST['searchyear'];    
		    }
		}

                // Everything empty or default except year, degree, and type
		if(!empty($_POST['searchyear']) && strcmp($_POST['degree'], "default")!=0 && strcmp($_POST['semester'], "default")==0){
                    // If student
                    if($_POST['type'] == 8){
		        $query = "SELECT ID, fname, lname, email, picture, type FROM allusers WHERE acctype=8 and year=" . $_POST['searchyear'] . " and type='" . $_POST['degree'] . "'"; 
                    // If student cleared for graduation
		    }else if($_POST['type'] == 10){
			$query = "SELECT ID, fname, lname, email, picture, type FROM allusers, advises WHERE acctype=8 and ID=s_id and gradstatus=1 and year=" . $_POST['searchyear'] . " and type='" . $_POST['degree'] . "'"; 
                    // If alumni
		    }else{
		        $query = "SELECT ID, fname, lname, email, picture, type FROM allusers WHERE acctype=9 and year=" . $_POST['searchyear'] . " and type='" . $_POST['degree'] . "'";    
		    }
		}

                // Everything empty or default except year, semester, and type
		if(!empty($_POST['searchyear']) && strcmp($_POST['degree'], "default")==0 && strcmp($_POST['semester'], "default")!=0){
                    // If student
                    if($_POST['type'] == 8){
		        $query = "SELECT ID, fname, lname, email, picture, type FROM allusers WHERE acctype=8 and year=" . $_POST['searchyear'] . " and semester='" . $_POST['semester'] . "'"; 
                    // If student cleared for graduation
		    }else if($_POST['type'] == 10){
			$query = "SELECT ID, fname, lname, email, picture, type FROM allusers, advises WHERE acctype=8 and ID=s_id and gradstatus=1 and year=" . $_POST['searchyear'] . " and semester='" . $_POST['semester'] . "'"; 
                    // If alumni
		    }else{
		        $query = "SELECT ID, fname, lname, email, picture, type FROM allusers WHERE acctype=9 and year=" . $_POST['searchyear'] . " and semester='" . $_POST['semester'] . "'";    
		    }
		}

                // Everything empty or default except semester, degree, and type
		if(empty($_POST['searchyear']) && strcmp($_POST['degree'], "default")!=0 && strcmp($_POST['semester'], "default")!=0){
                    // If student
                    if($_POST['type'] == 8){
		        $query = "SELECT ID, fname, lname, email, picture, type FROM allusers WHERE acctype=8 and semester='" . $_POST['semester'] . "' and type='" . $_POST['degree'] . "'"; 
                    // If student cleared for graduation
		    }else if($_POST['type'] == 10){
			$query = "SELECT ID, fname, lname, email, picture, type FROM allusers, advises WHERE acctype=8 and ID=s_id and gradstatus=1 and semester='" . $_POST['semester'] . "' and type='" . $_POST['degree'] . "'"; 
                    // If alumni
		    }else{
		        $query = "SELECT ID, fname, lname, email, picture, type FROM allusers WHERE acctype=9 and semester='" . $_POST['semester'] . "' and type='" . $_POST['degree'] . "'";    
		    }
		}

                // Nothing is empty or default
		if(!empty($_POST['searchyear']) && strcmp($_POST['degree'], "default")!=0 && strcmp($_POST['semester'], "default")!=0){
                    // If student
                    if($_POST['type'] == 8){
		        $query = "SELECT ID, fname, lname, email, picture, type FROM allusers WHERE acctype=8 and semester='" . $_POST['semester'] . "' and year=" . $_POST['searchyear'] . " and type='" . $_POST['degree'] . "'"; 
                    // If student cleared for graduation
		    }else if($_POST['type'] == 10){
			$query = "SELECT ID, fname, lname, email, picture, type FROM allusers, advises WHERE acctype=8 and ID=s_id and gradstatus=1 and semester='" . $_POST['semester'] . "' and year=" . $_POST['searchyear'] . " and type='" . $_POST['degree'] . "'"; 
                    // If alumni
		    }else{
		        $query = "SELECT ID, fname, lname, email, picture, type FROM allusers WHERE acctype=9 and semester='" . $_POST['semester'] . "' and year=" . $_POST['searchyear'] . " and type='" . $_POST['degree'] . "'";    
		    }
		}	        
                
		$data = mysqli_query($dbc, $query);
		$rowCount = mysqli_num_rows($data);
	    }
	}
	$_POST['qFlag'] = 0;

	// Search by name
	if(isset($_POST['nFlag'])){
	    if($_POST['nFlag'] != 0){        
	        $query = "SELECT ID, fname, lname, email, picture, type FROM allusers WHERE acctype=8 and concat_ws(' ', fname, lname) like '%" . $_POST['searchname'] . "%'";
		$data = mysqli_query($dbc, $query);
		$rowCount = mysqli_num_rows($data);
	    }
	}
        $_POST['nFlag'] = 0;

	// Search by id
	if(isset($_POST['iFlag'])){
	    if($_POST['iFlag'] != 0){        
	        $query = "SELECT ID, fname, lname, email, picture, type FROM allusers WHERE acctype=8 and ID=" . $_POST['searchid'];
		$data = mysqli_query($dbc, $query);
		if($data != FALSE){
		    $rowCount = mysqli_num_rows($data);
		}
	    }
	}
	$_POST['iFlag'] = 0;
        
	//Default
	if($rowCount == 0){
	    //Select query listing current students in system
	    $query = "SELECT ID, fname, lname, email, picture, type FROM allusers WHERE acctype=8";
	    $data = mysqli_query($dbc, $query);
	}

	echo '<table class="table table-hover table-dark">';
	echo '<thead><tr>';
	echo '<th scope="col">Profile</th>';
	echo '<th scope="col">Graduation</th>';
	echo '<th scope="col">Student ID</th>';
	echo '<th scope="col">Last Name</th>';
	echo '<th scope="col">First Name</th>';
	echo '<th scope="col">Email</th>';
	echo '<th scope="col">Degree</th>';
	echo '<th scope="col">View Transcript</th>';
	echo '<th scope="col">View Form 1</th>';
	echo '<th scope="col">Advisor</th>';
	echo '</tr></thead>';
	echo '<tbody>';
	while($row = mysqli_fetch_array($data)){
	    echo '<tr>';
	    if(is_file(MM_UPLOADPATH . $row['picture']) && filesize(MM_UPLOADPATH . $row['picture']) > 0){
	    	echo '<td><img src="' . MM_UPLOADPATH . $row['picture'] . '" alt="' . $row['lname'] . ', ' . $row['fname'] . '" width="150" height="150"/></td>';
	    }

	    $usertype = "SELECT acctype FROM allusers WHERE ID=" . $row['ID'];
	    $datatype = mysqli_query($dbc, $usertype);
	    $utype = mysqli_fetch_array($datatype);
            
	    // If student, print graduation status
            if($utype['acctype'] == 8){
	        $approve = "SELECT s_id FROM advises WHERE gradstatus=1 and s_id=" . $row['ID'];
	        $dataA = mysqli_query($dbc, $approve);

	        if(mysqli_num_rows($dataA) == 1){
	            //Approve Graduation
	            echo '<form id="A' . $row['ID'] . '" method="post" action="' . $_SERVER['PHP_SELF'] . '">';
	            echo '<input type="hidden" id="approveID" name="approveID" value="' . $row['ID'] . '">';
	            echo '<input type="hidden" id="approveDeg" name="approveDeg" value="' . $row['type'] . '">';
	            echo '<td><button type="submit" class="btn btn-outline-success my-2 my-sm-0">Approve</button></td>';
	            echo '</form>';
	        }else{
		    //Grad requirements not met
	            echo '<td>Action Needed</td>';
		}
	    // If alumni, not applicable
	    }else{
	        echo '<td>Not Applicable</td>';
	    }

	    echo '<td>' . $row['ID'] . '</td>';
	    echo '<td>' . $row['lname'] . '</td>';
	    echo '<td>' . $row['fname'] . '</td>';
	    echo '<td>' . $row['email'] . '</td>';
	    echo '<td>' . $row['type'] . '</td>';
	    
	    //View transcript
	    echo '<form id="' . $row['ID'] . '" method="post" action="../transcript.php">';
            echo '<input type="hidden" id="view" name="view" value="' . $row['ID'] . '">';
	    echo '<td><button type="submit" class="btn btn-outline-success my-2 my-sm-0">' . $row['ID'] . '</button></td>';
	    echo '</form>';
	    if($utype['acctype'] == 8){
	        //View form 1 
	        echo '<form id="F' . $row['ID']. '" method="post" action="form1.php">';
	        echo '<input type="hidden" id="view" name="view" value="' . $row['ID'] . '">';
	        echo '<td><button type="submit" class="btn btn-outline-success my-2 my-sm-0">Form 1</button></td>';
                echo '</form>';
	    
	        // Either view advisor information or assign advisor
	        $subQuery = "SELECT f_id, s_id FROM advises WHERE s_id=" . $row['ID'];
	        $data2 = mysqli_query($dbc, $subQuery);
	        //If exists, display faculty advisor
	        if(mysqli_num_rows($data2) == 1){
		    //Fetch data
		    $row2 = mysqli_fetch_array($data2);
	   	    echo '<form id="V' . $row['ID'] . '" method="post" action="personalinfo.php">';
		    echo '<input type="hidden" id="getInfo" name="getInfo" value="' . $row2['f_id'] . '">';
		    echo '<td><button type="submit" class="btn btn-outline-success my-2 my-sm-0">' . $row2['f_id'] . '</button></td>';
		    echo '</form>';

		    //Remove advisor
		    echo '<form id="R' . $row['ID'] . '" method="post" action="' . $_SERVER['PHP_SELF'] . '">';
		    echo '<input type="hidden" id="Rfid" name="Rfid" value="' . $row2['f_id'] . '">';
		    echo '<input type="hidden" id="Rsid" name="Rsid" value="' . $row['ID'] . '">';
		    echo '<td><button type="submit" class="btn btn-outline-danger my-2 my-sm-0">Remove</button></td>';
		    echo '</form>';
	        }else{ 		
		    echo '<form id="G' . $row['ID'] . '" method="post" action="getFaculty.php">';
		    echo '<input type="hidden" id="sid" name="sid" value="' . $row['ID'] . '">';
		    echo '<input type="hidden" id="lname" name="lname" value="' . $row['lname'] . '">';
		    echo '<input type="hidden" id="fname" name="fname" value="' . $row['fname'] . '">';
		    echo '<td><button type="submit" class="btn btn-outline-success my-2 my-sm-0">Assign</button></td>';
		    echo '</form>';
	        } 
	    }else{
	        echo '<td>Not Applicable</td>';
		echo '<td>Not Applicable</td>';
	    }
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

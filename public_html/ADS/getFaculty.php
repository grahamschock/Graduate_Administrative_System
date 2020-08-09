<?php 
    $page_title = "Assign Advisor";
    require_once('../header.php');
?>
<html lang="en">

  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Assign Advisor</title>
  </head>
  <body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">Faculty Search</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="GS_index.php">Home <span class="sr-only">(current)</span></a>
      </li>
    </ul>
    <form id="namesearch" class="form-inline my-2 my-lg-0" method = "post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <input class="form-control mr-sm-2" type="text" placeholder="Name Search" aria-label="Search" name="searchname">
      <input type="hidden" id="nFlag" name="nFlag" value="1">
      <input type="hidden" id="sid" name="sid" value="<?php echo $_POST['sid']; ?>">
      <input type="hidden" id="lname" name="lname" value="<?php echo $_POST['lname']; ?>">
      <input type="hidden" id="fname" name="fname" value="<?php echo $_POST['fname']; ?>">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>
    <form id="idsearch" class="form-inline my-2 my-lg-0" method = "post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <input class="form-control mr-sm-2" type="text" placeholder="ID Search" aria-label="Search" name="searchid">
      <input type="hidden" id="iFlag" name="iFlag" value="1">
      <input type="hidden" id="sid" name="sid" value="<?php echo $_POST['sid']; ?>">
      <input type="hidden" id="lname" name="lname" value="<?php echo $_POST['lname']; ?>">
      <input type="hidden" id="fname" name="fname" value="<?php echo $_POST['fname']; ?>">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
      <a class="btn btn-outline-danger" href="../logout.php" role="button">Logout</a>
    </form>
  </div>
</nav>
<?php
    require_once('connectvars.php');
    require_once('appvars.php');
    session_start();
    if(isset($_POST['sid'])){
    if($_POST['sid'] != -1){
    echo '<h1>Assign Advisor for ' . $_POST['lname'] . ', ' . $_POST['fname'] . ' (' . $_POST['sid'] . ')</h1>';

    if(isset($_SESSION['userid'])){
        //Connect to database
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$rowCount = 0;

	// Search by name
	if(isset($_POST['nFlag'])){
	    if($_POST['nFlag'] != 0){
	        $query = "SELECT ID, fname, lname, email, picture FROM allusers WHERE CAST(acctype as CHAR) LIKE '%6%' and concat_ws(' ', fname, lname) like '%" . $_POST['searchname'] . "%'";
	        $data = mysqli_query($dbc, $query);
		$rowCount = mysqli_num_rows($data);
	    }
	}
	$_POST['nFlag'] = 0;

	// Search by id
	if(isset($_POST['iFlag'])){
	    if($_POST['iFlag'] != 0){
	        $query = "SELECT ID, fname, lname, email, picture FROM allusers WHERE CAST(acctype as CHAR) LIKE '%6%' and ID=" . $_POST['searchid'];
	        $data = mysqli_query($dbc, $query);
		if($data != FALSE){
	            $rowCount = mysqli_num_rows($data);
		}
	    }
	}
	$_POST['iFlag'] = 0;

	//Default
	if($rowCount == 0){
	    //Select query listing current advisors in system
	    $query = "SELECT ID, fname, lname, email, picture FROM allusers WHERE CAST(acctype as CHAR) LIKE '%6%'";
	    $data = mysqli_query($dbc, $query);
	}

	echo '<table class="table table-hover table-dark">';
	echo '<thead><tr>';
	echo '<th scope="col">Profile</th>';
	echo '<th scope="col">Faculty ID</th>';
	echo '<th scope="col">Last Name</th>';
	echo '<th scope="col">First Name</th>';
	echo '<th scope="col">Email</th>';
	echo '<th scope="col">Advisees</th>';
	echo '<th scope="col">Confirm Assignment</th>';
	echo '</tr></thead>';
	echo '<tbody>';
	while($row = mysqli_fetch_array($data)){
	    echo '<tr>';
	    if(is_file(MM_UPLOADPATH . $row['picture']) && filesize(MM_UPLOADPATH . $row['picture']) > 0){
	    	echo '<td><img src="' . MM_UPLOADPATH . $row['picture'] . '" alt="' . $row['lname'] . ', ' . $row['fname'] . '" width="150" height="150"/></td>';
	    }
	    echo '<td>' . $row['ID'] . '</td>';
	    echo '<td>' . $row['lname'] . '</td>';
	    echo '<td>' . $row['fname'] . '</td>';
	    echo '<td>' . $row['email'] . '</td>';
	    echo '<td>';
	    $subQuery = "SELECT s_id FROM advises WHERE f_id=" . $row['ID'];
	    $data2 = mysqli_query($dbc, $subQuery);
	    while($row2 = mysqli_fetch_array($data2)){
		echo '<form id="' . $row2['s_id'] . '" method="post" action="personalinfo.php">';
                echo '<input type="hidden" id="sFlag" name="sFlag" value="1">';
                echo '<input type="hidden" id="sid" name="sid" value="' . $_POST['sid'] . '">';
                echo '<input type="hidden" id="lname" name="lname" value="' . $_POST['lname'] . '">';
                echo '<input type="hidden" id="fname" name="fname" value="' . $_POST['fname'] . '">';
		echo '<input type="hidden" id="getInfo" name="getInfo" value="' . $row2['s_id'] . '">';
		echo '<button type="submit" class="btn btn-outline-success my-2 my-sm-0">' . $row2['s_id'] . '</button>';
		echo '</form>';
	    }
	    echo '</td>';
	    echo '<form id="A' . $row['ID'] . '" method="post" action="GS_index.php">';
	    echo '<input type="hidden" id="fid" name="fid" value="' . $row['ID'] . '">';
	    echo '<input type="hidden" id="sid" name="sid" value="' . $_POST['sid'] . '">';
	    echo '<td><button type="submit" class="btn btn-outline-success my-2 my-sm-0">Confirm</button></td>';
	    echo '</form>';
	    echo '</tr>';
	}
	echo '</tbody>';
	echo '</table>';
	mysqli_close($dbc);
    }
    $_POST['sid'] = -1;
    }
    }
?>
    
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>

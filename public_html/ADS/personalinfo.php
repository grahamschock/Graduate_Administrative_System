<html lang="en">
<?php 
    $page_title = "Personal Info";
    require_once('../header.php');
?>
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Personal Info</title>
  </head>
  <body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#"> Personal Info</a>
<!--
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
-->
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
<?php
    session_start();
    
    //Set returnPage based on user type
    $returnPage = "logout.php";
    
    // Split into three separate digits
    $first = intdiv($_SESSION['acctype'], 100);
    $second = intdiv($_SESSION['acctype'], 10) % 10;
    $third = $_SESSION['acctype'] % 10;

    if($_SESSION['acctype'] == 1){
        $returnPage = "accounts.php";
    }else if($_SESSION['acctype'] == 2){
        $returnPage = "GS_index.php";
    }else if($first == 6 || $second == 6 || $third == 6){
        $returnPage = "FA_index.php";
    }else if($_SESSION['acctype'] == 8){
        $returnPage = "../index.php";
    }else if($_SESSION['acctype'] == 9){
        $returnPage = "alumni_index.php";
    }

    //Initialize session variable
    $_SESSION["getInfo"] = -1;
    //If form is submitted
    if(isset($_POST["getInfo"])){
	if($_POST["getInfo"] != -1){
            //Set session variable
	    $_SESSION["getInfo"] = $_POST["getInfo"];
	}
    }

    //If we came from getFaculty
    $_SESSION["sFlag"] = 0;
    //If form is submitted
    if(isset($_POST["sFlag"])){
        if($_POST["sFlag"] != 0){
	    //Set session variable
            $_SESSION["sFlag"] = $_POST["sFlag"];
	}
    }

    if($_SESSION["getInfo"] != -1){
	if($_SESSION["sFlag"] == 0){    
 	    echo '<li class="nav-item"><a class="nav-link" href="GS_index.php">Back</a></li>';
	}else{
            echo '<form id="ret" method="post" action="getFaculty.php">';
	    echo '<input type="hidden" id="sid" name="sid" value="' . $_POST['sid'] . '">';
	    echo '<input type="hidden" id="lname" name="lname" value="' . $_POST['lname'] . '">';
	    echo '<input type="hidden" id="fname" name="fname" value="' . $_POST['fname'] . '">';
	    echo '</form>';
	    echo '<li class="nav-item">';    
            echo '<a class="nav-link" href="#" onclick="document.getElementById(\'ret\').submit();">Back</a>';	    
	    echo '</li>';
	}
    }else{
?>
      <li class="nav-item">
      <a class="nav-link" href="<?php echo $returnPage; ?>">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="personalinfo.php">Personal Info</a>	    
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="editPersonalInfo.php">Edit Personal Info</a>
      </li>
<?php
    }
    echo '</ul>';
    echo '</div>';
    echo '</nav>';
    echo '<h1>My Info</h1>';
    require_once('connectvars.php');
    require_once('appvars.php');
    
    //If we are coming from assign.php, use getInfo as user id. Otherwise use $_SESSION['user_id']
    $id = $_SESSION['userid'];
    if($_SESSION["getInfo"] != -1){
        $id = $_SESSION["getInfo"];
    }
    
    if(isset($id)){
	//Connect to database
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        //Select query for allusers info
        $query = "SELECT fname, lname, email, address, city, state, birthdate, picture FROM allusers WHERE ID=" . $id;
	$data = mysqli_query($dbc, $query);

	if(mysqli_num_rows($data) == 1){
            $row = mysqli_fetch_array($data);
	    echo '<table class="table table-hover table-dark">';
	    echo '<tbody>';
	    if(is_file(MM_UPLOADPATH . $row['picture']) && filesize(MM_UPLOADPATH . $row['picture']) > 0){
                echo '<tr><td><img src="' . MM_UPLOADPATH . $row['picture'] . '" alt="' . $row['lname'] . ', ' . $row['fname'] . '" width="150" height="150"/></td></tr>';
            }
            echo '<tr><th scope="col">Name: </th><td>' . $row['lname'] . ', ' . $row['fname'] . '</td></tr>';
	    echo '<tr><th scope="col">Email: </th><td>' . $row['email'] . '</td></tr>';
	    echo '<tr><th scope="col">Address: </th><td>' . $row['address'] . '</td></tr>';
	    echo '<tr><th scope="col">City: </th><td>' . $row['city'] . '</td></tr>';
	    echo '<tr><th scope="col">State: </th><td>' . $row['state'] . '</td></tr>';
	    echo '<tr><th scope="col">Birthdate: </th><td>' . $row['birthdate'] . '</td></tr>';
	    echo '</tbody>';
	    echo '</table>';
	}

	mysqli_close($dbc);
    }
    
    //Clear Post variable just in case
    $_POST["getInfo"] = -1;
    $_POST['sFlag'] = 0;
?>  
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>

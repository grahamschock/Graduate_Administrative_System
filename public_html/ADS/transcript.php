<?php
    require_once('connectvars.php');

    session_start();
    
    //Set default $user_id
    $user_id = $_SESSION['userid'];
    //Initialize session variable
    $_SESSION["getInfo"] = -1;
    //If form is submitted
    if(isset($_POST["getInfo"])){
	if($_POST["getInfo"] != -1){
            //Set session variable
	    $_SESSION["getInfo"] = $_POST["getInfo"];
	}
    }
    //If form is submitted

    if($_SESSION["getInfo"] != -1){
        $user_id = $_SESSION["getInfo"];
    }

    //flag indicating view transcript option originating from GS_index
    $GSflag = 0;
    //Access database
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    //If post view exists, use for $user_id
    if(isset($_POST['view'])){
        if($_POST['view'] != -1){
            $user_id = $_POST['view'];
            //Set flag and reset post variable
	    $GSflag = 1;
	    $_POST['view'] = -1;
	}

    }
    
    //If post viewB exists, use for $user_id
    if(isset($_POST['viewB'])){
        if($_POST['viewB'] != -1){
            $user_id = $_POST['viewB'];
            //Set flag and reset post variable
	    $GSflag = 2;
	    $_POST['viewB'] = -1;
	}

    }

    // If alumni, use alumni table, otherwise use student table for students and GS access
    if($_SESSION['acctype'] == 9){
	$query = "SELECT c_dep,c_num,semester,year,hrs,grade,gpa FROM transcript,taken,alumni WHERE transID=tid AND atid=tid AND aid='$user_id'";
	$queryG = "SELECT gpa FROM transcript,alumni WHERE tid=atid and aid='$user_id'";
    }else{
	$query = "SELECT c_dep,c_num,semester,year,hrs,grade,gpa FROM transcript,taken,student WHERE transID=tid AND stid=tid AND sid='$user_id'";
        $queryG = "SELECT gpa FROM transcript,student WHERE tid=stid and sid='$user_id'";
    }
    $data = mysqli_query($dbc, $query);
    $dataG = mysqli_query($dbc, $queryG);

    //Acquire GPA
    $rowG = mysqli_fetch_array($dataG);
?>

<html lang="en">

  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Transcript</title>
  </head>
  <body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">ADS System</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
<div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
<?php
    //Set returnPage based on user type
    $returnPage = "logout.php";
    if($_SESSION['user_type'] == 4){
        $returnPage = "student_index.php";
    }else if($_SESSION['user_type'] == 5){
        $returnPage = "alumni_index.php";
    }else {
	$returnPage = "admin.php";
    }

    if($GSflag == 1){
      echo '<li class="nav-item">';
      echo '<a class="nav-link" href="GS_index.php">Back</a>';
      echo '</li>';
    }else if($GSflag == 2){
      echo '<li class="nav-item">';
      echo '<a class="nav-link" href="FA_index.php">Back</a>';
      echo '</li>';
    }else{   
?>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo $returnPage; ?>">Back<span class="sr-only">(current)</span></a>
      </li>
<?php 
    } 
?>
    </ul>
    <form class="form-inline my-2 my-lg-0" method = "post" action="search.php">
      <a class="btn btn-outline-danger" href="logout.php" role="button">Logout</a>
    </form>
  </div>
</nav>
    <h1>Transcript</h1>
    <?php
    if(empty($_GET['err'])){
      
    }else{
      $errmsg = $_GET['err'];
      echo "$errmsg";

    }
  ?>
<table class="table table-hover table-dark">
  <thead>
    <tr>
      <th scope="col">Department</th>
      <th scope="col">Course Number</th>
      <th scope="col">Semester</th>
      <th scope="col">Year</th>
      <th scope="col">Credit Hrs</th>
      <th scope="col">Grade</th>
    </tr>
  </thead>
  <tbody>
<?php
    $GPA = 0;
    $credithours = 0;
    $earnedcredit = 0;
   $total = 0; 
   while($row = mysqli_fetch_assoc($data)){
   echo"
    <tr>
      <td>{$row['c_dep']}</td>
      <td>{$row['c_num']}</td>
      <td>{$row['semester']}</td>
      <td>{$row['year']}</td>
      <td>{$row['hrs']}</td>
      <td>{$row['grade']}</td>
    </tr>";
     $currcredithr = $row['hrs'];
     $credithours += $currcredithr;
     switch ($row['grade']) {
             case "A":
                 $earnedcredit += $currcredithr * 4;
                 break;
             case "A-":
                 $earnedcredit += $currcredithr * 3.7;
                 break;
             case "B+":
                 $earnedcredit += $currcredithr * 3.3;
                 break;
             case "B":
                 $earnedcredit += $currcredithr * 3.0;
                 break;
             case "B-":
                 $earnedcredit += $currcredithr * 2.7;
                 break;
             case "C+":
                 $earnedcredit += $currcredithr * 2.3;
                 break;
             case "C":
                 $earnedcredit += $currcredithr * 2.0;
                 break;     
             case "F":
                 $earnedcredit += $currcredithr * 0;
                 break;    
             default:
                 $earnedcredit = 0;
         }
     }
     $GPA = $earnedcredit/$credithours;
?>
  <tr>
    <td align="center" colspan="3"><h1>GPA: <?php echo $GPA ?></h1></td>
    <td align="center" colspan="3"><h1>Credit Hours: <?php echo $credithours; ?></h1></td>
  </tr>
  </tbody>
</table>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>


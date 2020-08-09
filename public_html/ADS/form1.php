<?php
    $page_title = "Form 1";
    require_once('../header.php');
    require_once('connectvars.php');
    session_start();
    
    // Session error variables:
    $_SESSION['error1'] = "";
    $_SESSION['error2'] = "";
    $_SESSION['error3'] = "";
    $_SESSION['error4'] = "";
    $_SESSION['error5'] = "";
    $_SESSION['error6'] = "";
    $_SESSION['error7'] = "";
    $_SESSION['error8'] = "";
    $_SESSION['error9'] = "";
    $_SESSION['error10'] = "";
    $_SESSION['error11'] = "";
    $_SESSION['error12'] = "";

    $_SESSION['dep1'] = "";
    $_SESSION['num1'] = "";
    $_SESSION['dep2'] = "";
    $_SESSION['num2'] = "";
    $_SESSION['dep3'] = "";
    $_SESSION['num3'] = "";
    $_SESSION['dep4'] = "";
    $_SESSION['num4'] = "";
    $_SESSION['dep5'] = "";
    $_SESSION['num5'] = "";
    $_SESSION['dep6'] = "";
    $_SESSION['num6'] = "";
    $_SESSION['dep7'] = "";
    $_SESSION['num7'] = "";
    $_SESSION['dep8'] = "";
    $_SESSION['num8'] = "";
    $_SESSION['dep9'] = "";
    $_SESSION['num9'] = "";
    $_SESSION['dep10'] = "";
    $_SESSION['num10'] = "";
    $_SESSION['dep11'] = "";
    $_SESSION['num11'] = "";
    $_SESSION['dep12'] = "";
    $_SESSION['num12'] = "";

    $_SESSION['errmsg'] = "";

    //Set default $user_id
    $user_id = $_SESSION['userid'];
    //flag indicating view form1 option originating from FA_index
    $FAflag = 0;
    //Access database
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    //If post view exists, user for $user_id
    if(isset($_POST['view'])){
        if($_POST['view'] != -1){
	    $user_id = $_POST['view'];
	    //Set flag and reset post variable
	    $FAflag = 1;
	    $_POST['view'] = -1;
	}
    }

    //If post viewB exists, use for $user_id
    if(isset($_POST['viewB'])){
        if($_POST['viewB'] != -1){
	    $user_id = $_POST['viewB'];
	    //Set flag and reset post variable
	    $FAflag = 2;
	    $_POST['viewB'] = -1;
	}
    }

    $query = "SELECT f1_dep, f1_cnum FROM form1 where f1_id = '$user_id'";
    $data = mysqli_query($dbc, $query);          
?>


<html lang="en">

  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Form 1</title>
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
    if($FAflag == 1){
        echo '<li class="nav-item">';
	echo '<a class="nav-link" href="GS_index.php">Back</a>';
	echo '</li>';
    }else if($FAflag == 2){
        echo '<li class="nav-item">';
	echo '<a class="nav-link" href="FA_index.php">Back</a>';
	echo '</li>';
    }else{
?>
      <li class="nav-item">
        <a class="nav-link" href="../index.php">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="editForm1.php">Edit Form 1</a>
      </li>
<?php
    }
?>
    </ul>
    <form class="form-inline my-2 my-lg-0" method = "post" action="search.php">
      <a class="btn btn-outline-danger" href="../logout.php" role="button">Logout</a>
    </form>
  </div>
</nav>
    <h1>Form 1</h1>
<?php
    if(!empty($_GET['err'])){
        $errmsg = $_GET['err'];
	echo "$errmsg";
	$_GET['err'] = "";
    }
?>
<table class="table table-hover table-dark">
  <thead>
    <tr>
      <th scope="col">Department</th>
      <th scope="col">Course Number</th>
    </tr>
  </thead>
  <tbody>
  <?php while($row = mysqli_fetch_assoc($data)){
   echo"
    <tr>
      <td>{$row['f1_dep']}</td>
      <td>{$row['f1_cnum']}</td>
    </tr>";
  }
?>
  </tbody>
</table>

<?php
    // Check if Faculty advisor functionality is true
    if($FAflag == 2){ 
      $query = "SELECT registrationHold FROM students where SID = '$user_id'";
      $data = mysqli_query($dbc, $query);          
      $row = mysqli_fetch_assoc($data);

      // Check to see if registrationHold HAS NOT BEEN LIFTED
      if($row['registrationHold'] == 0){
        $query = "SELECT form1pass FROM students where SID = '$user_id'";
        $data = mysqli_query($dbc, $query);          
        $row = mysqli_fetch_assoc($data);
        
       // Check to see if form1 audit has passed	
	if($row['form1pass'] == 1){
?>
    <form id="holdApprove" class="form-inline my-2 my-lg-0" method = "post" action="FA_index.php">
    <input type="hidden" id="hold" name="hold" value="<?php echo $user_id; ?>">
      <button class="btn btn-outline-success" type="submit">Remove Hold</button>
    </form>
<?php }else{ ?>
    <form id="holdApprove" class="form-inline my-2 my-lg-0" method = "post" action="FA_index.php">
      <button class="btn btn-outline-danger" type="submit" disabled>Remove Hold</button>
    </form>
<?php }}else{ echo "<strong style=\"color: green;\">Registration Hold Removed</strong>"; }} ?>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>

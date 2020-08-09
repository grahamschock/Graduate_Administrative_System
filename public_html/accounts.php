<?php

	session_start();
	if(!isset($_SESSION["userid"]))
		header("Location: login.php");

  $page_title = 'Accounts';

  require_once('connectvars.php');

  require_once('header.php');

  require_once('navmenu.php');

  if ($_SESSION["acctype"] != "1") {
    echo '<div class="alert alert-danger" role="alert">Error: you do not have the permissions to access this page</div>';
    return;  
  }

  if (isset($_POST['submit-create-account'])) {

    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    
    $entered_password = mysqli_real_escape_string($dbc, trim($_POST['password']));
    $fname = $_POST["firstname"];
    $lname = $_POST["lastname"];
    $acctype = $_POST['acctype'];
    if($acctype == "Student") {
        $acctype = "8";
        $studenttype = $_POST['studenttype'];
        $query = "INSERT INTO allusers(password, acctype, fname, lname, type) VALUES ('$entered_password', '$acctype', '$fname', '$lname', '$studenttype')";
        
    }
    
      // insert into accounts
    else {
      $query = "INSERT INTO allusers(password, acctype, fname, lname) VALUES ('$entered_password', '$acctype', '$fname', '$lname')";
    }
      //echo $query . "<br>";
      $data = mysqli_query($dbc, $query);
      $latestID = mysqli_insert_id($dbc); // auto-incremented id which was just inserted
      echo "<div class='alert alert-danger' role='alert'> New ID Created: $latestID </div>";
      

      // insert into tables for specific user types
      if($_POST['acctype'] == "Student") {
          $query = "INSERT INTO students VALUES ('$latestID', 0, 0, NULL)";
          $data = mysqli_query($dbc, $query);
      }
      else if($acctype == 2) {
          $query = "INSERT INTO GS(GSID) VALUES ($latestID)";
          $data = mysqli_query($dbc, $query);
      }
      else {
          $query = "INSERT INTO faculty(FID) VALUES ($latestID)";
      }
      
                 
  }


?>


  <div class="p-3 mb-2 bg-light text-dark">

<h3 style = "text-align:left">Accounts</h3>

<nav class="navbar navbar-light bg-light">
 <ul class="list-group list-group-horizontal">
<li class="list-group-item">
<form class="form-inline" method="post" action="accounts.php">
	<h4>Create new account</h4>
    <input class="form-control mr-sm-2 clearfix" type="Search" placeholder="Password" name="password" required><br />
    <input class="form-control mr-sm-2 clearfix" type="Search" placeholder="Firstname" name="firstname"><br />
    <input class="form-control mr-sm-2 clearfix" type="Search" placeholder="Lastname" name="lastname"><br />
    <select class="form-control mr-sm-2" name="acctype" required onchange="displayStudentTypeDropdown(this)">
      <option value="">User Type</option>
      <option value="Student">Student</option>
      <option value="7">Faculty Instructor</option>
      <option value="2">Graduate Secretary</option>
      <option value="3">Chair</option>
      <option value="5">Faculty Reviewer</option>
      <option value="6">Faculty Advisor</option>
      <option value="7">Instructor</option>
      <option value="57">Instructor & Reviewer</option>
      <option value="67">Instructor & Advisor</option>
      <option value="56">Advisor & Reviewer</option>
      <option value="567">Advisor & Reviewer & Instructor</option>
      <option value="357">Chair & Advisor & Instructor</option>
      <option value="37">Chair & Instructor</option>
      <option value="35">Chair & Advisor</option>
    </select>
    <select class="form-control mr-sm-2" name="studenttype" id="studenttype" style="display: none;">
      <option value="">Student Type</option>
      <option value="masters">Master's Student</option>
      <option value="doctorate">PhD Student</option>
    </select>

    <button type="submit" class="btn btn-success" name="submit-create-account">Create </button>
  </form>
</li>
</ul>


</nav>



</div>


<ul class="list-group list-group-horizontal">



    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  </body>
</html>

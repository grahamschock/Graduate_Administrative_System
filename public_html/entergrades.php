<?php

	session_start();

	if(!isset($_SESSION["userid"]))
		header("Location: login.php");

  $page_title = 'Enter Grades';

  require_once('connectvars.php');

  require_once('header.php');

  require_once('navmenu.php');
  
if (($_SESSION["acctype"] == "8" )) {
    echo '<div class="alert alert-danger" role="alert">Error: you do not have the permissions to access this page</div>';
    return;  
  }

  // Connect to the database 
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  
  // if entered grades, update them
  if (isset($_POST['submit-enter-grades'])) {
  
    foreach ($_POST as $key=>$value) {
      if (substr($key, 0, 2) == "u-" && $value != '') {
        $newGradeInfo = explode('|', substr($key, 2));
        $courseID = $newGradeInfo[1];
        $studentID = $newGradeInfo[0];
        $semester = str_replace('_', ' ', $newGradeInfo[2]);
        $newgrade = $value;
        $query = "UPDATE takes SET grade='$newgrade' WHERE courseID='$courseID' AND studentID='$studentID' AND semester='$semester'";
        //echo "<br>" . $query . "<br>";
        $result = mysqli_query($dbc, $query);
        if (!$result) {
          echo '<div class="alert alert-danger" role="alert">Error updating database</div>';
        }
      }
    }
  }

  // get classes and populate dropdown

if (strpos($_SESSION["acctype"], "7") !== false) {  // if faculty, get taught classes - TODO check current sem
    $facultyID = $_SESSION["userid"];
    $query = "SELECT cid FROM courses AS c, teaches AS t, allusers as u  WHERE c.cid = t.courseID AND t.facultyID = '$facultyID' AND u.ID = '$facultyID' AND c.semester = 'Spring 2021'";
    
} else {
    $query = "SELECT DISTINCT cid FROM courses ORDER BY cid ASC";
    //echo $query;
  }

  $data = mysqli_query($dbc, $query);

?>

  <div class="p-3 mb-2 bg-light text-dark">

<h3 style = "text-align:left">Enter Grades</h3>

<nav class="navbar navbar-light bg-light">
 <ul class="list-group list-group-horizontal">
<li class="list-group-item">
<form class="form-inline" method="post" action="entergrades.php">
    <!-- <input class="form-control mr-sm-2" type="Search" placeholder="Student Name" name="studentSearch"> -->
    <select class="form-control mr-sm-2" name="classSearch">
      <option value="all"> Course Code</option>
    <?php
     while ($row = mysqli_fetch_array($data)) {
      echo '<option value="' . $row[cid] . '">'. str_replace("_", " ", $row[cid]) . '</option>';
    }
    ?>
    </select>
    <button class="btn btn-sm btn-outline-secondary" type="submit" name="submit-search-category">Search</button>
  </form>
</li>
</ul>


</nav>

<div class = "row">
  <!-- <div class = "card" style = "width: 18rem;"> -->
    <h4>&nbsp;&nbsp;Displaying students</h4>
  <!-- </div> -->
</div>
<div class = "row">

  <form method="post" action="entergrades.php">
  <table class="table">
    <tr>
      <th>Name</th>
      <th>Course</th>
           <th>Semester</th>
      <th>Current Grade</th>
      <th>New Grade</th>
    </tr>
    
    <?php
      if (isset($_POST['submit-search-category'])) {

          if(strpos($_SESSION["acctype"], "7") !== false) {
              $query = "SELECT takes.semester, grade, lname, fname, SID, courseID FROM students, takes, allusers WHERE students.SID = takes.studentID AND students.SID = allusers.ID AND takes.semester = 'Spring 2021'";
          }
          else {
        $query = "SELECT takes.semester, grade, lname, fname, SID, courseID FROM students, takes, allusers WHERE students.SID = takes.studentID AND students.SID = allusers.ID";
          }

        if (isset($_POST['classSearch']) && $_POST['classSearch'] != 'all') {
          $query .= " AND courseID = '" . $_POST['classSearch'] ."'"  ;
        }
        /*if (isset($_POST['studentSearch']) && trim($_POST['studentSearch']) != '') {
          $query .= " AND name LIKE '%" . $_POST['studentSearch'] ."%'"  ;
        }*/


        $data = mysqli_query($dbc, $query);

        $gradeDropdown = '<select class="form-control mr-sm-2" disableStatus name="dropdownName">
          <option value=""></option>
          <option value="A">A</option>
          <option value="A-">A-</option>
          <option value="B+">B+</option>
          <option value="B">B</option>
          <option value="B-">B-</option>
          <option value="C+">C+</option>
          <option value="C">C</option>
          <option value="F">F</option>
          </select>';

        while ($row = mysqli_fetch_array($data)) {
            $name = $row['lname'] . "," . $row['fname'];
            $updateDisabled = ((strpos($_SESSION["acctype"], "7") !== false) && $row['grade'] != 'IP') ? 'disabled' : ''; // never disabled if GS
          $dropdownName = 'u-' . $row['SID'] . '|' . $row['courseID'] . '|' . $row['semester'];
          $dropdownHTML = str_replace( 'dropdownName', $dropdownName, str_replace('disableStatus', $updateDisabled, $gradeDropdown));
          echo '<tr>';
          echo '<td>' . $name . '</td><td>' . str_replace("_", " ", $row['courseID']) . '</td><td>' . $row['semester']. '</td><td>' . $row['grade'] 
            . '</td><td>' . $dropdownHTML . '</td>';
        }
      }   
    ?>
    
  </table>
<button type="submit" class="btn btn-success" name="submit-enter-grades">Save  </button></form>

</div>

<?php
  require_once('footer.php');
?>

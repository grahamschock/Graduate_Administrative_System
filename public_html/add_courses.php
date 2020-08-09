<?php

	session_start();

	if(!isset($_SESSION["userid"]))
		header("Location: login.php");

  $page_title = 'Create a Class';

  require_once('connectvars.php');

  require_once('header.php');

  // Connect to the database 
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  
    $query = "SELECT DISTINCT cid FROM courses ORDER BY cid ASC";
    //echo $query;

  $data = mysqli_query($dbc, $query);


?>
<div class = "text-right">
<button type = "button" class = "btn btn-primary btn-lg" onclick = "window.location.href = 'rtf_requests.php'"> RTF REQUESTS </button>
                                                                                                                                                                                                                                                  <button type = "button" class = "btn btn-primary btn-lg" onclick = "window.location.href = 'logout.php'"> Logout </button>

                                                                                                                        </div>
  <div class="p-3 mb-2 bg-light text-dark">

<h3 style = "text-align:left">Create new class for Spring 2021</h3>

<nav class="navbar navbar-light bg-light">
 <ul class="list-group list-group-horizontal">
<li class="list-group-item">
<form class="form-inline" method="post" action="add_courses.php">
  <input class="form-control mr-sm-2" type="Search" placeholder="title" name="title">
      <select class = "form-control mr-sm-2" name = "Department">
  <option value = "all"> Department </option>
      <option value = "CSCI"> CSCI </option>
      <option value = "MATH"> MATH </option>
      <option value = "ECE"> ECE </optiion>
</select>

<input class="form-control mr-sm-2" type="number" placeholder="Course #" name="course_num">
<input class="form-control mr-sm-2" type="number" placeholder="Capacity" name="capacity">

  <select class = "form-control mr-sm-2" name = "day">
  <option value = "all"> Day </option>
      <option value = "M"> Monday </option>
      <option value = "T"> Tuesday </option>
      <option value = "W"> Wednesday </optiion>
</select>

  <select class = "form-control mr-sm-2" name = "time">
  <option value = "all"> Time </option>
      <option value = "1500-1730"> 1500-1730 </option>
      <option value = "1600-1830"> 1600-1830 </option>
      <option value = "1800-2030"> 1800-2030 </optiion>
</select>

  <select class = "form-control mr-sm-2" name = "credits">
  <option value = "all"> Credits </option>
      <option value = "1"> 1 </option>
      <option value = "2"> 2 </option>
  <option value = "3"> 3 </optiion>
      <option value = "4"> 4 </optiion>
</select>

    <select class="form-control mr-sm-2" name="prereq1">
      <option value="all"> Prerequisite 1</option>
      <option value="None"> None</option>

    <?php
     while ($row = mysqli_fetch_array($data)) {
      echo '<option value="' . $row[cid] . '">'. str_replace("_", " ", $row[cid]) . '</option>';
    }
    ?>
    </select>

    <select class="form-control mr-sm-2" name="prereq2">
      <option value="all"> Prerequisite 2</option>
      <option value="None"> None</option>

    <?php
    $query = "SELECT DISTINCT cid FROM courses ORDER BY cid ASC";
    $data = mysqli_query($dbc, $query);
     while ($row = mysqli_fetch_array($data)) {
      echo '<option value="' . $row[cid] . '">'. str_replace("_", " ", $row[cid]) . '</option>';
    }
    ?>
    </select>

    <select class="form-control mr-sm-2" name="faculty">
      <option value="all"> Professor</option>
    <?php
    $query = "SELECT * from faculty, allusers where FID = ID;";
    $data = mysqli_query($dbc, $query);
     while ($row = mysqli_fetch_array($data)) {
         $acctype = $row["acctype"];
         if(strpos($acctype, '7') !== false) {
      echo '<option value="' . $row[FID] . '">'. $row[lname]. '</option>';
         }
    }
    ?>
    </select>


    <button class="btn btn-success" type="submit" name="create">Create</button>
  </form>
</li>
</ul>


</nav>

    <?php
      
      if(isset($_POST["drop_course"]))
    {
        $courseID = $_POST["drop_course"];
        $query = "UPDATE courses SET semester = '0' where CID = '$courseID'";
        mysqli_query($dbc, $query);
        
        
    }
      if(isset($_POST["add_course"]))
    {
        $courseID = $_POST["add_course"];
        $query = "UPDATE courses SET semester = 'Spring 2021' where CID = '$courseID'";
        mysqli_query($dbc, $query);
        
        
    }


      if (isset($_POST['create'])) {
          
          $title = $_POST["title"];
          $department = $_POST["Department"];
          $course_num = $_POST["course_num"];
          $capacity = $_POST["capacity"];
          $day = $_POST["day"];
          $time = $_POST["time"];
          $arr = explode("-", $time);
          $start_time = $arr[0];
          $end_time = $arr[1];
          $credits = $_POST["credits"];
          $pre_req1 = $_POST["prereq1"];
          $pre_req2 = $_POST["prereq2"];
          $faculty = $_POST["faculty"];


          $course_id = $department . "_" . $course_num;
          
          if($pre_req1 == "None") {
              $query = "
INSERT INTO courses VALUES ('$course_id', '$start_time', '$end_time', '$credits', 'Spring 2021', '$title', '$day', NULL, NULL, NULL, NULL, NULL, NULL,  '$course_num', '$department', '$capacity');
";
                       $data = mysqli_query($dbc, $query);
          }

          else if($pre_req2 == "None") {
              $pre_req1_arr = explode("_", $pre_req1);
              $pre_req1_dept = $pre_req1_arr[0];
              $pre_req1_num = $pre_req1_arr[1];
              $pre_req1_id = $pre_req1_dept . "_" . $pre_req1_num; 
              
              $query = "
INSERT INTO courses VALUES ('$course_id', '$start_time', '$end_time', '$credits', 'Spring 2021', '$title', '$day', '$pre_req1_id', NULL, $pre_req1_num, '$pre_req1_dept', NULL, NULL,  '$course_num', '$department', '$capacity');
";
                       $data = mysqli_query($dbc, $query);
          }

          else {
              $pre_req1_arr = explode("_", $pre_req1);
              $pre_req1_dept = $pre_req1_arr[0];
              $pre_req1_num = $pre_req1_arr[1];
              $pre_req1_id = $pre_req1_dept . "_" . $pre_req1_num; 

              
              $pre_req2_arr = explode("_", $pre_req2);
              $pre_req2_dept = $pre_req2_arr[0];
              $pre_req2_num = $pre_req2_arr[1];
              $pre_req2_id = $pre_req2_dept . "_" . $pre_req2_num;

                            $query = "
INSERT INTO courses VALUES ('$course_id', '$start_time', '$end_time', '$credits', 'Spring 2021', '$title', '$day', '$pre_req1_id', '$pre_req2_id', $pre_req1_num, '$pre_req1_dept', $pre_req2_num, '$pre_req2_dept',  '$course_num', '$department', '$capacity');
";
                       $data = mysqli_query($dbc, $query);
              
              
          }
          

          $query = "
INSERT INTO teaches
VALUES('$faculty', 'Spring 2021', '$course_id');
";
           $data = mysqli_query($dbc, $query);
      }
    ?>


<?php
    echo "
<h3 style = 'text-align:left'>Current Courses for Spring 2021</h3>

</div>
<div class = 'row'>

";

$query2 = "select * from courses where courses.semester = 'Spring 2021';";
    $data2 = mysqli_query($dbc, $query2);
    
    while($row2 = mysqli_fetch_array($data2)) {
        
    $title = $row2["title"];
    $CID = $row2["CID"];
      // echo $info;
    $query3 = "select * from takes where courseID = '$CID'";
    $data3 = mysqli_query($dbc, $query3);

    if(mysqli_num_rows($data3) == 0) {



    echo
        "
      <div class = 'card' style = 'width: 18rem;'>
      <div class = 'card-body'>
                       <h5 class = 'card-title'> $title $CID</h5>

                       <p class='card-text'> </p>
<form class='form-inline' method = 'post'>

                       <button type='submit' class='btn btn-danger' name = 'drop_course' value = '$CID'> Remove from current courses</button>

</form>
     </div>
      </div>";
    }
    else {
        echo
        "
      <div class = 'card' style = 'width: 18rem;'>
      <div class = 'card-body'>
                       <h5 class = 'card-title'> $title $CID</h5>

                       <p class='card-text'> You can not remove classes that students are registered for! </p>
<form class='form-inline' method = 'post'>

                       <button type='submit' disabled  class='btn btn-danger' name = 'drop_course' value = '$CID'> Remove from current courses</button>

</form>
     </div>
      </div>";

    }

    
}

echo "</div>";

$query2 = "select * from courses where courses.semester = '0';";
$data2 = mysqli_query($dbc, $query2);
if(mysqli_num_rows($data2) == 0)
{
    echo "<h2> All Current Courses on Bulletin</h2>";
}
else {


    echo "
<h3 style = 'text-align:left'>Current Courses on Bulletin Not offered </h3>

</div>
<div class = 'row'>

";

    while($row2 = mysqli_fetch_array($data2)) {
        
    $title = $row2["title"];
    $CID = $row2["CID"];
      // echo $info;

    
    echo
        "
      <div class = 'card' style = 'width: 18rem;'>
      <div class = 'card-body'>
                       <h5 class = 'card-title'> $title $CID</h5>

                       <p class='card-text'> </p>
<form class='form-inline' method = 'post'>

                       <button type='submit' class='btn btn-success' name = 'add_course' value = '$CID'> Add to Spring 2021</button>

</form>
     </div>
      </div>";

    
}
}
?>

<?php
  require_once('footer.php');
?>

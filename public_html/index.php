<?php
    session_start();
	$current_student = $_SESSION['userid'];
//      require_once('connectvars.php');

	// redirect to login if not signed in
	if(!isset($_SESSION['userid']))
		header("Location: login.php");
?>


<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=yes">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
                                                                                                                                                                                                            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <title>Bronco University</title>
  </head>
  <body>

<div class="p-3 mb-2 bg-primary text-white">
    <h1 style="text-align:center">Bronco University Course Menu</h1>
  </div>

        <div class = "text-right">



                         
<?php
                                                                echo "Hello $current_student";
require_once('navmenu.php');

?>
  
<?php



      $ID = $current_student;
      require_once('connectvars.php');



$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$query = "select * from students where SID = $ID";
$data1 = mysqli_query($dbc, $query);
while($row = mysqli_fetch_array($data1)) {
    $registrationHold = $row["registrationHold"];
                  
}

$query = "select * from takes where studentID = $ID AND grade = 'IP'";
$data1 = mysqli_query($dbc, $query);



echo "
<h3 style = 'text-align:left'>My Courses Spring 2021</h3>

</div>
<div class = 'row'>

";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST["drop_course"]))
    {
        $courseID = $_POST["drop_course"];
        $query = "DELETE FROM takes where courseID = '$courseID' AND studentID = $ID";
        mysqli_query($dbc, $query);
        $query = "select * from courses where CID = '$courseID'";
        echo $query;
        $data = mysqli_query($dbc, $query);

        while(($row = mysqli_fetch_array($data))) {
                $capacity = $row["capacity"];
            }

            $capacity = $capacity + 1;

            $query = "update courses SET capacity = $capacity where CID = '$courseID'";
            mysqli_query($dbc, $query);

            echo $query;

             header("Location: index.php");
        
    }
    


}

if(isset($_POST["add_rtf"])) {
    $course = $_POST["add_rtf"];
    $reason = $_POST["reason"];
    $query = "INSERT INTO rtf VALUES($ID, '$course', 'Spring_2021', '$reason');";
    mysqli_query($dbc, $query);
}

if(isset($_POST["add_course"])) {
        $add_course = $_POST["add_course"];
        //TODO: Change to userID in page
        $arr = explode("_", $add_course);
        $department = $arr[0];
        $num = $arr[1];
        
        $query = "INSERT INTO takes(grade, semester, courseID, studentID, cnum, cdept) VALUES('IP', 'Spring 2021', '$add_course', $current_student, $num, '$department');";
        mysqli_query($dbc, $query);

        
        $query = "select * from courses where CID = '$add_course'";
        $data = mysqli_query($dbc, $query);

        while(($row = mysqli_fetch_array($data))) {
                $capacity = $row["capacity"];
            }

            $capacity = $capacity - 1;

            $query = "update courses SET capacity = $capacity where CID = '$add_course'";
            mysqli_query($dbc, $query);

            echo $query;
            
        
        
             header("Location: index.php");
        
            
    }

while($row = mysqli_fetch_array($data1))
{
    $courseID = $row["courseID"];
    $query2 = "select * from courses, teaches, faculty, allusers where CID = '$courseID' AND FID = facultyID AND FID = ID AND CID = courseID AND courses.semester = teaches.semester AND teaches.semester = 'Spring 2021'";
    $data2 = mysqli_query($dbc, $query2);
    
    while($row2 = mysqli_fetch_array($data2)) {

        
    $title = $row2["title"];
    $CID = $row2["CID"];
    $credits = $row2["credits"];
    $start_time = $row2["start_time"];
    $end_time = $row2["end_time"];
    $teacher_name = $row2["lname"];
    $day = $row2["day"];
    
           

    echo
        "
      <div class = 'card' style = 'width: 18rem;'>
      <div class = 'card-body'>
                       <h5 class = 'card-title'> $title $CID </h5>

                       <p class='card-text'> Credits: $credits <br /> Professor: $teacher_name <br /> $day $start_time-$end_time </p>
<form class='form-inline' method = 'post'>
                       <button type='submit' class='btn btn-danger' name = 'drop_course' value = '$CID'> Drop Course</button>
</form>
      </div>
      </div>";

    
    }
}

echo "</div>";


      ?>

            
  <div class="p-3 mb-2 bg-light text-dark">

<nav class="navbar navbar-light bg-light">
 <ul class="list-group list-group-horizontal">
<li class="list-group-item">
<form class="form-inline" method = "post">
    <input class="form-control mr-sm-2" type="Search" placeholder="Class/Category" name="class_search">
    <button class="btn btn-sm btn-outline-secondary" type="submit" name = "search">Search</button>
  </form>
</li>

</ul>

<?php


require_once('connectvars.php');

      $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);


if($_SERVER["REQUEST_METHOD"] == "POST") {

    

    if(isset($_POST["search"]))
    {
        
        $search = $_POST["class_search"];
        $query = "select * from courses, teaches, faculty, allusers where title = '$search' AND FID = facultyID AND FID = ID AND CID = courseID AND courses.semester = teaches.semester AND teaches.semester = 'Spring 2021'";
        $data = mysqli_query($dbc, $query);
        //echo $query;

        if(mysqli_num_rows($data) == 0)
        {
            $search1 = str_replace(' ', '_', $search);
            $query = "select * from courses, teaches, faculty, allusers where CID = '$search1' AND FID = facultyID AND FID = ID AND CID = courseID AND teaches.semester = courses.semester AND courses.semester = 'Spring 2021'";
            $data = mysqli_query($dbc, $query);
            //            echo $query;
        }
        
        echo"
</nav>
<h3 style = 'text-align:left'>Courses Available Spring 2021</h3>

<div class = 'row'>";
            
            //this means we are only searching for one course i.e CSCI 2212
        if(mysqli_num_rows($data) == 1)
        {
            while($row = mysqli_fetch_array($data))
            {
                $title = $row["title"];
                $CID = $row["CID"];
                $credits = $row["credits"];
                $teacher_name = $row["lname"];
                $start_time = $row["start_time"];
                $end_time = $row["end_time"];
                $day = $row["day"];
                $pre_req_error = false;
                $time_error = false;
                $current_course = false;
                $pre_req1 = $row["prerequisite1Id"];
                $pre_req2 = $row["prerequisite2Id"];
                $capacity = $row["capacity"];
                
                
                //check for registration hold
                if($registrationHold == 0) {
                    echo "
                          <div class = 'card' style = 'width: 18rem;'>
      <div class = 'card-body'>
                       <h5 class = 'card-title'> $title $CID </h5>

                       <p class='card-text'> Credits: $credits <br /> Professor: $teacher_name <br /> Spots Remaining: $capacity <br /> You currently have a registration hold! </p>
                       <button type='submit' class='btn btn-success' name = 'add_course' value='$CID' disabled>Add Course </button>

</form>
      </div>

</div>";
                    continue; 
                    
                }

                              if($capacity <= 0)
                {
                                        echo "
                          <div class = 'card' style = 'width: 18rem;'>
      <div class = 'card-body'>
                       <h5 class = 'card-title'> $title $CID </h5>

                       <p class='card-text'> Credits: $credits <br /> Professor: $teacher_name <br /> Spots Remaining: $capacity <br />There are no more spots available </p>
<form class='form-inline' method = 'post'>

                       <button type='submit' class='btn btn-success' name = 'add_course' value='$CID' disabled> Add Course </button>

                       <button type='submit' class='btn btn-warning' name = 'add_rtf' value='$CID'>Request RTF </button>

</form>
      </div>

</div>";
                                        continue;

                }

                
                //check to see if we are already in the course
                $query_current = "select * from takes where studentID = $current_student AND courseID = '$CID'";
                $data_current = mysqli_query($dbc, $query_current);
                if(mysqli_num_rows($data_current) == 1)
                {
                    echo "
                          <div class = 'card' style = 'width: 18rem;'>
      <div class = 'card-body'>
                       <h5 class = 'card-title'> $title $CID </h5>

                       <p class='card-text'> Credits: $credits <br /> Professor: $teacher_name <br /> Spots Remaining: $capacity <br /> You are currently in/have taken this course! </p>
                       <button type='submit' class='btn btn-success' name = 'add_course' value='$CID' disabled>Add Course </button>

</form>
      </div>

</div>";
                    $current_course = true;

                 

                }
                               

                
                //check to see if there is time conflicts
                $query_time = "select * from takes, courses where studentID = $current_student AND grade = 'IP' AND CID = courseID AND takes.semester = courses.semester AND courses.semester = 'Spring 2021'";
                $data_time = mysqli_query($dbc, $query_time);

                while($row2 = mysqli_fetch_array($data_time))
                {
                    $start_time2 = $row2["start_time"];
                    $end_time2 = $row2["end_time"];
                    $day2 = $row2["day"];
                    $CID1 = $row2["CID"];


                    if($day2 == $day && !$current_course)
                    {
                        $diff = abs($start_time2 - $start_time);
                        if(abs($start_time2 - $start_time) < 271) 
                        {
                                                echo "
                          <div class = 'card' style = 'width: 18rem;'>
      <div class = 'card-body'>
                       <h5 class = 'card-title'> $title $CID </h5>

                       <p class='card-text'> Credits: $credits <br /> Professor: $teacher_name 
<br /> $day $start_time-$end_time
<br /> You have a time conflict $CID1 $day2 $start_time-$end_time</p>
                       <button type='submit' class='btn btn-success' name = 'add_course' value='$CID' disabled>Add Course </button>

</form>
      </div>

</div>";

                            $time_error = true;
                        }
                    }
                
                }
                
                
                
                
                if($pre_req1 != NULL && !$time_error && !$current_course){
                
                    $query1 = "select * from takes where studentID = $current_student AND courseID = '$pre_req1' AND grade != 'IP' AND grade != 'F'";

                    $data1 = mysqli_query($dbc, $query1);



                    if(mysqli_num_rows($data1) == 0)
                    {
                        $pre_req_error = true;
                        echo "


      <div class = 'card' style = 'width: 18rem;'>
      <div class = 'card-body'>
                       <h5 class = 'card-title'> $title $CID </h5>

                       <p class='card-text'> Credits: $credits <br /> Professor: $teacher_name <br /> Spots Remaining: $capacity 
<br /> $day $start_time-$end_time
<br /> You do not meet pre req ($pre_req1) </p>
                       <button type='submit' class='btn btn-success' name = 'add_course' value='$CID' disabled>Add Course </button>




</form>
      </div>

</div>";

                    }
                }

                if($pre_req2 != NULL && !$time_error && !$current_course && !$pre_req_error)
                {

                    $query2 = "select * from takes where studentID = $current_student AND courseID = '$pre_req2' AND grade != 'IP' AND grade != 'F'";


                    $data2 = mysqli_query($dbc, $query2);

                    if(mysqli_num_rows($data2) == 0)
                    {
                        $pre_req_error = true;
                        echo "


      <div class = 'card' style = 'width: 18rem;'>
      <div class = 'card-body'>
                       <h5 class = 'card-title'> $title $CID </h5>

                       <p class='card-text'> Credits: $credits <br /> Professor: $teacher_name <br /> Spots Remaining: $capacity 



<br /> $day $start_time-$end_time
<br /> You do not meet pre req ($pre_req2) </p>
                       <button type='submit' class='btn btn-success' name = 'add_course' value='$CID' disabled>Add Course </button>
</div>

</div>";

                    }
                }

                
                if(($pre_req_error == false) && ($time_error == false) && $current_course == false)
                {
                echo "


      <div class = 'card' style = 'width: 18rem;'>
      <div class = 'card-body'>
                       <h5 class = 'card-title'> $title $CID </h5>

                       <p class='card-text'> Credits: $credits <br /> Professor: $teacher_name <br /> Spots Remaining: $capacity <br /> You meet all pre reqs!
<br /> $day $start_time-$end_time </p>

<form class='form-inline' method = 'post'>

                       <button type='submit' class='btn btn-success' name = 'add_course' value='$CID'>Add Course </button>

</form>
      </div>

</div>";

                }


            
        echo "</div>";
    }
        }
        else
        {
            $query = "select * from courses, teaches, faculty, allusers where CID LIKE '$search%' AND courseID = CID AND FID = facultyID AND FID = ID  AND courses.semester = teaches.semester AND teaches.semester = 'Spring 2021' order by CID";




        $data = mysqli_query($dbc, $query);

            while($row = mysqli_fetch_array($data))
            {
                $title = $row["title"];
                $CID = $row["CID"];
                $credits = $row["credits"];
                $teacher_name = $row["lname"];
                $start_time = $row["start_time"];
                $end_time = $row["end_time"];
                $day = $row["day"];
                $pre_req_error = false;
                $time_error = false;
                $current_course = false;
                $pre_req1 = $row["prerequisite1Id"];
                $pre_req2 = $row["prerequisite2Id"];
                $capacity = $row["capacity"];

                      //check for registration hold
                if($registrationHold == 0) {
                    echo "
                          <div class = 'card' style = 'width: 18rem;'>
      <div class = 'card-body'>
                       <h5 class = 'card-title'> $title $CID </h5>

                       <p class='card-text'> Credits: $credits <br /> Professor: $teacher_name <br /> Spots Remaining: $capacity <br /> You currently have a registration hold! </p>
                       <button type='submit' class='btn btn-success' name = 'add_course' value='$CID' disabled>Add Course </button>

</form>
      </div>

</div>";
                    continue; 
                    
                }

                //check to see if we are already in the course
                $query_current = "select * from takes where studentID = $current_student AND courseID = '$CID'";
                $data_current = mysqli_query($dbc, $query_current);
                if(mysqli_num_rows($data_current) == 1)
                {
                    echo "
                          <div class = 'card' style = 'width: 18rem;'>
      <div class = 'card-body'>
                       <h5 class = 'card-title'> $title $CID </h5>

                       <p class='card-text'> Credits: $credits <br /> Professor: $teacher_name <br /> Spots Remaining: $capacity <br /> You are currently in/have taken this course! </p>
                       <button type='submit' class='btn btn-success' name = 'add_course' value='$CID' disabled>Add Course </button>

</form>
      </div>

</div>";
                    $current_course = true;
                    continue;

                 

                }
                                  if($capacity <= 0)
                {
                                        echo "
                          <div class = 'card' style = 'width: 18rem;'>
      <div class = 'card-body'>
                       <h5 class = 'card-title'> $title $CID </h5>

                       <p class='card-text'> Credits: $credits <br /> Professor: $teacher_name <br /> Spots Remaining: $capacity <br />There are no more spots available </p>
                       <button type='submit' class='btn btn-success' name = 'add_course' value='$CID' disabled>Add Course </button>

<button type='button' class='btn btn-warning' data-toggle='modal' data-target='#$CID'>Request RTF</button>

<div id='$CID' class='modal fade' role='dialog'>
  <div class='modal-dialog'>

    <!-- Modal content-->
    <div class='modal-content'>
      <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal'>&times;</button>
        <h4 class='modal-title'>RTF Request for $CID</h4>
      </div>
      <div class='modal-body'>
<form class='form-inline' method = 'post'>
<label for='reason'>What is the reason for your RTF Request</label>
    <input type='text' id='reason' name='reason' size='32' /><br />

                        <button type='submit' class='btn btn-warning' name = 'add_rtf' value='$CID'>Request RTF </button>
      </div>
</form>

</div>
  </div>
</div>

      </div>

</div>";
                                        continue;

                }

                               

                
                //check to see if there is time conflicts
                $query_time = "select * from takes, courses where studentID = $current_student AND grade = 'IP' AND CID = courseID AND takes.semester = courses.semester AND takes.semester = 'Spring 2021'";
                $data_time = mysqli_query($dbc, $query_time);

                while($row2 = mysqli_fetch_array($data_time))
                {
                    $start_time2 = $row2["start_time"];
                    $end_time2 = $row2["end_time"];
                    $day2 = $row2["day"];
                    $CID1 = $row2["CID"];


                    if($day2 == $day && !$current_course)
                    {
                        $diff = abs($start_time2 - $start_time);
                        if(abs($start_time2 - $start_time) < 271) 
                        {
                                                echo "
                          <div class = 'card' style = 'width: 18rem;'>
      <div class = 'card-body'>
                       <h5 class = 'card-title'> $title $CID </h5>


                       <p class='card-text'> Credits: $credits <br /> Professor: $teacher_name <br /> Spots Remaining: $capacity <br />$day $start_time-$end_time <br />You have a time conflict $CID1 $day2 $start_time-$end_time</p>
                       <button type='submit' class='btn btn-success' name = 'add_course' value='$CID' disabled>Add Course </button>

</form>
      </div>

</div>";

                            $time_error = true;
                        }
                    }
                
                }
                
                
                
                
                if($pre_req1 != NULL && !$time_error && !$current_course)
                {
                
                    $query1 = "select * from takes where studentID = $current_student AND courseID = '$pre_req1' AND grade != 'IP' AND grade != 'F'";

                    $data1 = mysqli_query($dbc, $query1);



                    if(mysqli_num_rows($data1) == 0)
                    {
                        $pre_req_error = true;
                        echo "


      <div class = 'card' style = 'width: 18rem;'>
      <div class = 'card-body'>
                       <h5 class = 'card-title'> $title $CID </h5>

                       <p class='card-text'> Credits: $credits <br /> Professor: $teacher_name <br /> Spots Remaining: $capacity 
<br />$day $start_time-$end_time
<br /> You do not meet pre req ($pre_req1) </p>
                       <button type='submit' class='btn btn-success' name = 'add_course' value='$CID' disabled>Add Course </button>
<button type='button' class='btn btn-warning' data-toggle='modal' data-target='#$CID'>Request RTF</button>

<div id='$CID' class='modal fade' role='dialog'>
  <div class='modal-dialog'>

    <!-- Modal content-->
    <div class='modal-content'>
      <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal'>&times;</button>
        <h4 class='modal-title'>RTF Request for $title</h4>
      </div>
      <div class='modal-body'>
<form class='form-inline' method = 'post'>
<label for='reason'>What is the reason for your RTF Request</label>
    <input type='text' id='reason' name='reason' size='32' /><br />

                        <button type='submit' class='btn btn-warning' name = 'add_rtf' value='$CID'>Request RTF </button>
      </div>
</form>

</div>
  </div>
</div>

      </div>

</div>";

                    }
                }

                if($pre_req2 != NULL && !$time_error && !$current_course && !$pre_req_error)
                {

                    $query2 = "select * from takes where studentID = $current_student AND courseID = '$pre_req2' AND grade != 'IP' AND grade != 'F'";


                    $data2 = mysqli_query($dbc, $query2);

                    if(mysqli_num_rows($data2) == 0)
                    {
                        $pre_req_error = true;
                        echo "


      <div class = 'card' style = 'width: 18rem;'>
      <div class = 'card-body'>
                       <h5 class = 'card-title'> $title $CID </h5>

                       <p class='card-text'> Credits: $credits <br /> Professor: $teacher_name <br /> Spots Remaining: $capacity 



<br />$day $start_time-$end_time
<br /> You do not meet pre req ($pre_req2) </p>
                       <button type='submit' class='btn btn-success' name = 'add_course' value='$CID' disabled>Add Course </button>
</div>

</div>";

                    }
                }

                
                if(($pre_req_error == false) && ($time_error == false) && $current_course == false)
                {
                echo "


      <div class = 'card' style = 'width: 18rem;'>
      <div class = 'card-body'>
                       <h5 class = 'card-title'> $title $CID </h5>

                       <p class='card-text'> Credits: $credits <br /> Professor: $teacher_name <br /> Spots Remaining: $capacity <br /> You meet all pre reqs!
<br /> $day $start_time-$end_time </p>

<form class='form-inline' method = 'post'>

                       <button type='submit' class='btn btn-success' name = 'add_course' value='$CID'>Add Course </button>

</form>
      </div>

</div>";

                }

        }
            
        }
        echo "</div>";
    }
}
    

    ?>


<ul class="list-group list-group-horizontal">



    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  </body>
</html>

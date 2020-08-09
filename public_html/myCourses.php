<?php
    session_start();
    if(!isset($_SESSION["userid"])) {
    	header("Location: login.php");
    }
$current_student = $_SESSION['userid'];

?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=yes">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title>Bronco University</title>
  </head>
  <body>

<div class="p-3 mb-2 bg-primary text-white">
    <h1 style="text-align:center">Bronco University My Courses</h1>
  </div>

        <div class = "text-right">
<button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = 'index.php';">Course Menu </button>
<button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = 'transcript.php';">View Transcripts</button>
    <button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = 'logout.php';">Logout</button>

        </div>

  <div class="p-3 mb-2 bg-light text-dark">

<nav class="navbar navbar-light bg-light">
 <ul class="list-group list-group-horizontal">
  </div>
</div>
</li>
</ul>


</nav>
      
<?php

      $ID = $current_student;
      require_once('connectvars.php');

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$query = "select * from takes where studentID = $ID AND grade = 'IP'";
$data1 = mysqli_query($dbc, $query);


      echo "
<h3 style = 'text-align:left'>My Courses Spring 2020</h3>

</div>
<div class = 'row'>

";

while($row = mysqli_fetch_array($data1))
{
    $courseID = $row["courseID"];
    $query2 = "select * from courses, teaches, faculty where CID = '$courseID' AND courseID = CID AND FID = facultyID";
    $data2 = mysqli_query($dbc, $query2);
    
    while($row2 = mysqli_fetch_array($data2)) {

        
    $title = $row2["title"];
    $CID = $row2["CID"];
    $credits = $row2["credits"];
    $start_time = $row2["start_time"];
    $end_time = $row2["end_time"];
    $teacher_name = $row2["name"];
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

<?php
if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST["drop_course"]))
    {
        $courseID = $_POST["drop_course"];
        $query = "DELETE FROM takes where courseID = '$courseID' AND studentID = $ID";
        mysqli_query($dbc, $query);
        header("Location: myCourses.php");
        
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

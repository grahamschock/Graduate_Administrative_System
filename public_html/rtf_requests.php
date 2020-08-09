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



                        
    <div class = "text-right">
<button type = "button" class = "btn btn-primary btn-lg" onclick = "window.location.href = 'add_courses.php'"> Create Course </button>
                                                                                                                          <button type = "button" class = "btn btn-primary btn-lg" onclick = "window.location.href = 'logout.php'"> Logout </button>

                                                                                                                        </div>

<?php



      $ID = $current_student;
      require_once('connectvars.php');



$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);




echo "
<h3 style = 'text-align:left'>Current RTF Requests Spring 2021</h3>

</div>
<div class = 'row'>

";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST["drop_course"]))
    {
        $courseID = $_POST["drop_course"];
        $arr = explode("-", $courseID);
        $CID = $arr[0];
        $ID = $arr[1];
        echo $ID;
        
        $query = "DELETE FROM rtf where cid = '$CID' AND sid = $ID";
        mysqli_query($dbc, $query);
        
    }
    


}

if(isset($_POST["add_course"])) {
        $add_course = $_POST["add_course"];
        //TODO: Change to userID in page
        $arr = explode("-", $add_course);
        $CID = $arr[0];
        $ID = $arr[1];

        $arr = explode("_", $CID);
        $department = $arr[0];
        $num = $arr[1];

        $query = "DELETE FROM rtf where cid = '$CID' AND sid = $ID";
        mysqli_query($dbc, $query);
        
        $query = "INSERT INTO takes(grade, semester, courseID, studentID, cnum, cdept) VALUES('IP', 'Spring 2021', '$CID', $ID, $num, '$department');";
        mysqli_query($dbc, $query);

        
        $query = "select * from courses where CID = '$CID'";
        $data = mysqli_query($dbc, $query);

        while(($row = mysqli_fetch_array($data))) {
                $capacity = $row["capacity"];
            }

            $capacity = $capacity - 1;

            $query = "update courses SET capacity = $capacity where CID = '$CID'";
            mysqli_query($dbc, $query);

            echo $query;
            
        
        
            //             header("Location: index.php");
        
            
    }



$query2 = " select * from rtf, courses, allusers where rtf.cid = courses.cid AND courses.semester = 'Spring 2021' AND rtf.sid = allusers.id;";
    $data2 = mysqli_query($dbc, $query2);
    
    while($row2 = mysqli_fetch_array($data2)) {
        
    $title = $row2["title"];
    $CID = $row2["CID"];
    $ID = $row2["sid"];
    $fname = $row2["fname"];
    $lname = $row2["lname"];
    $reason = $row2["reason"];

    $info = $CID . '-' . $ID;
    // echo $info;
           

    echo
        "
      <div class = 'card' style = 'width: 18rem;'>
      <div class = 'card-body'>
                       <h5 class = 'card-title'> $title $CID $ID</h5>

                       <p class='card-text'>Name: $lname, $fname </br> Reason: $reason </p>
<form class='form-inline' method = 'post'>

                       <button type='submit' class='btn btn-success' name = 'add_course' value = '$info'> Accept </button>

                       <button type='submit' class='btn btn-danger' name = 'drop_course' value = '$info'> Deny </button>
</form>
      </div>
      </div>";

    
}

echo "</div>";


      ?>



<ul class="list-group list-group-horizontal">



    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  </body>
</html>


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


require_once('connectvars.php');

      $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);


    ?>

<?php

$query = "select * from takes, courses where studentID = $current_student AND grade = 'IP' AND courseID = CID AND courses.semester = 'Spring 2021' ORDER BY start_time, courses.day";
$data1 = mysqli_query($dbc, $query);

$monday = false;
$tuesday = false;
$times = array();
$days = array();
$endtime = array();
$colors = array("bg-primary", "bg-success", "bg-warning", "bg-danger", "bg-info");
$class_colors = array();
$x = 0;
while($row = mysqli_fetch_array($data1))
{
    $times[$row["CID"]] = $row["start_time"];
    $days[$row["CID"]] = $row["day"];
    $endtime[$row["CID"]] = $row["end_time"];
    $class_colors[$row["CID"]] = $colors[$x];
    $x++;
    
}

print_r($days);

echo '<div class = "table-responsive-lg"> <table class="table table-bordered">
  <thead>
    <tr>
      <th scope="col">Time</th>
      <th scope="col">Monday</th>
      <th scope="col">Tuesday</th>
      <th scope="col">Wednesday</th>
<th scope = "col"> Thursday</th>
    </tr>
  </thead>
  <tbody>';
 
for($i = 1500; $i <= 2000; $i = $i+100) {
    echo "<tr>
<th scope = 'row'> $i </th>";
    foreach($times as $key => $value) {
        if($value == $i || ($value + 200 >= $i && $value <= $i )) {
            if($days[$key] == 'Z') {
                $color = $class_colors[$key];
                if($monday == true && $tuesday == false && $wednesday == false)
                {
                    echo '<td> </td>';
                    echo '<td> </td>';
                }
                if($tuesday == true && $wednesday == false)
                {
                    echo '<td> </td>';
                }
                if($monday == false && $tuesday == false && $wednesday == false) {
                    echo '<td> </td>';
                    echo '<td> </td>';
                    echo '<td> </td>';
                }
                 if(($value + 200 >= $i && $value <= $i ) && !($value == $i)) {
                    
                    echo "<td class = '$color'> </td>";
                    break; 
                }
                 $start_time = $times[$key];
                $end_time = $endtime[$key];
                echo "<td class = '$color'> $key $start_time - $end_time </td>";
                               
 
            }
            if($days[$key] == 'W') {
                $color = $class_colors[$key];
                if($monday == true && $tuesday == false) {
                    echo '<td> </td>';

                }
                if($monday == false && $tuesday == false) {
                    echo '<td> </td>';
                    echo '<td> </td>';
                
                }
                if(($value + 200 >= $i && $value <= $i ) && !($value == $i)) {
                    
                    echo "<td class = '$color'> </td>";
                    continue; 
                }
                $start_time = $times[$key];
                $end_time = $endtime[$key];
                echo "<td class = '$color'> $key $start_time - $end_time </td>";
                $wednesday = true;
                continue; 
            }
            if($days[$key] == 'T') {
                $color = $class_colors[$key];
                if($monday == false) {
                    echo '<td> </td>';
                }

                if(($value + 200 >= $i && $value <= $i ) && !($value == $i)) {
                    echo "<td class = '$color'> </td>";
                    $tuesday = true;
                    continue; 
                }

                $start_time = $times[$key];
                $end_time = $endtime[$key];

                

                echo "<td class = '$color'> $key $start_time - $end_time </td>";
                $tuesday = true; 
                continue;
            }

            if($days[$key] == 'M') {
                $color = $class_colors[$key];
                $start_time = $times[$key];
                $end_time = $endtime[$key];
                
                if(($value + 200 >= $i && $value <= $i ) && !($value == $i)) {
                    echo "<td class = '$color'> </td>";
                    $monday = true;
                    continue; 
                }


                echo "<td class = '$color'> $key $start_time - $end_time</td>";
                $monday = true;
                continue;
            }

        }
    }
    echo '</tr>';
    $monday = false;
    $tuesday = false;
    $wednesday = false;


    $i = $i + 30;
    echo "<tr>
<th scope = 'row'> $i </th>";
    foreach($times as $key => $value) {
        if($value == $i || ($value + 200 >= $i && $value <= $i )) {
           if($days[$key] == 'Z') {
                $color = $class_colors[$key];
                if($monday == true && $tuesday == false && $wednesday == false)
                {
                    echo '<td> </td>';
                    echo '<td> </td>';
                }
                if($monday == true && $tuesday == true && $wednesday == false)
                {
                    echo '<td> </td>';
                }
                if($monday == false && $tuesday == false && $wednesday == false) {
                    echo '<td> </td>';
                    echo '<td> </td>';
                    echo '<td> </td>';
                }
                 echo "<td class = '$color'> </td>";
                break;
                        }
            if($days[$key] == 'W') {
                $color = $class_colors[$key];
                if($monday == true && $tuesday == false) {
                    echo '<td> </td>';

                }
                if($monday == false && $tuesday == false) {
                    echo '<td> </td>';
                    echo '<td> </td>';
                
                }
                echo "<td class = '$color'> </td>";
                $wednesday = true; 
                continue;
            }
            if($days[$key] == 'T') {
                $color = $class_colors[$key];
                if($monday == false) {
                    echo '<td> </td>';
                }
                echo "<td class = '$color'> </td>";
                $tuesday = true; 
                continue;
            }

            if($days[$key] == 'M') {
                $color = $class_colors[$key];
                echo "<td class = '$color'> </td>";
                $monday = true;
                continue;
            }

        }
    }
    echo '</tr>';
    $monday = false;
    $tuesday = false;
    $wednesday = false; 

    $i = $i - 30; 

    
}
echo '</div>';
      ?>

<ul class="list-group list-group-horizontal">



    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  </body>
</html>

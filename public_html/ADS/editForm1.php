<?php
    $page_title = "Edit Form 1";
    require_once('../header.php');
    require_once('connectvars.php');
    require_once('appvars.php');
    session_start();
    
    $user_id = $_SESSION['userid'];

    echo '<form id="autofillM" method="post" action="' . $_SERVER['PHP_SELF'] . '">';
    echo '<input type="hidden" id="M" name="M" value="1">';
    echo '</form>';

    echo '<form id="autofillD" method="post" action="' . $_SERVER['PHP_SELF'] . '">';
    echo '<input type="hidden" id="D" name="D" value="1">';
    echo '</form>';

    echo '<button type="submit" form="autofillM" class="btn btn-primary btn-lg">Autofill Masters</button>';
    echo '<button type="submit" form="autofillD" class="btn btn-primary btn-lg">Autofill PhD</button>';
    echo '<div align="right"><button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = \'form1.php\';">Back</button></div>';

    // Reset all department and number variables
    $dep1 = "";
    $dep2 = "";
    $dep3 = "";
    $dep4 = "";
    $dep5 = "";
    $dep6 = "";
    $dep7 = "";
    $dep8 = ""; 
    $dep9 = "";
    $dep10 = "";
    $dep11 = "";
    $dep12 = "";

    $num1 = ""; 
    $num2 = "";
    $num3 = "";
    $num4 = "";
    $num5 = "";
    $num6 = "";
    $num7 = "";
    $num8 = "";
    $num9 = "";
    $num10 = "";
    $num11 = "";
    $num12 = "";
    
    // Populate for masters
    if(isset($_POST['M'])){
	if($_POST['M'] != 0){
            $dep1 = "CSCI";
            $dep2 = "CSCI";
            $dep3 = "CSCI";
            $dep4 = "CSCI";
            $dep5 = "CSCI";
            $dep6 = "CSCI";
            $dep7 = "CSCI";
            $dep8 = "CSCI";
            $dep9 = "CSCI";
            $dep10 = "CSCI";

	    $num1 = 6221;
	    $num2 = 6232;
	    $num3 = 6233;
	    $num4 = 6241;
	    $num5 = 6242;
	    $num6 = 6283;
	    $num7 = 6284;
	    $num8 = 6286;
	    $num9 = 6339;
	    $num10 = 6384;
	    
	    $_POST['M'] = 0;
	}
    }
    
    // Populate for PhD
    if(isset($_POST['D'])){
	if($_POST['D'] != 0){
            $dep1 = "CSCI";
            $dep2 = "CSCI";
            $dep3 = "CSCI";
            $dep4 = "CSCI";
            $dep5 = "CSCI";
            $dep6 = "CSCI";
            $dep7 = "CSCI";
            $dep8 = "CSCI";
            $dep9 = "CSCI";
            $dep10 = "CSCI";
            $dep11 = "CSCI";
	    $dep12 = "ECE";

	    $num1 = 6221;
	    $num2 = 6212;
	    $num3 = 6461;
	    $num4 = 6232;
	    $num5 = 6241;
	    $num6 = 6283;
	    $num7 = 6233;
	    $num8 = 6246;
	    $num9 = 6262;
	    $num10 = 6242;
	    $num11 = 6339;
	    $num12 = 6241;
	    
	    $_POST['D'] = 0;
	}
    }
?>


<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title>Edit Form1</title>
  </head>
  <body>
  <?php
    if(empty($_GET['err'])){
      
    }else{
      $errmsg = $_GET['err'];
      echo "$errmsg";

    }
  ?>

    <div class="container">
  <div class="row">
    <div class="col-sm">
    <div class="col-md">

       <strong style="color: red;"> <?php echo $_SESSION['errmsg']; ?></strong><br>

      <form method = "post" action = "updateform1.php" >
      Course 1: <strong style="color: red;"> <?php echo $_SESSION['error1']; ?></strong><br>
        <div class="form-row">
          <div class="col">
	    <input type="text" class="form-control" placeholder="Department" name = "Dept1" value="<?php if (!empty($_SESSION['dep1'])) echo $_SESSION['dep1']; ?><?php if (!empty($dep1)) echo $dep1; ?>">
          </div>
          <div class="col">
            <input type="text" class="form-control" placeholder="Course Number" name = "Num1" value="<?php if (!empty($_SESSION['num1'])) echo $_SESSION['num1']; ?><?php if (!empty($num1)) echo $num1; ?>">
          </div>
        </div>
	Course 2: <strong style="color: red;"><?php echo $_SESSION['error2']; ?></strong><br>
        <div class="form-row">
          <div class="col">
            <input type="text" class="form-control" placeholder="Department" name = "Dept2" value="<?php if (!empty($_SESSION['dep2'])) echo $_SESSION['dep2']; ?><?php if (!empty($dep2)) echo $dep2; ?>">
          </div>
          <div class="col">
            <input type="text" class="form-control" placeholder="Course Number" name = "Num2" value="<?php if (!empty($_SESSION['num2'])) echo $_SESSION['num2']; ?><?php if (!empty($num2)) echo $num2; ?>">
          </div>
        </div>
	Course 3: <strong style="color: red;"><?php echo $_SESSION['error3']; ?></strong><br>
        <div class="form-row">
          <div class="col">
            <input type="text" class="form-control" placeholder="Department" name = "Dept3" value="<?php if (!empty($_SESSION['dep3'])) echo $_SESSION['dep3']; ?><?php if (!empty($dep3)) echo $dep3; ?>">
          </div>
          <div class="col">
            <input type="text" class="form-control" placeholder="Course Number" name = "Num3" value="<?php if (!empty($_SESSION['num3'])) echo $_SESSION['num3']; ?><?php if (!empty($num3)) echo $num3; ?>">
          </div>
        </div>
	Course 4: <strong style="color: red;"><?php echo $_SESSION['error4']; ?></strong><br>
        <div class="form-row">
          <div class="col">
            <input type="text" class="form-control" placeholder="Department" name = "Dept4" value="<?php if (!empty($_SESSION['dep4'])) echo $_SESSION['dep4']; ?><?php if (!empty($dep4)) echo $dep4; ?>">
          </div>
          <div class="col">
            <input type="text" class="form-control" placeholder="Course Number" name = "Num4" value="<?php if (!empty($_SESSION['num4'])) echo $_SESSION['num4']; ?><?php if (!empty($num4)) echo $num4; ?>">
          </div>
        </div>
	Course 5: <strong style="color: red;"><?php echo $_SESSION['error5']; ?></strong><br>
        <div class="form-row">
          <div class="col">
            <input type="text" class="form-control" placeholder="Department" name = "Dept5" value="<?php if (!empty($_SESSION['dep5'])) echo $_SESSION['dep5']; ?><?php if (!empty($dep5)) echo $dep5; ?>">
          </div>
          <div class="col">
            <input type="text" class="form-control" placeholder="Course Number" name = "Num5" value="<?php if (!empty($_SESSION['num5'])) echo $_SESSION['num5']; ?><?php if (!empty($num5)) echo $num5; ?>">
          </div>
        </div>
	Course 6: <strong style="color: red;"><?php echo $_SESSION['error6']; ?></strong><br>
        <div class="form-row">
          <div class="col">
            <input type="text" class="form-control" placeholder="Department" name = "Dept6" value="<?php if (!empty($_SESSION['dep6'])) echo $_SESSION['dep6']; ?><?php if (!empty($dep6)) echo $dep6; ?>">
          </div>
          <div class="col">
            <input type="text" class="form-control" placeholder="Course Number" name = "Num6" value="<?php if (!empty($_SESSION['num6'])) echo $_SESSION['num6']; ?><?php if (!empty($num6)) echo $num6; ?>">
          </div>
        </div>
	Course 7: <strong style="color: red;"><?php echo $_SESSION['error7']; ?></strong><br>
        <div class="form-row">
          <div class="col">
            <input type="text" class="form-control" placeholder="Department" name = "Dept7" value="<?php if (!empty($_SESSION['dep7'])) echo $_SESSION['dep7']; ?><?php if (!empty($dep7)) echo $dep7; ?>">
          </div>
          <div class="col">
            <input type="text" class="form-control" placeholder="Course Number" name = "Num7" value="<?php if (!empty($_SESSION['num7'])) echo $_SESSION['num7']; ?><?php if (!empty($num7)) echo $num7; ?>">
          </div>
        </div>
	Course 8: <strong style="color: red;"><?php echo $_SESSION['error8']; ?></strong><br>
        <div class="form-row">
          <div class="col">
            <input type="text" class="form-control" placeholder="Department" name = "Dept8" value="<?php if (!empty($_SESSION['dep8'])) echo $_SESSION['dep8']; ?><?php if (!empty($dep8)) echo $dep8; ?>">
          </div>
          <div class="col">
            <input type="text" class="form-control" placeholder="Course Number" name = "Num8" value="<?php if (!empty($_SESSION['num8'])) echo $_SESSION['num8']; ?><?php if (!empty($num8)) echo $num8; ?>">
          </div>
        </div>
	Course 9: <strong style="color: red;"><?php echo $_SESSION['error9']; ?></strong><br>
        <div class="form-row">
          <div class="col">
            <input type="text" class="form-control" placeholder="Department" name = "Dept9" value="<?php if (!empty($_SESSION['dep9'])) echo $_SESSION['dep9']; ?><?php if (!empty($dep9)) echo $dep9; ?>">
          </div>
          <div class="col">
            <input type="text" class="form-control" placeholder="Course Number" name = "Num9" value="<?php if (!empty($_SESSION['num9'])) echo $_SESSION['num9']; ?><?php if (!empty($num9)) echo $num9; ?>">
          </div>
        </div>
	Course 10: <strong style="color: red;"><?php echo $_SESSION['error10']; ?></strong><br>
        <div class="form-row">
          <div class="col">
            <input type="text" class="form-control" placeholder="Department" name = "Dept10" value="<?php if (!empty($_SESSION['dep10'])) echo $_SESSION['dep10']; ?><?php if (!empty($dep10)) echo $dep10; ?>">
          </div>
          <div class="col">
            <input type="text" class="form-control" placeholder="Course Number" name = "Num10" value="<?php if (!empty($_SESSION['num10'])) echo $_SESSION['num10']; ?><?php if (!empty($num10)) echo $num10; ?>">
          </div>
        </div>
	Course 11: <strong style="color: red;"><?php echo $_SESSION['error11']; ?></strong><br>
        <div class="form-row">
          <div class="col">
            <input type="text" class="form-control" placeholder="Department" name = "Dept11" value="<?php if (!empty($_SESSION['dep11'])) echo $_SESSION['dep11']; ?><?php if (!empty($dep11)) echo $dep11; ?>">
          </div>
          <div class="col">
            <input type="text" class="form-control" placeholder="Course Number" name = "Num11" value="<?php if (!empty($_SESSION['num11'])) echo $_SESSION['num11']; ?><?php if (!empty($num11)) echo $num11; ?>">
          </div>
        </div>
	Course 12: <strong style="color: red;"><?php echo $_SESSION['error12']; ?></strong><br>
        <div class="form-row">
          <div class="col">
            <input type="text" class="form-control" placeholder="Department" name = "Dept12" value="<?php if (!empty($_SESSION['dep12'])) echo $_SESSION['dep12']; ?><?php if (!empty($dep12)) echo $dep12; ?>">
          </div>
          <div class="col">
            <input type="text" class="form-control" placeholder="Course Number" name = "Num12" value="<?php if (!empty($_SESSION['num12'])) echo $_SESSION['num12']; ?><?php if (!empty($num12)) echo $num12; ?>">
          </div>
          </div>
        <br>
	<button type="submit" class="btn btn-primary">Submit Form1</button>
      </form>
    </div>
    <div class="col-sm">
    </div>
  </div>
    </div>

    <form>
<?php
    // Reset session variables
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
?>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  </body>
</html>

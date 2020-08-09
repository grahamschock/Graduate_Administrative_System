<?php
session_start();
$page_title = "Edit Personal Info";
require_once('../header.php');
require_once('../connectvars.php');

$username = $_SESSION['username'];

$query = "SELECT * FROM applicant WHERE username='$username';";

$db = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$data = mysqli_query($db, $query);
$row = mysqli_fetch_array($data);
$uid = $row['uid'];
$fname = $row['fname'];
$lname = $row['lname'];
$email = $row['email'];
$dobirth = $row['dobirth'];
$ssn = $row['ssn'];
$address = $row['address'];
$fnameErr = $lnameErr = $emailErr = $dobErr = $ssnErr = $addressErr = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // STUDENT INFO
  // get student name
  $fname = test_input($_POST['fname']);
  if (strlen($fname) > 32) {
    $fnameErr .= '<p style="color:red;">INVALID length</p>';
  }

  $lname = test_input($_POST['lname']);
  if (strlen($lname) > 32) {
    $lnameErr .= '<p style="color:red;">INVALID length</p>';
  }

  $email = $_POST['email'];
  if (!empty($email)) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailErr .= '<p style="color:red;"> Invalid format and please re-enter valid email </p>';
    }

    if (strlen($email) > 100) {
      $emailErr .= '<p style="color:red;"> Invalid email length </p>';
    }
  }

  // (FORMAT: DATE YYYY-MM-DD)
  // get the date of birth
  if (!empty($_POST['dobirth'])) {
    $dobirth = $_POST['dobirth'];

    // get substring is from date
    $M = (int) substr($dobirth, 5, 6);
    $D = (int) substr($dobirth, 8, 9);
    $Y = (int) substr($dobirth, 0, 4);

    if (checkdate($M, $D, $Y) == false || strlen($dobirth) > 10) {
      $dobErr .= '<p style="color:red;">INVALID date format</p>';
    }
  }

  // get the SSN
  if (!empty($_POST['ssn'])) {
    $ssn = $_POST['ssn'];

    // check if the length and data type is correct
    if (!is_numeric($ssn) || strlen($ssn) != 9) {
      //$ssn = "";
      $ssnErr .= '<p style="color:red;">INVALID input</p>';
    } else {
      // check if the SSN already exist on the databsae
      $query = "SELECT ssn FROM applicant WHERE ssn='$ssn' AND username <> '$username'";
      $result = mysqli_query($db, $query);
      $row = mysqli_fetch_assoc($result);
      if (strcasecmp($row['ssn'], $ssn) == 0) {
        //$ssn = "";
        $ssnErr .= '<p style="color:red;"> Please enter a valid SSN </p>';
      }
    }
  }

  // get address
  $address = $_POST['address'];
  // get if the address is formated correctly
  if (!empty($address) && !preg_match('/\d+ [0-9a-zA-Z ]+/', $address)) {
    $addressErr .= '<p style="color:red;">INVALID address format </p>';
  }

  if (strlen($address) > 100) {
    $addressErr .= '<p style="color:red;">INVALID address length </p>';
  }
  if (empty($fnameErr) && empty($lnameErr) && empty($emailErr) && empty($dobErr) && empty($ssnErr) && empty($addressErr)) {
    $query = "UPDATE applicant SET fname='$fname',lname='$lname',email='$email',dobirth='$dobirth',ssn=$ssn,address='$address' WHERE uid=$uid;";
    $result = mysqli_query($db,$query);
    if(!$result) {
      echo "An error has occurred when Updating your personal info.";
      echo mysqli_error($db);
    } else {
      $_SESSION['updateinfo'] = 1;
      header("location: viewapp.php");
    }
  }
}

if (isset($_POST['back'])) {
  echo ("<script>location.href = 'viewapp.php';</script>");
}

function test_input($input)
{
  $input = trim($input);
  $input = stripcslashes($input);
  $input = htmlspecialchars($input);
  return $input;
}
?>


<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

  <title>Edit Personal Info</title>
</head>

<body>
  <div class="container">
    <div class="row">
      <div class="col-sm">
        <div class="col-md">
          <h1> Edit Personal Info</h1>
          <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-group">
              <label for="name">First Name</label>
              <?php echo $fnameErr ?>
              <input type="text" class="form-control" value="<?php echo $fname ?>" name="fname" required>
            </div>
            <div class="form-group">
              <label for="name">Last Name</label>
              <?php echo $lnameErr ?>
              <input type="text" class="form-control" value="<?php echo $lname ?>" name="lname" required>
            </div>
            <div class="form-group">
              <label for="email">Email</label>
              <?php echo $emailErr ?>
              <input type="text" class="form-control" value="<?php echo $email ?>" name="email" required>
            </div>
            <div class="form-group">
              <label for="dobirth">Date of Birth</label>
              <?php echo $dobErr ?>
              <input type="text" class="form-control" value="<?php echo $dobirth ?>" name="dobirth" required>
            </div>
            <div class="form-group">
              <label for="ssn"> SSN: </label>
              <?php echo $ssnErr ?>
              <input type="password" class="form-control" name="ssn" placeholder="SSN" id="ssn" value=<?php echo $ssn; ?> required>
              <input type="checkbox" onclick="myFunction()"> Show SSN <br>
            </div>

            <script>
              function myFunction() {
                var x = document.getElementById("ssn");
                if (x.type === "password") {
                  x.type = "text";
                } else {
                  x.type = "password";
                }
              }
            </script>

            <div class="form-group">
              <label for="address">Address</label>
              <?php echo $addressErr ?>
              <input type="text" class="form-control" value="<?php echo $address ?>" name="address" required>
            </div>

            <button type="submit" class="btn btn-primary" value="updateinfo">Submit</button>
            <input type="submit" class="btn btn-primary" value="Back" name="back">
          </form>
        </div>
        <div class="col-sm">
        </div>
      </div>
    </div>

    <form>


      <!-- Optional JavaScript -->
      <!-- jQuery first, then Popper.js, then Bootstrap JS -->
      <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
      <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>

</html>
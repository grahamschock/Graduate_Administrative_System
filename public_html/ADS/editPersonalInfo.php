<?php
  session_start();
  $page_title = "Edit Personal Info";
  require_once('../header.php');
  require_once('connectvars.php');
   
  $user_id = $_SESSION['userid'];

  $query = "SELECT fname, lname, email, address, city, state FROM allusers WHERE ID = '$user_id'";

  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  
  $data = mysqli_query($dbc, $query);

  $row = mysqli_fetch_array($data);
  
  echo '<div align="right"><button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = \'personalinfo.php\';">Back</button></div>';
?>


<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title>Edit P_info</title>
  </head>
  <body>
    <div class="container">
  <div class="row">
    <div class="col-sm">
    <div class="col-md">
      <h1> Edit Personal Info</h1>
      <form method = "post" action = "updateprofile.php" >
      <div class="form-group">
                <label for="name">First Name</label>
                <input type="text" class="form-control" value="<?php echo $row['fname'] ?>" name="firstname" required>
            </div>
      <div class="form-group">
                <label for="name">Last Name</label>
                <input type="text" class="form-control" value="<?php echo $row['lname'] ?>" name="lastname" required>
            </div>
      <div class="form-group">
                <label for="email">Email</label>
                <input type="text" class="form-control"  value="<?php echo $row['email'] ?>" name="email" required>
            </div>
       <div class="form-group">
                <label for="No. and Street">No. and Street</label>
                <input type="text" class="form-control"  value="<?php echo $row['address'] ?>"  name="address" required>
               </div>
        <div class="form-group">
                <label for="city">City</label>
                <input type="text" class="form-control"  value="<?php echo $row['city'] ?>" name="city" required>
               </div>
        <div class="form-group">
                <label for="State">State</label>
                <input type="text" class="form-control"  value="<?php echo $row['state'] ?>" name="state" required>
               </div>

        <button type="submit" class="btn btn-primary">Submit</button>
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

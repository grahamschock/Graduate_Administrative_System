<?php 
    $page_title = "Apply For Graduation";
    require_once('../header.php');
    echo '<div align="right"><button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = \'../index.php\';">Back</button></div>';
?>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title>Apply For Graduation</title>
  </head>
  <body>
    <div class="container">
  <div class="row">
    <div class="col-sm">
    <div class="col-md">
      <?php
          if(!empty($_GET['err'])){
	      $errmsg = $_GET['err'];
	      echo "$errmsg";
	      $_GET['err'] = ""; 
	  } 
      ?>
      <form method = "post" action = "checkApp.php" >
      <div class="form-group">
      <label for="sid">Student ID</label>
      <input type="text" class="form-control" id="sid" name="sid" required>
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-primary">Apply</button>
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

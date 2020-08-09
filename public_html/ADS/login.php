<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title>Login</title>
  </head>
  <body>
<?php
    require_once('connectvars.php');
    //Start session
    session_start();

    //clear the error message
    $error_msg = "";

    //If user isn't logged in, try to log them in
    if(!isset($_SESSION['user_id'])){
	if(isset($_POST['submit'])){
            // Connect to the database
            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	    // User-entered log-in data
	    $user_id = mysqli_real_escape_string($dbc, trim($_POST['uid']));
	    $user_pass = mysqli_real_escape_string($dbc, trim($_POST['pass']));

	    if(!empty($user_id) && !empty($user_pass)){
		//Look up user ID and password from database
                $query = "SELECT user_id, password, accounttype FROM allusers WHERE user_id='$user_id' and password='$user_pass'";
		$data = mysqli_query($dbc, $query);
                
		//Check that login credentials exist
		if(mysqli_num_rows($data) == 1){
		    $row = mysqli_fetch_array($data);

		    //Set user_id and accounttype
		    $_SESSION['user_id'] = $row['user_id'];
		    $_SESSION['accounttype'] = $row['accounttype'];

                    if($_SESSION['accounttype'] == 1){
		        $home_url = 'http://' . $_SERVER["HTTP_HOST"] . dirname($_SERVER['PHP_SELF']) . '/admin.php';
		        header('Location: ' . $home_url);
		    }

                    if($_SESSION['accounttype'] == 2){
		        $home_url = 'http://' . $_SERVER["HTTP_HOST"] . dirname($_SERVER['PHP_SELF']) . '/GS_index.php';
		        header('Location: ' . $home_url);
		    }

                    if($_SESSION['accounttype'] == 3){
		        $home_url = 'http://' . $_SERVER["HTTP_HOST"] . dirname($_SERVER['PHP_SELF']) . '/FA_index.php';
		        header('Location: ' . $home_url); 
		    }

                    if($_SESSION['accounttype'] == 4){
		        $home_url = 'http://' . $_SERVER["HTTP_HOST"] . dirname($_SERVER['PHP_SELF']) . '/student_index.php';
		        header('Location: ' . $home_url);
		    }

                    if($_SESSION['accounttype'] == 5){
		        $home_url = 'http://' . $_SERVER["HTTP_HOST"] . dirname($_SERVER['PHP_SELF']) . '/alumni_index.php';
		        header('Location: ' . $home_url);
		    }
		}else{
		    $error_msg = 'Sorry, you must enter a valid userID and password to log in.';
		}
	    }else{
	        $error_msg = 'Sorry, you must enter your userID and password to log in.';
	    }
	}
    }
?>
    <div class="container">
    <div class="row">
    <div class="col-sm">
    </div>
    <div class="col-md">
      <h1> Login </h1>
<?php
    if(empty($_SESSION['user_id'])){
        echo '<p class="error">' . $error_msg . '</p>';
?>
      <form id="login" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-group">
                <label for="uid">Username</label>
		<input type="text" class="form-control" name = "uid" value="<?php if (!empty($user_id)) echo $user_id; ?>" />
            </div>
            <div class="form-group">
                <label for="pass">Password</label>
                <input type="password" class="form-control" name = "pass" />
            </div>
         <button type="submit" class="btn btn-primary" name="submit">Submit</button>
      </form>
    </div>
    <div class="col-sm">
    </div>
  </div>
  </div>
<?php
    }else{
        echo('<p class="login">You are logged in as ' . $_SESSION['user_id'] . '.</p>');
    }
?>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  </body>
</html>

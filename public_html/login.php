<?php
session_start();

if (isset($_SESSION["userid"])) {
	header("Location: index.php"); // user is already signed in
}

$page_title = 'Login';
require_once('connectvars.php');
require_once('header.php');

?>




<div class="p-3 mb-2 bg-light text-dark">


	<h3 style="text-align:left">Login</h3>

	</br>

	<form method="post">
		<div class="form-group">
			<input type="username" class="form-control" name="username" placeholder="username">
		</div>
		<div class="form-group">
			<input type="password" class="form-control" name="password" placeholder="password">
		</div>
		<button type="submit" class="btn btn-primary">Submit</button>
	</form>

	<button type="submit" class="btn btn-secondary" onclick="window.location.href = 'APPS/applicants.php';">Applicant? Click Here!</button>

	<form method="post">
		<button name="reset" type="submit" class="btn btn-danger"> RESET WEBSITE </button>

		</br>


		<?php
		$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

		if (isset($_POST["reset"])) {
			$query = file_get_contents("data_population_final.sql");
			$lines = explode(';', $query);
			$templine = '';
			$error = "The Database was Reset Successfully.";
			foreach ($lines as $line) {
				if (empty($line) || (substr($line,0,2) == "--")) {
					continue;
				}
				mysqli_query($dbc, $line . ";");
				$templine = mysqli_error($dbc);
				if(!empty($templine) && $templine != "Query was empty") {
					echo "\n".$line."\n";
				 	$error = "There is an issue resetting the database.";
				}				
			}
			echo $error;
		} else if (isset($_POST['username']) && isset($_POST['password'])) {
			if (strlen($_POST['username']) > 0 && strlen($_POST['password']) > 0) {

				$data = mysqli_query($dbc, "SELECT * FROM allusers WHERE (ID = '" . $_POST['username'] . "');");


				if (mysqli_num_rows($data) != 1) {
					echo '<div class="alert alert-danger" role="alert">
  User not found
</div>';
					return;
				}


				$data = mysqli_query($dbc, "SELECT ID, acctype FROM allusers WHERE (ID = '" . $_POST['username'] . "' AND password = '" . $_POST['password'] . "');");

				if (mysqli_num_rows($data) != 1) {
					echo '<div class="alert alert-danger" role="alert">
  Incorrect password</div>';
					return;
				}


				// if here we're successful
				$row = mysqli_fetch_array($data);
				$_SESSION = array(); // clears the variables in the session
				session_destroy();
				session_start(); // restart session with account creds now
				$_SESSION["userid"] = $row['ID'];
				$_SESSION["acctype"] = $row['acctype'];


				// Split into three separate digits
				$first = intdiv($_SESSION["acctype"], 100);
				$second = intdiv($_SESSION["acctype"], 10) % 10;
				$third = $_SESSION["acctype"] % 10;
                $acctype = $row["acctype"] . '';

				if ($row['acctype'] == 1) {
					header('Location: accounts.php');
                }
				else if ($row['acctype'] == 2) {
					header('Location: ADS/GS_index.php');
                }

				else if ($first == 6 || $second == 6 || $third == 6)
					header('Location: ADS/FA_index.php');
				else if ($row['acctype'] == 9)
					header('Location: ADS/alumni_index.php');
				else if ($row['acctype'] == 8)
					header('Location: index.php');
                else if(strpos($acctype, '7') !== false) {
                    header('Location: entergrades.php');
                }
                else if(strpos($acctype, '3') !== false) {
                    header('Location: faculty_landing.php');
                }
                else if(strpos($acctype, '4') !== false) {
                    header('Location: add_courses.php');
                }
				else
                    header('Location: index.php');
			} else {

				echo '<div class="alert alert-danger" role="alert">
  Please enter a username and password
</div>';
			}
		}
		?>

</div>

<div />

<?php
require_once('footer.php');
?>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>

</html>

 <!-- All users, identified by acctype as different user previlages
 1 = admin, 2 = GS, 3 = CAC, 4 = registrar, 5 = faculty reviewer
 6 = faculty advisor, 7 = instructor, 8 = students, 9 = alumni -->
<?php
require_once('connectvars.php');
$acctype = $_SESSION["acctype"] . '';
//$path = "/~ubuntu/";
//$path = "/~ubuntu/BroncoPlusPlus/public_html/";
$path = "/~sp20DBp2-BroncoPlusPlus/";
$root = "http://" . $_SERVER['SERVER_NAME'] . $path;

  if(isset($_SESSION["userid"])) {
  
    echo '<div class = "text-right">';

    if ((strpos($acctype, '8') !== false)) {
      echo '<button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = \''. $root.'index.php\';">Dashboard</button> ';
      echo '<button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = \''. $root.'my_schedule.php\';">View your schedule</button> ';
      echo '<button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = \''. $root.'ADS/personalinfo.php\';">View Personal Info</button> ';
      echo '<button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = \''. $root.'ADS/form1.php\';">View Form 1</button> ';
      echo '<button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = \''. $root.'ADS/applyGrad.php\';">Apply For Graduation</button> ';

      // Query to acquire advisor ID
      $query = "SELECT f_id FROM advises WHERE s_id=" . $_SESSION['userid'];
      $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
      $data = mysqli_query($dbc, $query);
      
      // Check if advisor exists
      if(mysqli_num_rows($data) == 1){
	  $row = mysqli_fetch_array($data);
          // Send Messages
	  echo '<form id="send" method="post" action="' . $root . 'ADS/message.php">'; 
          echo '<input type="hidden" id="msg" name="msg" value="' . $row['f_id'] . '">';
	  echo '</form>';	  
	  // View Inbox
	  echo '<form id="inbox" method="post" action="' . $root . 'ADS/inbox.php">'; 
          echo '<input type="hidden" id="inID" name="inID" value="' . $row['f_id'] . '">';
          echo '</form>';	  
          echo '<button type="submit" form="send" class="btn btn-primary btn-lg">Message</button>';
          echo '<button type="submit" form="inbox" class="btn btn-primary btn-lg">View Inbox</button>';
      }
    }

    if ((strpos($acctype, '6') !== false)) {
        echo '<button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = \''. $root.'ADS/FA_index.php\';">Grad System</button> ';
    }
    
    if (strpos($acctype, '2') !== false) {
      echo '<button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = \''. $root.'ADS/GS_index.php\';">Dashboard</button> ';
      echo '<button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = \''. $root.'entergrades.php\';">Enter Grades</button> ';
      echo '<button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = \''. $root.'faculty_landing.php\';">Review</button> ';

    }

    if ((strpos($acctype, '1') !== false) || (strpos($acctype, '7') !== false )) {
      echo '<button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = \''. $root.'entergrades.php\';">Enter Grades</button> ';
    }
    
    if ((strpos($acctype, '8') !== false) || (strpos($acctype, '1') !== false )) {
      echo '<button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = \''. $root.'transcript.php\';">View Transcripts</button> ';
    }
    
    if (strpos($acctype, '1') !== false) {
      echo '<button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = \''. $root.'accounts.php\';">Accounts</button> ';
      echo '<button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = \''. $root.'faculty_landing.php\';">Review</button> ';
    }
    if(strpos($acctype,'5') !== false) {
        echo '<button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = \''. $root.'faculty_landing.php\';">Review</button> ';
    }
    if(strpos($acctype,'3') !== false) {
      echo '<button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = \''. $root.'faculty_landing.php\';">Review</button> ';
    }

    if($acctype == 9) {
        echo '<button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = \''. $root.'ADS/alumni_index.php\';">Alumni Home</button> ';
    }
 
    if($acctype == 2){
        echo '<button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = \''. $root.'logout.php\';">Logout</button> </div>';

    }else{
        echo '<button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = \''. $root.'logout.php\';">Logout</button> </div>';
    }
   }
  
?>

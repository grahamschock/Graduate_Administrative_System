 <!-- All users, identified by acctype as different user previlages
 1 = admin, 2 = GS, 3 = CAC, 4 = registrar, 5 = faculty reviewer
 6 = faculty advisor, 7 = instructor, 8 = students, 9 = alumni -->
<?php
$acctype = $_SESSION["acctype"] . '';

  if(isset($_SESSION["userid"])) {
  
    echo '<div class = "text-right">';

    if ((strpos($acctype, '8') !== false)) {
      echo '<button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = \'/../index.php\';">Dashboard</button> ';
      echo '<button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = \'/../my_schedule.php\';">View your schedule</button> ';
      echo '<button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = \'personalinfo.php\';">View Personal Info</button> ';
      echo '<button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = \'form1.php\';">View Form 1</button> ';
      echo '<button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = \'applyGrad.php\';">Apply For Graduation</button> ';
    }

    if ((strpos($acctype, '6') !== false)) {
        echo '<button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = \' FA_index.php\';">Grad System</button> ';
    }
    
    if (strpos($acctype, '2') !== false) {
      echo '<button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = \'../entergrades.php\';">Enter Grades</button> ';
    }

    if ((strpos($acctype, '1') !== false) || (strpos($acctype, '7') !== false )) {
      echo '<button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = \'../entergrades.php\';">Enter Grades</button> ';
    }
    
    
    if ((strpos($acctype, '8') !== false) || (strpos($acctype, '1') !== false )) {
      echo '<button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = \'transcript.php\';">View Transcripts</button> ';
    }
    
    if ($acctype == 1) {
      echo '<button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = \'accounts.php\';">Accounts</button> ';
      echo '<button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = \'faculty_landing.php\';">Review</button> ';
    }
    if((strpos($acctype, '5') !== false)) {
        echo '<button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = \'../faculty_landing.php\';">Review</button> ';
    }

    if($acctype == 2) {
        echo '<button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = \' GS_index.php\';">Grad System</button> ';
        echo '<button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = \'../faculty_landing.php\';">Review</button> ';
    }

    if($acctype == 3) {
      echo '<button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = \'faculty_landing.php\';">Review</button> ';
    }

    if($acctype == 9) {
        echo '<button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = \' alumni_index.php\';">Alumni Home</button> ';
    }
 
    if($acctype == 2){
        echo '<button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = \'../logout.php\';">Logout</button> </div>';
    }else{
        echo '<button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = \'logout.php\';">Logout</button> </div>';
    }
   }
  
?>

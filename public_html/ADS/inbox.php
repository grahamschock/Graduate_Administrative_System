<?php 
    session_start();
    $page_title = "Advising Inbox";
    require_once('../header.php');
    require_once('connectvars.php');
    require_once('appvars.php');
    
    // Acquire ID of sender
    if(isset($_POST['inID'])){
        if($_POST['inID'] != -1){
	    $_SESSION['inID'] = $_POST['inID'];
            $_POST['inID'] = -1;
	}
    }

    if($_SESSION['acctype'] == 8){
//        echo '<button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = \'message.php\';">Send</button>';
        echo '<button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = \'../index.php\';">Back</button>';
    }else{
//        echo '<button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = \'message.php\';">Send</button>';
        echo '<button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = \'FA_index.php\';">Back</button>';
    }
?>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {
  margin: 0 auto;
  max-width: 800px;
  padding: 0 20px;
}

.container {
  border: 2px solid #dedede;
  background-color: #f1f1f1;
  border-radius: 5px;
  padding: 10px;
  margin: 10px 0;
}

.darker {
  border-color: #ccc;
  background-color: #ddd;
}

.container::after {
  content: "";
  clear: both;
  display: table;
}

.container img {
  float: left;
  max-width: 60px;
  width: 100%;
  margin-right: 20px;
  border-radius: 50%;
}

.container img.right {
  float: right;
  margin-left: 20px;
  margin-right:0;
}

.time-right {
  float: right;
  color: #aaa;
}

.time-left {
  float: left;
  color: #999;
}
</style>
</head>
<body>
<?php 
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $query = "SELECT subject, body, timestamp, sender, fname, lname, picture FROM messaging, inbox, allusers WHERE selfrecord=" . $_SESSION['inID'] . " and msg_ID=mid and sender=ID and (sender=" . $_SESSION['inID'] . " or sender=" . $_SESSION['userid'] . ") and inboxID=" . $_SESSION['userid'] . " ORDER BY timestamp DESC";
    $data = mysqli_query($dbc, $query);
  
    while($row = mysqli_fetch_array($data)){
	if($row['sender'] != $_SESSION['userid']){    
?>
<div class="container">
<?php echo '<img src="images/' . $row['picture'] . '" alt="Avatar" style="width:100%;">'; ?>
  <span><span style="font-weight:bold">Name:</span> <?php echo $row['fname']; ?> <?php echo $row['lname']; ?></span><br>
  <span><span style="font-weight:bold">Subject:</span> <?php echo $row['subject']; ?></span>
  <p><span style="font-weight:bold">Message:</span> <?php echo $row['body']; ?></p>
  <span class="time-right"><?php echo $row['timestamp']; ?></span>
</div>
<?php 
        }else{
?>
<div class="container darker">
<?php echo '<img src="images/' . $row['picture'] . '" alt="Avatar" class="right" style="width:100%;">' ?>
  <span><span style="font-weight:bold">Name:</span> <?php echo $row['fname']; ?> <?php echo $row['lname']; ?></span><br>
  <span><span style="font-weight:bold">Subject:</span> <?php echo $row['subject']; ?></span>
  <p><span style="font-weight:bold">Message:</span> <?php echo $row['body']; ?></p>
  <span class="time-left"><?php echo $row['timestamp']; ?></span>
</div>
<?php
        }
    }
?>
</body>
</html>

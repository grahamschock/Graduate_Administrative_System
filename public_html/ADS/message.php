<?php
    session_start();
    
    // Retain ID
    if(isset($_POST['msg'])){
        if($_POST['msg'] != -1){
	    $_SESSION['msg'] = $_POST['msg'];
	    $_POST['msg'] = -1;
	}
    }

    $page_title = "Advising Messaging System";
    require_once('../header.php');
    require_once('connectvars.php');
    require_once('appvars.php');

    if($_SESSION['acctype'] == 8){
        //echo '<button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = \'inbox.php\';">Inbox</button>';
        echo '<button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = \'../index.php\';">Back</button>';
    }else{
        //echo '<button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = \'inbox.php\';">Inbox</button>';
        echo '<button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = \'FA_index.php\';">Back</button>';
    }
?>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {font-family: Arial, Helvetica, sans-serif;}
* {box-sizing: border-box;}

input[type=text], select, textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    margin-top: 6px;
    margin-bottom: 16px;
    resize: vertical;
}

input[type=submit] {
    background-color: #4CAF50;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

input[type=submit]:hover {
    background-color: #45a049;
}

.container {
    border-radius: 5px;
    background-color: #f2f2f2;
    padding: 20px;
}
</style>
</head>

<body>
<?php
    $error_msg = "";
    $fail = 1;
    $emailExists = 0;
    $requiredFields = 1;
    
    if(isset($_POST['msgID'])){
	if($_POST['msgID'] != -1){ 
            $subject = $_POST['topic'];
            $email = $_POST['email'];
	    $message = $_POST['message'];
        
   	    // Check to see if required fields are empty
	    if(empty($subject) || empty($message)){
		$requiredFields = 0;
                $error_msg .= " Please fill in the required fields *.";	    
	    }

	    //If email is NOT empty, validate email
	    if(!empty($email)){
                // remove illegal character	 
		$email = filter_var($email, FILTER_SANITIZE_EMAIL);
		
		// Validate email
		if (filter_var($email, FILTER_VALIDATE_EMAIL)){
		    $emailExists = 1;
                    
		    // If both conditions are met, no fail
                    if($requiredFields == 1){
	                $fail = 0;
		    }
	        }else{
	            $error_msg .= " Please fill in valid email to continue.";
		}
	    }else{
                // If first condition is met, no fail
		if($requiredFields == 1){		    
		    $fail = 0;
		}
            }
	}
    }

    if($fail == 1){
        echo '<p class="error">' . $error_msg . '</p>';
?>
<div class="container">
<form id="sendmsg" class="form-inline my-2 my-lg-0" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label for="topic">*Subject</label>
    <input type="text" id="topic" name="topic" placeholder="subject" value="<?php if (!empty($subject)) echo $subject; ?>">

    <label for="email">Email Address</label>
    <input type="text" id="email" name="email" placeholder="email address" value="<?php if (!empty($email)) echo $email; ?>">

    <label for="message">*Message</label>
    <textarea id="message" name="message" placeholder="Your message..." style="height:300px"></textarea>
    
    <input type="hidden" id="msgID" name="msgID" value="<?php echo $_SESSION['msg']; ?>">
    <input type="submit" value="Send">
  </form>
</div>
<?php 
    }else{
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        
	// Check if email was entered
	if($emailExists == 1){
	    // Insert message with email
            $msgQuery = "INSERT INTO messaging (subject, email, body, timestamp) VALUES('" . $subject . "', '" . $email . "', '" . $message . "', now())";
            mysqli_query($dbc, $msgQuery);
             
	    // Acquire auto incremented message ID and insert into user inbox
            $autoID = mysqli_insert_id($dbc);
	    $inboxQuery = "INSERT INTO inbox (inboxID, sender, selfrecord, msg_ID) VALUES(" . $_POST['msgID'] . ", " . $_SESSION['userid'] . ", " . $_SESSION['userid'] . ", " . $autoID . ")";
	    mysqli_query($dbc, $inboxQuery);
	    
            // Insert copy into sender's inbox
	    $inboxQuery = "INSERT INTO inbox (inboxID, sender, selfrecord, msg_ID) VALUES(" . $_SESSION['userid'] . ", " . $_SESSION['userid'] . ", " . $_POST['msgID'] . ", " . $autoID . ")";
	    mysqli_query($dbc, $inboxQuery);
            
	    // Send the message and subject to email
	    $emsg = wordwrap($message, 70);
	    mail($email, $subject, $emsg);
	}else{
	    // Insert message
            $msgQuery = "INSERT INTO messaging (subject, body, timestamp) VALUES('" . $subject . "', '" . $message . "', now())";
	    mysqli_query($dbc, $msgQuery);
           
	    // Acquire auto incremented message ID and insert into user inbox
            $autoID = mysqli_insert_id($dbc);
	    $inboxQuery = "INSERT INTO inbox (inboxID, sender, selfrecord, msg_ID) VALUES(" . $_POST['msgID'] . ", " . $_SESSION['userid'] . ", " . $_SESSION['userid'] . ", " . $autoID . ")";
	    mysqli_query($dbc, $inboxQuery);
	    
            // Insert copy into sender's inbox
	    $inboxQuery = "INSERT INTO inbox (inboxID, sender, selfrecord, msg_ID) VALUES(" . $_SESSION['userid'] . ", " . $_SESSION['userid'] . ", " . $_POST['msgID'] . ", " . $autoID . ")";
	    mysqli_query($dbc, $inboxQuery);
	}
        
	// Reset post variable
	$_POST['msgID'] = -1;

	if($_SESSION['acctype'] == 8){
	    // Link back to student page
	    $home_url = 'http://' . $_SERVER["HTTP_HOST"] . dirname($_SERVER['PHP_SELF']) . '/../index.php';
	}else{
	    // Link back to faculty page
	    $home_url = 'http://' . $_SERVER["HTTP_HOST"] . dirname($_SERVER['PHP_SELF']) . '/FA_index.php';
	}
        header('Location: ' . $home_url);

    }
?>
</body>
</html>

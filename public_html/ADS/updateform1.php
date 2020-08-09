<?php
    require_once('connectvars.php');
    session_start();

    //Load in form 1 edits

    $user_id = $_SESSION['userid'];
    $degree = "";
    $dept1 = $_SESSION['dep1'] = $_POST['Dept1'];
    $num1 = $_SESSION['num1'] = $_POST['Num1'];
    $dept2 = $_SESSION['dep2'] = $_POST['Dept2'];
    $num2 = $_SESSION['num2'] = $_POST['Num2'];
    $dept3 = $_SESSION['dep3'] = $_POST['Dept3'];
    $num3 = $_SESSION['num3'] = $_POST['Num3'];
    $dept4 = $_SESSION['dep4'] = $_POST['Dept4'];
    $num4 = $_SESSION['num4'] = $_POST['Num4'];
    $dept5 = $_SESSION['dep5'] = $_POST['Dept5'];
    $num5 = $_SESSION['num5'] = $_POST['Num5'];
    $dept6 = $_SESSION['dep6'] = $_POST['Dept6'];
    $num6 = $_SESSION['num6'] = $_POST['Num6'];
    $dept7 = $_SESSION['dep7'] = $_POST['Dept7'];
    $num7 = $_SESSION['num7'] = $_POST['Num7'];
    $dept8 = $_SESSION['dep8'] = $_POST['Dept8'];
    $num8 = $_SESSION['num8'] = $_POST['Num8'];
    $dept9 = $_SESSION['dep9'] = $_POST['Dept9'];
    $num9 = $_SESSION['num9'] = $_POST['Num9'];
    $dept10 = $_SESSION['dep10'] = $_POST['Dept10'];
    $num10 = $_SESSION['num10'] = $_POST['Num10'];
    $dept11 = $_SESSION['dep11'] = $_POST['Dept11'];
    $num11 = $_SESSION['num11'] = $_POST['Num11'];
    $dept12 = $_SESSION['dep12'] = $_POST['Dept12'];
    $num12 = $_SESSION['num12'] = $_POST['Num12'];
   
   //Failure
    $fail = 0;
    $errmsg = '';
    
    //create an array of course info
    $coursedept = array($dept1, $dept2, $dept3, $dept4, $dept5, $dept6, $dept7, $dept8, $dept9, $dept10, $dept11, $dept12);
    $coursenum = array($num1, $num2, $num3, $num4, $num5, $num6, $num7, $num8, $num9, $num10, $num11, $num12);

    $query = "SELECT type FROM allusers WHERE ID = '$user_id'";
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $data = mysqli_query($dbc, $query);
    $row = mysqli_fetch_assoc($data);
    $degree = $row['type'];

    // Keeping track of repeats
    $repeatArr = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

    // Check for repeated entries
    for($x = 0; $x < 12; $x++){
	// If not empty
	if(!(empty($coursedept[$x]) && empty($coursenum[$x]))){
            //Check to see if course is repeated
            for($y = 0; $y < 12; $y++){
                //Skip over itself
		if($y != $x){
                    // Check to see if coursenum and coursedept match
		    if($coursenum[$x]==$coursenum[$y] && strcmp($coursedept[$x], $coursedept[$y])==0){
		        // Avoid detecting the same repeat. $y should be larger than $x		
                        if($x < $y){
			    $repeatArr[$y] = 1;
			}
		        $fail = 1;
		    }
	        }
	    }
	}
    }

    // If repeat detected, update error message
    if($fail == 1){
	$errmsg .= " You have entered repeated courses!";
    }

    //Query into form1
    for($x = 0; $x < 12; $x++){	
        if(!(empty($coursedept[$x]) && empty($coursenum[$x]))){
            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            $query = "SELECT cnum, department FROM courses WHERE cnum='$coursenum[$x]' and department='$coursedept[$x]'";
	    $data = mysqli_query($dbc, $query);
	    $rowCount = mysqli_num_rows($data);
 
	     //If insertion failed, it's possible invalid course was entered
	    if($rowCount == 0){
		// If the insertion failed WAS NOT due to repeated entry    
		if($repeatArr[$x] != 1){
	            // Invalid course errors
                    switch($x){
		        case 0:
			    $_SESSION['error1'] = "Invalid course!";	
                            break;
			case 1:
			    $_SESSION['error2'] = "Invalid course!";	
                            break;
                        case 2:
                 	    $_SESSION['error3'] = "Invalid course!";	
                            break;
			case 3:
			    $_SESSION['error4'] = "Invalid course!";	
                            break;
			case 4:
			    $_SESSION['error5'] = "Invalid course!";	
                            break;
			case 5:
			    $_SESSION['error6'] = "Invalid course!";	
                            break;
			case 6:
			    $_SESSION['error7'] = "Invalid course!";	
                            break;
			case 7:
			    $_SESSION['error8'] = "Invalid course!";	
                            break;
			case 8:
			    $_SESSION['error9'] = "Invalid course!";	
                            break;
			case 9:
			    $_SESSION['error10'] = "Invalid course!";	
                            break;
			case 10:
			    $_SESSION['error11'] = "Invalid course!";	
                            break;
			case 11:
			    $_SESSION['error12'] = "Invalid course!";	
                            break;
	            }
		}
		$fail = 1;
	    }else{
		//Insert temporarily to count credits
                $query = "INSERT INTO form1(f1_id, f1_dep, f1_cnum) VALUES ('$user_id', '$coursedept[$x]', '$coursenum[$x]')";
	        mysqli_query($dbc, $query);

		// Check for prereqs
		$query = "SELECT p1num, p1dep, p2num, p2dep FROM courses WHERE cnum=" . $coursenum[$x] . " and department='" . $coursedept[$x] . "'";
		$data = mysqli_query($dbc, $query);
		$checkPR = mysqli_fetch_assoc($data);

		// If prereq 1 exists, check to see if student also signed up for prereq
		if($checkPR['p1num'] != NULL){
		    // Set fail to 1, change back to 0 if prereq is found
	            $failPR = 1;
                    
		    // Searching for prereq
		    for($y = 0; $y < 12; $y++){
			if($coursenum[$y] == $checkPR['p1num'] && strcmp($coursedept[$y], $checkPR['p1dep'])==0){
                            $failPR = 0;
			}
		    }
                    
		    //If failed to sign up for prereqs
		    if($failPR == 1){
		       // $errmsg .= " Missing prerequisite: " . $checkPR['p1dep'] . " " . $checkPR['p1num'] . " for " . $coursedept[$x] . " " . $coursenum[$x];
			
                        switch($x){
			    case 0:
		                $_SESSION['error1'] = " Missing prerequisite: " . $checkPR['p1dep'] . " " . $checkPR['p1num'];	
                                break;
			    case 1:
		                $_SESSION['error2'] = " Missing prerequisite: " . $checkPR['p1dep'] . " " . $checkPR['p1num'];	
                                break;
			    case 2:
		                $_SESSION['error3'] = " Missing prerequisite: " . $checkPR['p1dep'] . " " . $checkPR['p1num'];	
                                break;
			    case 3:
		                $_SESSION['error4'] = " Missing prerequisite: " . $checkPR['p1dep'] . " " . $checkPR['p1num'];	
                                break;
			    case 4:
		                $_SESSION['error5'] = " Missing prerequisite: " . $checkPR['p1dep'] . " " . $checkPR['p1num'];	
                                break;
			    case 5:
		                $_SESSION['error6'] = " Missing prerequisite: " . $checkPR['p1dep'] . " " . $checkPR['p1num'];	
                                break;
			    case 6:
		                $_SESSION['error7'] = " Missing prerequisite: " . $checkPR['p1dep'] . " " . $checkPR['p1num'];	
                                break;
			    case 7:
		                $_SESSION['error8'] = " Missing prerequisite: " . $checkPR['p1dep'] . " " . $checkPR['p1num'];	
                                break;
			    case 8:
		                $_SESSION['error9'] = " Missing prerequisite: " . $checkPR['p1dep'] . " " . $checkPR['p1num'];	
                                break;
			    case 9:
		                $_SESSION['error10'] = " Missing prerequisite: " . $checkPR['p1dep'] . " " . $checkPR['p1num'];	
                                break;
			    case 10:
		                $_SESSION['error11'] = " Missing prerequisite: " . $checkPR['p1dep'] . " " . $checkPR['p1num'];	
                                break;
			    case 11:
		                $_SESSION['error12'] = " Missing prerequisite: " . $checkPR['p1dep'] . " " . $checkPR['p1num'];	
                                break;
			}
			$fail = 1;
		    }	
		}

		// If prereq 2 exists, check to see if student also signed up for prereq
		if($checkPR['p2num'] != NULL){
		    $failPR = 1;

		    // Searching for prereq
		    for($y = 0; $y < 12; $y++){
			if($coursenum[$y] == $checkPR['p2num'] && strcmp($coursedept[$y], $checkPR['p2dep'])==0){
                            $failPR = 0;
			}
		    }
                    
		    //If failed to sign up for prereqs
		    if($failPR == 1){
		        //$errmsg .= " Missing prerequisite: " . $checkPR['p2dep'] . " " . $checkPR['p2num'] . " for " . $coursedept[$x] . " " . $coursenum[$x];
			
                        switch($x){
			    case 0:
		                $_SESSION['error1'] .= " Missing prerequisite: " . $checkPR['p2dep'] . " " . $checkPR['p2num'];	
                                break;
			    case 1:
		                $_SESSION['error2'] .= " Missing prerequisite: " . $checkPR['p2dep'] . " " . $checkPR['p2num'];	
                                break;
			    case 2:
		                $_SESSION['error3'] .= " Missing prerequisite: " . $checkPR['p2dep'] . " " . $checkPR['p2num'];	
                                break;
			    case 3:
		                $_SESSION['error4'] .= " Missing prerequisite: " . $checkPR['p2dep'] . " " . $checkPR['p2num'];	
                                break;
			    case 4:
		                $_SESSION['error5'] .= " Missing prerequisite: " . $checkPR['p2dep'] . " " . $checkPR['p2num'];	
                                break;
			    case 5:
		                $_SESSION['error6'] .= " Missing prerequisite: " . $checkPR['p2dep'] . " " . $checkPR['p2num'];	
                                break;
			    case 6:
		                $_SESSION['error7'] .= " Missing prerequisite: " . $checkPR['p2dep'] . " " . $checkPR['p2num'];	
                                break;
			    case 7:
		                $_SESSION['error8'] .= " Missing prerequisite: " . $checkPR['p2dep'] . " " . $checkPR['p2num'];	
                                break;
			    case 8:
		                $_SESSION['error9'] .= " Missing prerequisite: " . $checkPR['p2dep'] . " " . $checkPR['p2num'];	
                                break;
			    case 9:
		                $_SESSION['error10'] .= " Missing prerequisite: " . $checkPR['p2dep'] . " " . $checkPR['p2num'];	
                                break;
			    case 10:
		                $_SESSION['error11'] .= " Missing prerequisite: " . $checkPR['p2dep'] . " " . $checkPR['p2num'];	
                                break;
			    case 11:
		                $_SESSION['error12'] .= " Missing prerequisite: " . $checkPR['p2dep'] . " " . $checkPR['p2num'];	
                                break;
			}
			$fail = 1;
		    }	
		}
	    }
	}

    }


    if($degree == 'masters'){
        //Check for required courses 
        // 1 - CSCI 6212 
        // 2 - CSCI 6221
        // 3 - CSCI 6461

        $c1 = 0;	
        $c2 = 0;	
        $c3 = 0;

        // Check for required courses	
        for($x = 0; $x < 12; $x++){
            if($coursedept[$x] == 'CSCI' && $coursenum[$x] == 6212){
	        $c1 = 1;
	    }else if($coursedept[$x] == 'CSCI' && $coursenum[$x] == 6221){
	        $c2 = 1;
	    }else if($coursedept[$x] == 'CSCI' && $coursenum[$x] == 6461){
	        $c3 = 1;
	    }
	}

        if($c1 == 1 && $c2 == 1 && $c3 == 1){
            echo "You have all the correct required courses";
        }else{
            echo "You are missing required courses";
            $errmsg .= " You are missing required courses!";
            $fail = 1;
        }

        //Check Credits
        $query = "SELECT f1_dep, f1_cnum FROM form1 WHERE f1_id = '$user_id'"; 
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $data = mysqli_query($dbc, $query);

        $cscredits = 0;
        $noncscredits = 0;

        while($row = mysqli_fetch_assoc($data)){
            if($row['f1_dep'] != 'CSCI'){
                $dep = $row['f1_dep'];
                $num = $row['f1_cnum'];
                $query = "SELECT credits FROM courses WHERE department = '$dep' and cnum = '$num'";
                $creddata = mysqli_query($dbc, $query);
                $credrow = mysqli_fetch_assoc($creddata);
                $noncscredits +=$credrow['credits'];

            }else{
                $dep = $row['f1_dep'];
                $num = $row['f1_cnum'];
                $query = "SELECT credits FROM courses WHERE department = '$dep' and cnum = '$num'";
                $creddata = mysqli_query($dbc, $query);
                $credrow = mysqli_fetch_assoc($creddata);
                $cscredits += $credrow['credits'];

            }
        }

        if($cscredits < 30){
            if($cscredits + $noncscredits >= 30){

                if($noncscredits>6){
                    $errmsg .= " Not enough credits!";
                    $fail = 1;
                }
            }else{
                $errmsg .= " Not enough credits!";
                $fail = 1; 
            }
        }


        //INSERT FORM 1 INFO
        if($fail == 0){
            for($x = 0; $x < 12; $x++){
                $query = "INSERT INTO form1(f1_id, f1_dep, f1_cnum) VALUES ('$user_id', '$coursedept[$x]', '$coursenum[$x]')";
                $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                $data = mysqli_query($dbc, $query);
	    }
             
	    //Pass form 1 audit set flag and await for advisor hold removal
	    $query = "UPDATE students set form1pass = 1 WHERE SID=" . $user_id;
	    mysqli_query($dbc, $query);

            $home_url = 'http://' . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . '/form1.php?err=Masters Form 1 passed! Awaiting registration hold removal.';
            header('Location: ' . $home_url);
	}

        if($fail == 1){
            $errmsg .= " Please Adjust and Resubmit Form 1";
            $_SESSION['errmsg'] = "* " . $errmsg;
            $query = "DELETE FROM form1 WHERE f1_id = '$user_id'";
            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	    $data = mysqli_query($dbc, $query);
            $home_url = 'http://' . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . '/editForm1.php';
            header('Location: ' . $home_url);
        }
    }

    if($degree == 'doctorate'){
	
        $query = "SELECT f1_dep, f1_cnum FROM form1 WHERE f1_id = '$user_id'"; 
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $data = mysqli_query($dbc, $query);

        $cscredits = 0;
        $noncscredits = 0;

        while($row = mysqli_fetch_assoc($data)){
            if($row['f1_dep'] != 'CSCI'){
                $dep = $row['f1_dep'];
                $num = $row['f1_cnum'];
                $query = "SELECT credits FROM courses WHERE department = '$dep' and cnum = '$num'";
                $creddata = mysqli_query($dbc, $query);
                $credrow = mysqli_fetch_assoc($creddata);
                $noncscredits +=$credrow['credits'];

            }else{
                $dep = $row['f1_dep'];
                $num = $row['f1_cnum'];
                $query = "SELECT credits FROM courses WHERE department = '$dep' and cnum = '$num'";
                $creddata = mysqli_query($dbc, $query);
                $credrow = mysqli_fetch_assoc($creddata);
                $cscredits += $credrow['credits'];

            }
        }

        if($cscredits < 30){
            $errmsg .= " Not enough CS Credits! ";
            $fail = 1;
        }
        if($cscredits+$noncscredits < 36){
            $errmsg .= " Not enough Total Credits! ";
            $fail = 1;
        }

        //INSERT FORM 1 INFO
        if($fail == 0){
            for($x = 0; $x < 12; $x++){
                $query = "INSERT INTO form1(f1_id, f1_dep, f1_cnum) VALUES ('$user_id', '$coursedept[$x]', '$coursenum[$x]')";
                $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                $data = mysqli_query($dbc, $query);
	    }

	    //Pass form 1 audit set flag and await for advisor hold removal
	    $query = "UPDATE students set form1pass = 1 WHERE SID=" . $user_id;
	    mysqli_query($dbc, $query);
	    
	    $home_url = 'http://' . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . '/form1.php?err=PhD Form 1 passed! Awaiting registration hold removal.';
            header('Location: ' . $home_url);
        }
        if($fail == 1){
	    $errmsg .= " Please Adjust and Resubmit Form 1";
            $_SESSION['errmsg'] = "* " . $errmsg;
            $query = "DELETE FROM form1 WHERE f1_id = '$user_id'";
            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            $data = mysqli_query($dbc, $query);
            $home_url = 'http://' . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . '/editForm1.php';
            header('Location: ' . $home_url);
        }

    }


?>

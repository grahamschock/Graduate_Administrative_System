<?php
    require_once('connectvars.php');

    session_start();
    
    $errmsg = "";

    $sid = $_POST['sid'];

    // Check if userid matches
    if($sid != $_SESSION['userid']){
        $errmsg .=" The student ID entered does not match with current user!";
        $home_url = 'http://' . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . '/applyGrad.php?err='.$errmsg;
        header('Location: ' . $home_url);
    }

    $query = "SELECT type FROM allusers WHERE ID = '$sid'";
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $data = mysqli_query($dbc, $query);
    $row = mysqli_fetch_assoc($data);
    $degree = $row['type'];

    $query = "SELECT department, courses.cnum, credits, grade FROM takes, courses WHERE studentID='$sid' and takes.semester=courses.semester and courseID=CID";
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $data = mysqli_query($dbc, $query);

    //If Masters Degree
    //GPA >= 3.0
    //Credit Hrs >= 30
    //At least two non CS classes
    //Grades lower than B <= 2

    //GPA Calculation (Grades A, A‐, B+, B, B‐, C+, C, F)
    //A 4.0, A- 3.7, B+ 3.3, B 3.0, B- 2.7, C+ 2.3, C 2.0, F 0
    //(Credit Hours * Grade Val) / ALL CREDIT HOURS = GPA
    if($degree == 'masters'){

        $GPA = 0;
        $credithours = 0;
        $badgrades = 0;
        $earnedcredit = 0;

        while($row = mysqli_fetch_assoc($data)){
            $currcredithr = $row['credits'];
            $credithours += $currcredithr;
            switch ($row['grade']) {
                case "A":
                    $earnedcredit += $currcredithr * 4;
                    break;
                case "A-":
                    $earnedcredit += $currcredithr * 3.7;
                    break;
                case "B+":
                    $earnedcredit += $currcredithr * 3.3;
                    break;
                case "B":
                    $earnedcredit += $currcredithr * 3.0;
                    break;
                case "B-":
                    $badgrades++;
                    $earnedcredit += $currcredithr * 2.7;
                    break;
                case "C+":
                    $badgrades++;
                    $earnedcredit += $currcredithr * 2.3;
                    break;
                case "C":
                    $badgrades++;
                    $earnedcredit += $currcredithr * 2.0;
		    break;
	        case "C-":
	            $badgrades++;
		    $earnedcredit += $currcredithr * 1.7;
                    break;		    
	        case "D+":
	            $badgrades++;
		    $earnedcredit += $currcredithr * 1.3;
                    break;		    
	        case "D":
	            $badgrades++;
		    $earnedcredit += $currcredithr;
                    break;		    	    
                case "F":
                    $badgrades++;
                    $earnedcredit += $currcredithr * 0;
		    break;    
		case "IP":
		    $earnedcredit += $currcredithr * 0;
                default:
                    $earnedcredit = 0;
            }
        }

        $GPA = $earnedcredit/$credithours;

        //GRAD CHECKS
        $fail = 0;

        if($GPA < 3.0){
            $errmsg .=" You need at least a 3.0 GPA to graduate.";
            $fail = 1;
        }
        if($credithours < 30){
            $errmsg .=" You need at least 30 credits to graduate.";
            $fail = 1;
        }
        if($badgrades > 2){
            $errmsg .=" . You can't have more than 2 Grades Below B to graduate.";
            $fail = 1;
        }

        //Check Form1 == Transcript For Requirements
        $missingcourse = 0;

        $query = "SELECT f1_dep, f1_cnum FROM form1 WHERE f1_id = '$sid'";
        $data = mysqli_query($dbc, $query);
        while($row = mysqli_fetch_assoc($data)){
            $dep = $row['f1_dep'];
            $cnum = $row['f1_cnum'];
            $cquery = "SELECT * FROM takes WHERE cdept = '$dep' and cnum = '$cnum' and studentID = '$sid'";
            $cdata = mysqli_query($dbc, $cquery);
            if(mysqli_num_rows($cdata) == 0){
                $missingcourse = 1;
            }

        }

        if($missingcourse == 1){
            $errmsg .= " Transcript missing Form 1 Courses!";
            $fail = 1; 
        }
        

    


        // $query = "SELECT c_dep,c_num,semester,year, hrs, grade FROM transcript,taken,student WHERE transID=tid AND stid=tid AND sid='$sid'";
        // $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        // $data = mysqli_query($dbc, $query);


        // $c1 = 0;
        // $c2 = 0;
        // $c3 = 0;
        // $nonCS = 0;
        // while($row = mysqli_fetch_assoc($data)) {
            
        //     if($row['c_num'] == 6212){
        //         if($row['c_dep'] == 'CSCI'){
        //             $c1 = 1;
        //         }
        //     }
        //     if($row['c_num'] == 6221){
        //         if($row['c_dep'] == 'CSCI'){
        //             $c2 = 1;
        //         }
        //     }
        //     if($row['c_num'] == 6461){
        //         if($row['c_dep'] == 'CSCI'){
        //             $c3 = 1;
        //         }
        //     }
        // }

        // $query = "SELECT c_dep,c_num,semester,year, hrs, grade FROM transcript,taken,student WHERE transID=tid AND stid=tid AND sid='$sid'";
        // $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        // $data = mysqli_query($dbc, $query);

        // $nonCSCredits = 0;

        // while($row = mysqli_fetch_assoc($data)) {
        //     if($row['c_dep'] != 'CSCI'){
        //         $nonCS += 1;
        //         $nonCSCredits += $row['hrs'];
                
        //     }
        // }

        // if($nonCS>2){

        //     if($credithours-$nonCSCredits < 30){
        //         $errmsg .=" You need at least 30 credits to graduate.";
        //         $fail = 1;
        //     }
        // }


        // if($c1 == 1 && $c2 == 1 && $c3 == 1){
        //     echo "You have all the correct required courses";
        // }else{
        //     echo "You are missing required courses";
        //     $errmsg .= "You are missing required courses!";
        //     $fail = 1;
        // }




        if($fail == 1){
            $home_url = 'http://' . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . '/applyGrad.php?err='.$errmsg;
            header('Location: ' . $home_url);
        }

        if($fail == 0){
        
            $query = "SELECT f1_cnum FROM form1 WHERE f1_id = '$sid'";
            $data = mysqli_query($dbc, $query);
            if (mysqli_num_rows($data)!=0){
                echo "You graduation audit has passed. Your Graduate Secretary will process soon.";
                $query = "UPDATE advises SET gradstatus = 1 WHERE s_id = '$sid'";
                $data = mysqli_query($dbc, $query);
                $home_url = 'http://' . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . '/applyGrad.php?err=You graduation audit has passed. Your Graduate Secretary will process soon.';
            header('Location: ' . $home_url);

            }else{
                $errmsg = "You haven't filed a correct form 1";
                $home_url = 'http://' . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . '/applyGrad.php?err='.$errmsg;
            header('Location: ' . $home_url);
            }

        }

    }

    if($degree == 'doctorate'){

        $GPA = 0;
        $credithours = 0;
        $badgrades = 0;
        $earnedcredit = 0;

        while($row = mysqli_fetch_assoc($data)){
            $currcredithr = $row['credits'];
            $credithours += $currcredithr;
            switch ($row['grade']) {
                case "A":
                    $earnedcredit += $currcredithr * 4;
                    break;
                case "A-":
                    $earnedcredit += $currcredithr * 3.7;
                    break;
                case "B+":
                    $earnedcredit += $currcredithr * 3.3;
                    break;
                case "B":
                    $earnedcredit += $currcredithr * 3.0;
                    break;
                case "B-":
                    $badgrades++;
                    $earnedcredit += $currcredithr * 2.7;
                    break;
                case "C+":
                    $badgrades++;
                    $earnedcredit += $currcredithr * 2.3;
                    break;
                case "C":
                    $badgrades++;
                    $earnedcredit += $currcredithr * 2.0;
                    break;     
	        case "C-":
	            $badgrades++;
		    $earnedcredit += $currcredithr * 1.7;
                    break;		    
	        case "D+":
	            $badgrades++;
		    $earnedcredit += $currcredithr * 1.3;
                    break;		    
	        case "D":
	            $badgrades++;
		    $earnedcredit += $currcredithr;
                    break;		    	    
                case "F":
                    $badgrades++;
                    $earnedcredit += $currcredithr * 0;
                    break;    
                default:
                    $earnedcredit = 0;
            }
        }

        $GPA = $earnedcredit/$credithours;

        //GRAD CHECKS
        $fail = 0;

        if($GPA < 3.5){
            $errmsg .=" You need at least a 3.5 GPA to graduate.";
            $fail = 1;
        }
        if($credithours < 36){
            $errmsg .=" You need at least 36 credits to graduate.";
            $fail = 1;
        }
        if($badgrades > 1){
            $errmsg .=" . You can't have more than 1 Grades Below B to graduate.";
            $fail = 1;
        }

        //Check Form1 == Transcript For Requirements
        $missingcourse = 0;
        $query = "SELECT f1_dep, f1_cnum FROM form1 WHERE f1_id = '$sid'";
        $data = mysqli_query($dbc, $query);
        while($row = mysqli_fetch_assoc($data)){
            $dep = $row['f1_dep'];
            $cnum = $row['f1_cnum'];
            $cquery = "SELECT * FROM takes WHERE cdept = '$dep' and cnum = '$cnum' and studentID = '$sid'";
            $cdata = mysqli_query($dbc, $cquery);
            if(mysqli_num_rows($cdata) == 0){
                $missingcourse = 1;
            }

        }

        if($missingcourse == 1){
            $errmsg .= " Transcript missing Form 1 Courses!";
            $fail = 1; 
        }

        // $nonCS = 0;

        // $query = "SELECT c_dep,c_num,semester,year, hrs, grade FROM transcript,taken,student WHERE transID=tid AND stid=tid AND sid='$sid'";
        // $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        // $data = mysqli_query($dbc, $query);

        // $nonCSCredits = 0;

        // while($row = mysqli_fetch_assoc($data)) {
        //     if($row['c_dep'] != 'CSCI'){
        //         $nonCS += 1;
        //         $nonCSCredits += $row['hrs'];
                
        //     }
        // }

        // if($credithours-$nonCSCredits < 30){
        //         $errmsg .=" You need at least 30 credits in CS to graduate.";
        //         $fail = 1;
        // }

        if($fail == 1){
            $home_url = 'http://' . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . '/applyGrad.php?err='.$errmsg;
            header('Location: ' . $home_url);
        }

        if($fail == 0){
        
            $query = "SELECT f1_cnum FROM form1 WHERE f1_id = '$sid'";
            $data = mysqli_query($dbc, $query);
            if (mysqli_num_rows($data)!=0){
                echo "You graduation audit has passed. Your Graduate Secretary will process soon.";
                $query = "UPDATE advises SET gradstatus = 1 WHERE s_id = '$sid'";
                $data = mysqli_query($dbc, $query);
                $home_url = 'http://' . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . '/applyGrad.php?err=You graduation audit has passed. Your Advisor Needs To Approve Your Thesis. Then your Graduate Secretary will process soon.';
            header('Location: ' . $home_url);

            }else{
                $errmsg = "You haven't filed a correct form 1";
                $home_url = 'http://' . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . '/applyGrad.php?err='.$errmsg;
            header('Location: ' . $home_url);
            }

        }
    }


?>

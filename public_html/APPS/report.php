<!DOCTYPE html>
<html>

<head>
    <!--<title>Review</title>-->
    <link rel="stylesheet" type="text/css" href="facultyStyle.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>

<body>
    <?php
    // start the session and confirm that correct session vars are set
    session_start();
    $page_title = 'Application Reports';
    require_once('../header.php');
    require_once('../navmenu.php');
    require_once('../connectvars.php');
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $errors = array();
    $username = $password = $fname = $lname = "";
    $sid = 0;
    $gs = $admin = $cac = $facRev = false;
    ?>
    <?php if (!empty($error)) { ?>
        <label name="errorbox" style="color: #FF0000"><?php include('errors.php'); ?></label> <br />
    <?php } ?>
    <br>
    <div style="float:left; margin-left:10px;">
        <form id="selectButtons" class="form-inline my-2 my-lg-0" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <label class="btn btn-secondary">
                    <input type="radio" name="deg" id="option1" autocomplete="off"> Both
                </label>
                <label class="btn btn-secondary">
                    <input type="radio" name="degm" id="option2" autocomplete="off"> Master
                </label>
                <label class="btn btn-secondary">
                    <input type="radio" name="degp" id="option3" autocomplete="off"> PhD
                </label>
            </div>

            <div class="btn-group btn-group-toggle" style="margin-left:10px;" data-toggle="buttons">
                <label class="btn btn-secondary">
                    <input type="radio" name="sem" id="option1" autocomplete="off"> Both
                </label>
                <label class="btn btn-secondary">
                    <input type="radio" name="semf" id="option2" autocomplete="off"> Fall
                </label>
                <label class="btn btn-secondary">
                    <input type="radio" name="sems" id="option3" autocomplete="off"> Spring
                </label>
            </div>

            <div class="btn-group btn-group-toggle" style="margin-left:10px;" data-toggle="buttons">
                <label class="btn btn-secondary">
                    <input type="radio" name="adm" id="option1" autocomplete="off"> Both
                </label>
                <label class="btn btn-secondary">
                    <input type="radio" name="admm" id="option2" autocomplete="off"> Admitted
                </label>
                <label class="btn btn-secondary">
                    <input type="radio" name="admr" id="option3" autocomplete="off"> Rejected
                </label>
            </div>

            <select class="custom-select" style="margin-left:10px;" id="dropdown-menu" default=0 name="admYear">
                <option selected value="%" disabled>Admit Year</option>
                <?php $tempQuery = "SELECT DISTINCT admitYear FROM application ORDER BY admitYear DESC;"; ?>
                <?php $tempRess = mysqli_query($dbc, $tempQuery); ?>
                <?php while ($tempRes = mysqli_fetch_array($tempRess)) { ?>
                    <?php $admyear = $tempRes['admitYear']; ?>
                    <option class="dropdown-item" value="<?php echo $admyear; ?>"><?php echo $admyear; ?></option>
                <?php } ?>
            </select>

            <button class="btn btn-outline-success my-2 my-sm-0" name="genReport" type="submit">Generate Report</button>
        </form>
    </div><br>
    <?php
    if (!isset($_SESSION['userid'])) {
        echo 'This page has been accessed with no faculty session. To use this page you must first <a href="index.php">login.</a>';
        header("location: logout.php");
    } else {
        // establish a database connection
        // check for a transcript received POST to this page
        if (isset($_POST['genReport'])) {
            if (isset($_POST['degm'])) {
                $deg = "MS";
            } else if (isset($_POST['degp'])) {
                $deg = "PhD";
            } else {
                $deg = "%";
            }
            if (isset($_POST['semf'])) {
                $sem = "Fall";
            } else if (isset($_POST['sems'])) {
                $sem = "Spring";
            } else {
                $sem = "%";
            }
            $admYear = "%";
            if (isset($_POST['admYear'])) {
                $admYear = $_POST['admYear'];
            }
            if (isset($_POST['admm'])) {
                $query = "SELECT * FROM application AS A,applicant AS B WHERE A.uid=B.uid AND submit AND complete AND applyfor LIKE '$deg' AND admitSemes LIKE '$sem' AND admitYear LIKE '$admYear' AND finaldeci!=0";
            } else if (isset($_POST['admr'])) {
                $query = "SELECT * FROM application AS A,applicant AS B WHERE A.uid=B.uid AND submit AND complete AND applyfor LIKE '$deg' AND admitSemes LIKE '$sem' AND admitYear LIKE '$admYear' AND finaldeci=0";
            } else {
                $query = "SELECT * FROM application AS A,applicant AS B WHERE A.uid=B.uid AND submit AND complete AND applyfor LIKE '$deg' AND admitSemes LIKE '$sem' AND admitYear LIKE '$admYear'";
            }

            $results = mysqli_query($dbc, $query);
            if (mysqli_num_rows($results) != 0) {
                echo '<div class="table-responsive" style="width:100%;height:100%;">';
                echo '<table class="table table-hover table-striped table-white">';
                echo '<thead><tr>';
                echo '<th scope="col">Applicant ID</th>';
                echo '<th scope="col">Last Name</th>';
                echo '<th scope="col">First Name</th>';
                echo '<th scope="col">Applying For</th>';
                echo '<th scope="col">Interest</th>';
                echo '<th scope="col">Year</th>';
                echo '<th scope="col">Semester</th>';
                echo '<th scope="col">Review</th>';
                echo '<th scope="col">Your Decision</th>';
                echo '<th scope="col">Final Decision</th>';
                echo '</tr></thead>';
                echo '<tbody>';
            }
            $i = $verbaltot = $quanttot = $num = 0;
            while ($row = mysqli_fetch_array($results)) {
                $i = $i + 1;
                $query = 'SELECT * FROM gre WHERE aid=' . $row['aid'];
                $result = mysqli_query($dbc, $query);
                if (mysqli_num_rows($result) != 0) {
                    $scores = mysqli_fetch_array($result);
                    $verbaltot += $scores['verbalscore'];
                    $quanttot += $scores['quantscore'];
                    $num += 1;
                }

                echo '<tr>';
                echo '<td>' . $row['aid'] . '</td>';
                echo '<td>' . $row['lname'] . '</td>';
                echo '<td>' . $row['fname'] . '</td>';
                echo '<td>' . $row['applyfor'] . '</td>';
                echo '<td>' . $row['interest'] . '</td>';
                echo '<td>' . $row['admitYear'] . '</td>';
                echo '<td>' . $row['admitSemes'] . '</td>';

                //check that there is not already a review for this application by this user
                $query = 'SELECT * FROM review WHERE aid = ' . $row['aid'] . ' AND sid = ' . $sid;
                $result = mysqli_query($dbc, $query);
                echo '<form method="post" action="review.php">';
                echo '<input type="hidden" id="sid" name="sid" value="' . $row['aid'] . '">';
                echo '<input type="hidden" id="lname" name="lname" value="' . $row['lname'] . '">';
                echo '<input type="hidden" id="fname" name="fname" value="' . $row['fname'] . '">';
                echo '<input type="hidden" name="aid" value="' . $row['aid']  . '" />';
                echo '<td><button type="submit" class="btn btn-outline-success my-2 my-sm-0">Review</button></td>';
                echo '</form>';
                $rating = mysqli_fetch_array($result)["rating"];
                switch ($rating) {
                    case "0":
                        $rating = "Reject";
                        break;
                    case "1":
                        $rating = "Borderline Admit";
                        break;
                    case "2":
                        $rating = "Admit without Aid";
                        break;
                    case "3":
                        $rating = "Admit with Aid";
                        break;
                }
                if (!empty($rating))
                    echo '<td>' . $rating . '</td>';
                else echo '<td>N/A</td>';
                $finalrating = $row['finaldeci'];
                switch ($finalrating) {
                    case "0":
                        $finalrating = "Reject";
                        break;
                    case "1":
                        $finalrating = "Admit without Aid";
                        break;
                    case "2":
                        $finalrating = "Admit with Aid";
                        break;
                }
                if (!empty($finalrating)) {
                    echo '<td>' . $finalrating . '</td>';
                } else {
                    echo '<td>N/A</td>';
                }
                echo '</tr>';
            }
            $output = '<br><div class="alert alert-primary" role="alert">Total Number of Students: ' . $i;
            if ($num != 0) {
                $verbalavg = round($verbaltot / $num, 2);
                $quantavg = round($quanttot / $num, 2);
                $output .= '<br>Average GRE Verbal Score of those who submitted their score: ' . $verbalavg . '<br>
                Average GRE Quantative Score of those who submitted their score: ' . $quantavg . '</div>';
            } else {
                $output .= '</div>';
            }
            echo $output;
        }
    } ?>
</body>
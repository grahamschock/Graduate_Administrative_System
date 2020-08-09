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
    $page_title = 'Applications';
    require_once('header.php');
    require_once('navmenu.php');
    require_once('connectvars.php');
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $errors = array();
    $username = $password = $fname = $lname = "";
    $sid = 0;
    $gs = $admin = $cac = $facRev = false;
    ?>
    <?php if (!empty($error)) { ?>
        <label name="errorbox" style="color: #FF0000"><?php include('errors.php'); ?></label> <br />
    <?php } ?>
    <?php
    if (isset($_SESSION['userid'])) {
        $sid = $_SESSION['userid'];
        $ssid = strval($_SESSION['acctype']);
        if (strpos($ssid, '1') !== false) $admin = true;
        if (strpos($ssid, '2') !== false) $gs = true;
        if (strpos($ssid, '3') !== false) $cac = true;
        if (strpos($ssid, '5') !== false) $facRev = true;
        if (!$admin && !$gs && !$cac && !$facRev) {
            header("location: logout.php");
        }
    }

    $_SESSION['admin'] = $admin;
    $_SESSION['gs'] = $gs;
    $_SESSION['cac'] = $cac;
    $_SESSION['facRev'] = $facRev;?>

    <br>
    <div style="float:left; margin-left:10px;">
        <form id="selectButtons" class="form-inline my-2 my-lg-0" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <button class="btn btn-outline-secondary my-2 my-sm-0" name="showAll" type="submit">Show All</button>
            <button class="btn btn-outline-success my-2 my-sm-0" style="margin-left:10px;" name="msSearch" type="submit">Masters Only</button>
            <button class="btn btn-outline-success my-2 my-sm-0" name="docSearch" type="submit">PhD Only</button>
            <select class="custom-select" style="margin-left:10px;" id="dropdown-menu" default=0 name="admYear">
                <option selected disabled>Admit Year</option>
                <?php $tempQuery = "SELECT DISTINCT admitYear FROM application ORDER BY admitYear DESC;"; ?>
                <?php $tempRess = mysqli_query($dbc, $tempQuery); ?>
                <?php while ($tempRes = mysqli_fetch_array($tempRess)) { ?>
                    <?php $admyear = $tempRes['admitYear']; ?>
                    <option class="dropdown-item" value="<?php echo $admyear; ?>"><?php echo $admyear; ?></option>
                <?php } ?>
            </select>
            <button class="btn btn-outline-success my-2 my-sm-0" name="selectYear" type="submit">Search</button>
            <button class="btn btn-outline-success my-2 my-sm-0" style="margin-left:10px;" name="fallSearch" type="submit">Fall Only</button>
            <button class="btn btn-outline-success my-2 my-sm-0" name="springSearch" type="submit">Spring Only</button>
            <?php if($gs || $admin) { ?>
                <button class="btn btn-outline-success my-2 my-sm-0" style="margin-left:10px;" name="admitOnly" type="submit">Admitted Only</button>
                <a class="btn btn-danger my-2 my-sm-0" style="margin-left:20px;" href="APPS/report.php" name="report" type="submit">Reports</a>
            <?php } ?>
        </form>
    </div>
    <div style="float:right; margin-right:10px;">
        <form id="searchName" class="form-inline my-2 my-lg-0" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input class="form-control mr-sm-2" type="text" placeholder="Name Search" aria-label="Search" name="searchname">
            <button class="btn btn-outline-success my-2 my-sm-0" name="subNameSearch" type="submit">Search</button>
            <input class="form-control mr-sm-2" type="text" placeholder="ID Search" aria-label="Search" name="searchid">
            <button class="btn btn-outline-success my-2 my-sm-0" name="subidSearch" type="submit">Search</button>
        </form>
    </div><br>

    <?php if (!isset($_SESSION['userid'])) {
        echo 'This page has been accessed with no faculty session. To use this page you must first <a href="index.php">login.</a>';
        header("location: logout.php");
    } else {
        // establish a database connection
        // check for a transcript received POST to this page
        if (isset($_POST['submitTranscriptRec'])) {
            $query = 'UPDATE application SET trReced = TRUE WHERE aid = ' . $_POST['aid'];
            mysqli_query($dbc, $query);
            $query = 'SELECT * FROM recLet WHERE aid = ' . $_POST['aid'] . ' AND recName = NULL';
            $check_recs = mysqli_query($dbc, $query);
            if (mysqli_num_rows($check_recs) == 0) {
                // if none of the recs are null, then set the app to complete
                $query = 'UPDATE application SET complete = TRUE WHERE aid = ' . $_POST['aid'];
                mysqli_query($dbc, $query);
                echo $_POST['fname'] . ' ' . $_POST['lname'] . '\'s application is now complete.';
            } else {
                echo $_POST['fname'] . ' ' . $_POST['lname'] . '\'s application has been updated.';
            }
            //TODO add an undo button here that would post to self as well
        }

        $searchquery1 = $searchquery2 = "";
        if (isset($_POST['subNameSearch'])) {
            if (!empty($_POST['searchname']))
                $searchquery1 = "SELECT * FROM applicant,application WHERE applicant.uid=application.uid AND concat_ws(' ', fname, lname) like '%" . $_POST['searchname'] . "%'";
        }

        if (isset($_POST['subidSearch'])) {
            if (!empty($_POST['searchid']))
                $searchquery2 = "SELECT * FROM applicant,application WHERE applicant.uid=application.uid AND aid=" . $_POST['searchid'];
        }

        if (!empty($searchquery1)) {
            $result = mysqli_query($dbc, $searchquery1);
            if (mysqli_num_rows($result) == 0) {
                echo '<h4>Sorry, there are no result from your search.</h4><br>';
            }
        }
        if (!empty($searchquery2)) {
            $result = mysqli_query($dbc, $searchquery2);
            if (mysqli_num_rows($result) == 0) {
                echo '<h4>Sorry, there are no result from your search.</h4><br>';
            }
        }

        // requires GS or ADMIN privelege
        if ($gs || $admin) {
            if (!empty($searchquery1)) {
                $query = $searchquery1;
            } else if (!empty($searchquery2)) {
                $query = $searchquery2;
            } else if (isset($_POST['msSearch'])) {
                $query = 'SELECT * FROM application, applicant WHERE applicant.uid = application.uid AND NOT complete AND SUBMIT AND applyfor=\'MS\' ORDER BY aid';
            } else if (isset($_POST['docSearch'])) {
                $query = 'SELECT * FROM application, applicant WHERE applicant.uid = application.uid AND NOT complete AND SUBMIT AND applyfor=\'PhD\' ORDER BY aid';
            } else if (isset($_POST['fallSearch'])) {
                $query = 'SELECT * FROM application, applicant WHERE applicant.uid = application.uid AND NOT complete AND SUBMIT AND admitSemes=\'Fall\' ORDER BY aid';
            } else if (isset($_POST['springSearch'])) {
                $query = 'SELECT * FROM application, applicant WHERE applicant.uid = application.uid AND NOT complete AND SUBMIT AND admitSemes=\'Spring\' ORDER BY aid';
            } else if (isset($_POST['selectYear']) && isset($_POST['admYear'])) {
                $admYear = $_POST['admYear'];
                $query = 'SELECT * FROM application, applicant WHERE applicant.uid = application.uid AND NOT complete AND SUBMIT AND admitYear=\'' . $admYear . '\' ORDER BY aid';
            } else if (isset($_POST['admitOnly'])) {
                $query = 'SELECT * FROM application WHERE admitSemes=\'hello\'';
            } else {
                $query = 'SELECT * FROM application, applicant WHERE applicant.uid = application.uid AND NOT complete AND SUBMIT ORDER BY aid';
            }
            $apps = mysqli_query($dbc, $query);
            if (mysqli_num_rows($apps) == 0) {
                if (empty($searchquery)) {
                    echo '<br><h4>There are no incomplete applications.</h4><br>';
                }
            } else {
                echo '<div class="table-responsive" style="width:100%;height:100%;">';
                echo '<table class="table table-hover table-striped table-white">';
                echo '<thead><tr>';
                echo '<th scope="col">Applicant ID</th>';
                echo '<th scope="col">Last Name</th>';
                echo '<th scope="col">First Name</th>';
                echo '<th scope="col">Applying For</th>';
                echo '<th scope="col">Transcript Received</th>';
                echo '</tr></thead>';
                echo '<tbody style="height:100px;overflow:auto;">';
                while ($row = mysqli_fetch_array($apps)) {
                    if (!$row['complete'] && $row['submit']) {
                        echo '<tr>';

                        echo '<td>' . $row['aid'] . '</td>';
                        echo '<td>' . $row['lname'] . '</td>';
                        echo '<td>' . $row['fname'] . '</td>';
                        echo '<td>' . $row['applyfor'] . '</td>';

                        echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
                        echo '<input type="hidden" id="sid" name="sid" value="' . $row['aid'] . '">';
                        echo '<input type="hidden" id="lname" name="lname" value="' . $row['lname'] . '">';
                        echo '<input type="hidden" id="fname" name="fname" value="' . $row['fname'] . '">';
                        echo '<input type="hidden" name="aid" value="' . $row['aid']  . '" />';
                        echo '<td><button type="submit" class="btn btn-outline-success my-2 my-sm-0" name="submitTranscriptRec">Transcript Received</button></td>';
                        echo '</form>';
                        echo '</tr>';
                    }
                }
                echo '</div>';
            }
        }

        // display all applications that are complete but have no final decision yet
        //only say "or final decision" if this person has such privelege
        if (!empty($searchquery1)) {
            $query = $searchquery1;
        } else if (!empty($searchquery2)) {
            $query = $searchquery2;
        } else if (isset($_POST['msSearch'])) {
            $query = 'SELECT * FROM application, applicant WHERE applicant.uid = application.uid AND complete AND SUBMIT AND applyfor=\'MS\' ORDER BY aid';
        } else if (isset($_POST['docSearch'])) {
            $query = 'SELECT * FROM application, applicant WHERE applicant.uid = application.uid AND complete AND SUBMIT AND applyfor=\'PhD\' ORDER BY aid';
        } else if (isset($_POST['fallSearch'])) {
            $query = 'SELECT * FROM application, applicant WHERE applicant.uid = application.uid AND complete AND SUBMIT AND admitSemes=\'Fall\' ORDER BY aid';
        } else if (isset($_POST['springSearch'])) {
            $query = 'SELECT * FROM application, applicant WHERE applicant.uid = application.uid AND complete AND SUBMIT AND admitSemes=\'Spring\' ORDER BY aid';
        } else if (isset($_POST['selectYear']) && isset($_POST['admYear'])) {
            $admYear = $_POST['admYear'];
            $query = 'SELECT * FROM application, applicant WHERE applicant.uid = application.uid AND complete AND SUBMIT AND admitYear=\'' . $admYear . '\' ORDER BY aid';
        } else if (isset($_POST['admitOnly'])) {
            $query = 'SELECT * FROM application, applicant WHERE applicant.uid = application.uid AND complete AND SUBMIT AND finaldeci!=0 ORDER BY aid';
        } else {
            $query = 'SELECT * FROM application, applicant WHERE applicant.uid = application.uid AND complete AND SUBMIT ORDER BY admitYear DESC,admitSemes';
        }
        $apps = mysqli_query($dbc, $query);
        if (mysqli_num_rows($apps) == 0) {
            if (empty($searchquery))
                echo '<br><h4>There are no complete applications.</h4><br>';
        } else {
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
            if ($admin || $gs) {
                echo '<th scope="col">Final Decision</th>';
            }
            echo '</tr></thead>';
            echo '<tbody>';
            while ($row = mysqli_fetch_array($apps)) {
                if ($row['complete'] && $row['submit']) {
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
                    if ($admin || $gs) {
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
                    }
                    echo '</tr>';
                }
            } //endwhile
            echo '</div>';
        }
    }?>
</body>
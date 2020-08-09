<?php
require_once("connectvars.php");
$db = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
session_start();

$aid = "";

if (!isset($_SESSION['userid'])) {
        echo 'not all necessary session variables were set. yikes<br>';
        echo "Goto <a href=\"../login.php\">This Page</a> to log in.";
        header("location: logout.php");
} else {
        if (isset($_POST['aid'])) {
                $aid = $_POST['aid'];
                $_SESSION['aid'] = $aid;
        } else {
                $aid = $_SESSION['aid'];
                $_POST['aid'] = $aid;
        }
        $sid = $_SESSION['userid'];
}

// Application Info
$query = "SELECT * FROM application,applicant WHERE applicant.uid=application.uid AND application.aid=$aid;";
$result = mysqli_query($db, $query);
$infos = mysqli_fetch_array($result);
$fname = $infos['fname'];
$lname = $infos['lname'];
$aid = $infos['aid'];
$admSemes = $infos['admitSemes'];
$admYear = $infos['admitYear'];
$priorExp = $infos['priorExp'];
if ($infos['priorDegree1'] == "B") {
        $priorDeg1 = "Bachelor's Degree";
}
$pd1Place = $infos['pd1Place'];
$pd1GPA = $infos['pd1GPA'];
if ($infos['priorDegree2'] == "MS") {
        $priorDeg2 = "Master's Degree";
}
$pd2Place = $infos['pd2Place'];
$pd2GPA = $infos['pd2GPA'];
$interest = $infos['interest'];

// Review info
$query = "SELECT * FROM review WHERE sid=$sid AND aid=$aid;";
$result = mysqli_query($db, $query);
$reviewed = false;
if (mysqli_num_rows($result) != 0) {
        $reviewed = true;
        $infos = mysqli_fetch_array($result);
        $rating = $infos['rating'];
        $comments = $infos['comments'];
        $courseDef = $infos['courseDef'];
}
// Submit review
if (isset($_POST['submitReview']) && $_POST['recDec'] != "-1") {
        $rating = $_POST['recDec'];
        if (!empty($_POST['courseDef'])) {
                $courseDef = $_POST['courseDef'];
        } else {
                $courseDef = null;
        }
        if (!empty($_POST['revComment'])) {
                $revComment = $_POST['revComment'];
        } else {
                $revComment = null;
        }
        $date = date("Y-m-d H:i:s");
        $query = "INSERT INTO review VALUES ('$date','$sid','$aid','$rating','$courseDef','$revComment');";
        $result = mysqli_query($db, $query);
        if (!$result) {
                echo "Something went wrong.";
        } else {
                header("Refresh:0");
        }
}

// Recommendation Letter Review
$query = "SELECT * FROM recLet WHERE aid=$aid;";
$recomResults = mysqli_query($db, $query);
$hasRecom = false;
while ($result = mysqli_fetch_array($recomResults)) {
        if ($result['recLink'] != "NULL" && !empty($result['recLink'])) {
                $hasRecom = true;
        }
}
$recomResults = mysqli_query($db, $query);

// Submit Recommendation Review
for ($i = 1; $i < 4; $i++) {
        $postName = "submitRecRev$i";
        if (isset($_POST[$postName])) {
                $genericRes = $_POST['genericOrNot'];
                $credibleRes = $_POST['credibleOrNot'];
                $rating = $_POST['recRating'];
                $recid = $_POST['recIdVal'];
                $query = "INSERT INTO recRating VALUES ('$recid','$sid','$rating','$genericRes','$credibleRes');";
                $result = mysqli_query($db, $query);
                if (!$result) {
                        echo "Something went wrong.";
                } else {
                        header("Refresh:0");
                }
        }
}

// Previous Reviews
$query = "SELECT A.rating AS rating, A.comments AS comments, A.courseDef AS courseDef, B.fname AS fname, B.lname AS lname FROM review AS A, allusers AS B WHERE A.sid=B.ID AND A.aid=$aid;";
$pRevResults = mysqli_query($db, $query);

// Submit Final Decision
if (isset($_POST['submitFinal'])) {
        $finalDecOp = $_POST['finalDecOption'];
        $finalReason = $finalRecAdv = "NULL";
        if ($finalDecOp == 0) {
                $finalReason = $_POST['finalRejRea'];
                $query = "UPDATE application SET finalDeci=$finalDecOp,reason=\"$finalReason\" WHERE aid=$aid;";
                echo ("<script>location.href = 'APPS/admitmiddle.php';</script>");
        } else {
                $finalRecAdv = $_POST['finalRecAdv'];
                $query = "UPDATE application SET finalDeci=$finalDecOp,recomAdv=$finalRecAdv WHERE aid=$aid;";
                echo ("<script>location.href = 'APPS/admitmiddle.php';</script>");
        }
        $result = mysqli_query($db, $query);
        if (!$result) {
                echo "Something went wrong.";
                echo $query;
        } else {
                header("Refresh:0");
        }
}

// Final Decision
$query = "SELECT * FROM application WHERE aid=$aid;";
$finalQ = mysqli_query($db, $query);
$finalResult = mysqli_fetch_array($finalQ);
$finalReviewed = false;
$finalRating = "";

//Recommended advisor
$query = "SELECT DISTINCT fname,lname,ID FROM allusers WHERE acctype LIKE '%6%' ORDER BY lname;";
$staffResults = mysqli_query($db, $query);

if ($finalResult['finaldeci'] != NULL) {
        $finalReviewed = true;
        switch ($finalResult['finaldeci']) {
                case "0":
                        $finalRating = "Reject";
                        break;
                case "1":
                        $finalRating = "Admit without Aid";
                        break;
                case "2":
                        $finalRating = "Admit with Aid";
                        break;
        }
        $reason = $recAdv = "";
        if (strcmp($finalRating, "Reject") == 0) {
                switch ($finalResult['reason']) {
                        case "A":
                                $reason = "Incomplete Record";
                                break;
                        case "B":
                                $reason = "Does not meet minimum Requirements";
                                break;
                        case "C":
                                $reason = "Problems with Letters";
                                break;
                        case "D":
                                $reason = "Not competitive";
                                break;
                        case "E":
                                $reason = "Other reasons";
                                break;
                }
        } else {
                $recAdv = $finalResult['recomAdv'];
                if (!is_null($recAdv)) {
                        $query = "SELECT fname,lname FROM allusers WHERE ID=$recAdv;";
                        $recomAdvResult = mysqli_query($db, $query);
                        $recomAdvResult = mysqli_fetch_array($recomAdvResult);
                        $recAdvFname = $recomAdvResult['fname'];
                        $recAdvLname = $recomAdvResult['lname'];
                }
        }
}
?>

<!DOCTYPE html>
<html>

<head>
        <title>Review</title>
        <link rel="stylesheet" type="text/css" href="facultyReviewStyle.css" />
</head>

<body>
        <?php
        $page_title = "Review " . $fname . ' ' . $lname . "'s Application";
        require_once("header.php");
        require_once("navmenu.php");
        ?><br>

        <h2 style="color:white;">Review <span style="color:purple"> <?php echo $fname . ' ' . $lname; ?>'s</span> Application</h2>
        <?php if (isset($_SESSION['appchangemsg'])) {
                echo $_SESSION['appchangemsg'];
                unset($_SESSION['appchangemsg']);
        } ?>
        <table class="table table-hover table-white" style="text-align:center;">
                <thead class="thead-dark">
                        <tr>
                                <th scope="col">Applicant ID</th>
                                <th scope="col">Apply for Year</th>
                                <th scope="col">Semester</th>
                                <th scope="col">Prior Experience</th>
                                <?php if (!empty($priorDeg1)) { ?>
                                        <th scope="col">Prior Degree</th>
                                        <th scope="col">From</th>
                                        <th scope="col">GPA</th>
                                <?php } ?>
                                <?php if (!empty($priorDeg2)) { ?>
                                        <th scope="col">Prior Degree</th>
                                        <th scope="col">From</th>
                                        <th scope="col">GPA</th>
                                <?php } ?>
                                <?php if (!empty($interest)) { ?>
                                        <th scope="col">Area of Interest:</th>
                                <?php } ?>
                                <?php if ($_SESSION["admin"] || $_SESSION["gs"]) { ?>
                                        <th scope="col">Update Application</th>
                                <?php } else { ?>
                                        <th scope="col">View Application</th>
                                <?php } ?>
                        </tr>
                </thead>
                <tbody>
                        <tr>
                                <td><?php echo $aid; ?></td>
                                <td><?php echo $admYear; ?></td>
                                <td><?php echo $admSemes; ?></td>
                                <td><?php echo $priorExp; ?></td>
                                <?php if (!empty($priorDeg1)) { ?>
                                        <td><?php echo $priorDeg1; ?></td>
                                        <td><?php echo $pd1Place; ?></td>
                                        <td><?php echo $pd1GPA; ?></td>
                                <?php } ?>
                                <?php if (!empty($priorDeg2)) { ?>
                                        <td><?php echo $priorDeg2; ?></td>
                                        <td><?php echo $pd2Place; ?></td>
                                        <td><?php echo $pd2GPA; ?></td>
                                <?php } ?>
                                <?php if (!empty($interest)) { ?>
                                        <td><?php echo $interest; ?></td>
                                <?php } ?>
                                <?php if ($_SESSION["admin"] || $_SESSION["gs"]) { ?>
                                        <td><button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = 'APPS/adminapp.php'">Update</button></td>
                                <?php } else { ?>
                                        <td><button type="button" class="btn btn-primary btn-lg" onclick="window.location.href = 'APPS/fcompletedapp.php'">View</button></td>
                                <?php } ?>
                        </tr>
                </tbody>
        </table>
        <div class="reviewApp">
                <form method="post" id="reviewAppForm" action="<?php $_SERVER['PHP_SELF'] ?>">
                        <?php if ($reviewed) {
                                switch ($rating) {
                                        case 0:
                                                $ratingString = "Reject";
                                                break;
                                        case 1:
                                                $ratingString = "Borderline Admit";
                                                break;
                                        case 2:
                                                $ratingString = "Admit without Aid";
                                                break;
                                        case 3:
                                                $ratingString = "Admit with Aid";
                                                break;
                                } ?>
                                <p>Your Rating: <span><?php echo $ratingString; ?></span></p>
                                <?php if (!empty($comments)) { ?>
                                        <p>Comment: <span><?php echo $comments; ?></span></p>
                                <?php } ?>
                                <?php if (!empty($courseDef)) { ?>
                                        <p>Cource Deficiencies: <span><?php echo $courseDef; ?></span></p>
                                <?php } ?>
                        <?php } else if ($finalReviewed) { ?>
                                <p style="color:red;">Final Decision has been made, no review needed.</p>
                        <?php } else { ?>
                                <p>Your Rating:
                                        <select id="recDec" name="recDec" onchange="reviewRating(this.value)">
                                                <option value="-1" selected> Make a Decision </option>
                                                <option value="0">Reject</option>
                                                <option value="1">Borderline Admit</option>
                                                <option value="2">Admit without Aid</option>
                                                <option value="3">Admit with Aid</option>
                                        </select>
                                </p>
                                <p style="margin-top:20px;">Course deficiencies if any:<input type="text" name="courseDef"></p>
                                <p>Provide additional comments on this application here:<br><textarea style="font-size:10px;width:550px;height:30px;margin-bottom:15px;" name="revComment"></textarea></p><br>
                                <button type="submit" id="btn-submitRev" form="reviewAppForm" name="submitReview" style="display:none;margin-top:20px;">Submit Application Review</button>
                        <?php } ?>
                </form>
        </div><br><br>

        <?php if ($hasRecom) { ?>
                <h2 style="color:white;">Review <span style="color:purple"> <?php echo $fname . ' ' . $lname; ?>'s</span> Recommendations</h2>
        <?php } ?>
        <div class="reviewAppRec">
                <?php
                $i = 0;
                while ($recLet = mysqli_fetch_array($recomResults)) {
                        $recid = $recLet['recid'];
                        if ($recLet['recLink'] != null) {?>
                                <?php $i = $i + 1;?>
                                <a href="<?php echo $recLet['recLink']; ?>" style="display:block;margin:5px auto;text-align:center;font-size:25px;" target="_blank">Recommendation <?php echo $i; ?></a>
                                <?php
                                $tempQuery = "SELECT * FROM recRating WHERE recid=$recid AND sid=$sid;";
                                $tempRes = mysqli_query($db, $tempQuery);
                                if (mysqli_num_rows($tempRes) == 0) { ?>
                                        <form method="post" id="reviewRec<?php echo $i; ?>" action="<?php $_SERVER['PHP_SELF'] ?>">
                                                <p style="text-align:center;margin-bottom:20px;">Generic:
                                                        <select name="genericOrNot" style="color:red;">
                                                                <option value="1">Yes</option>
                                                                <option value="0">No</option>
                                                        </select>
                                                        Credible:
                                                        <select name="credibleOrNot" style="color:red;">
                                                                <option value="1">Yes</option>
                                                                <option value="0">No</option>
                                                        </select>
                                                </p>
                                                <p style="text-align:center;margin-bottom:20px;">Rating (1=worst to 5=best): <input type="number" min="1" max="5" value="5" name="recRating" onkeydown="return false;" style="color:red;" /></p>
                                                <input type="hidden" value="<?php echo $recid ?>" name="recIdVal">
                                                <button type="submit" id="btn-submitRev" style="display:block;margin:5px auto;" form="reviewRec<?php echo $i; ?>" name="submitRecRev<?php echo $i; ?>">Submit Recommendation Review</button><br>
                                        </form><br>
                                <?php } else {
                                        $tempRe = mysqli_fetch_array($tempRes);
                                        if ($tempRe['generic'] == 1) {
                                                $genericRes = "Yes";
                                        } else {
                                                $genericRes = "No";
                                        }
                                        if ($tempRe['credible'] == 1) {
                                                $credibleRes = "Yes";
                                        } else {
                                                $credibleRes = "No";
                                        } ?>
                                        <p style="text-align:center;">Generic: <span><?php echo $genericRes; ?></span> Credible: <span><?php echo $credibleRes; ?></span></p>
                                        <P style="text-align:center;">Rating (1=worst to 5=best): <span><?php echo $tempRe['rating']; ?></span></P><br>
                <?php }
                        }
                }
                ?>
        </div>
        <?php if ($_SESSION['cac'] || $_SESSION['gs'] || $_SESSION['admin']) {
                if (mysqli_num_rows($pRevResults) != 0) {
        ?>
                        <h2 style="color:white;">Previous Reviews of <span style="color:purple"> <?php echo $fname . ' ' . $lname; ?></span></h2>
                <div class="preReview">
                        <div class="table-responsive" style="width:100%;height:150px;">
                                <table class="table table-hover table-striped table-white" style="text-align:center;">
                                        <thead class="thead-dark">
                                                <tr>
                                                        <th scope="col">Reviewed By</th>
                                                        <th scope="col">Recommended Decision</th>
                                                        <th scope="col">Comments</th>
                                                        <th scope="col">Course Deficiencies</th>
                                                </tr>
                                        </thead>
                                        <?php while ($pRevResult = mysqli_fetch_array($pRevResults)) { ?>
                                                <?php switch ($pRevResult['rating']) {
                                                        case "0":
                                                                $preRevRating = "Reject";
                                                                break;
                                                        case "1":
                                                                $preRevRating = "Borderline Admit";
                                                                break;
                                                        case "2":
                                                                $preRevRating = "Admit without Aid";
                                                                break;
                                                        case "3":
                                                                $preRevRating = "Admit with Aid";
                                                                break;
                                                } ?>
                                                <tbody>
                                                        <tr>
                                                                <td><?php echo $pRevResult['fname'] . " " . $pRevResult['lname']; ?></td>
                                                                <td><?php echo $preRevRating; ?></td>
                                                                <td><?php echo $pRevResult['comments']; ?></td>
                                                                <td><?php echo $pRevResult['courseDef']; ?></td>
                                                        <?php } ?>
                                                        </tr>
                                                </tbody>
                                </table>
                        </div>
                </div>
                <?php } ?>
                <h2 style="color:white;">Final Decision for <span style="color:purple"> <?php echo $fname . ' ' . $lname; ?></span></h2>
                <div class="finaldecision">
                        <form method="post" id="finalDec" action="<?php $_SERVER['PHP_SELF'] ?>">
                                <?php if ($finalReviewed) { ?>
                                        <p style="text-align:center;">Final Decision: <span><?php echo $finalRating; ?></span></p>
                                        <?php if (!empty($reason)) { ?>
                                                <p style="text-align:center;">Reason: <span><?php echo $reason; ?></span></p>
                                        <?php } else if (!empty($recAdv)) { ?>
                                                <p style="text-align:center;">Recommended Advisor: <span><?php echo $recAdvFname . " " . $recAdvLname; ?></span></p>
                                        <?php } ?>
                                <?php } else { ?>
                                        <p style="text-align:center;">Final Decision:
                                                <select style="color:red;" id="finalDecOption" name="finalDecOption" onchange="finalDec(this.value)">
                                                        <option value="-1" selected> Make a Decision </option>
                                                        <option value="0">Reject</option>
                                                        <option value="1">Admit without Aid</option>
                                                        <option value="2">Admit with Aid</option>
                                                </select>
                                        </p>
                                        <p style="text-align:center;margin-top:20px;display:none;" id="finalRejRea">Reasons of rejection:
                                                <select style="color:red;" name="finalRejRea">
                                                        <option value="A">Incomplete Record</option>
                                                        <option value="B">Does not meet minimum Requirements</option>
                                                        <option value="C">Problems with Letters</option>
                                                        <option value="D">Not competitive</option>
                                                        <option value="E" selected>Other reasons</option>
                                                </select>
                                        </p>
                                        <p style="text-align:center;margin-top:20px;display:none;" id="finalRecAdv">Recommended Advisor:
                                                <select style="color:red;" name="finalRecAdv">
                                                        <?php
                                                        while ($staffResult = mysqli_fetch_array($staffResults)) {
                                                        ?>
                                                                <option value="<?php echo $staffResult['ID']; ?>"><?php echo $staffResult['fname'] . ' ' . $staffResult['lname']; ?></option>
                                                        <?php } ?>
                                                </select>
                                        </p>
                                        <button type="submit" id="btn-submitFinal" form="finalDec" name="submitFinal" style="display:none;color:white;margin-top:20px;">Submit Final Decision</button>
                                <?php } ?>
                        </form>
                </div><br><br>
        <?php } ?>

        <script>
                function reviewRating(val) {
                        if (val == "-1") {
                                document.getElementById("btn-submitRev").style.display = "none";
                        } else {
                                document.getElementById("btn-submitRev").style.display = "block";
                        }
                }

                function finalDec(val) {
                        if (val == "-1") {
                                document.getElementById("btn-submitFinal").style.display = "none";
                                document.getElementById("finalRejRea").style.display = "none";
                                document.getElementById("finalRecAdv").style.display = "none";
                        } else {
                                if (val == "0") {
                                        document.getElementById("finalRejRea").style.display = "block";
                                        document.getElementById("finalRecAdv").style.display = "none";
                                } else {
                                        document.getElementById("finalRejRea").style.display = "none";
                                        document.getElementById("finalRecAdv").style.display = "block";
                                }
                                document.getElementById("btn-submitFinal").style.display = "block";
                        }
                }
        </script>
</body>

</html>
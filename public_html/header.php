<!doctype html>
<html lang="en">
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=yes">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

<?php
  echo '<title>Bronco University - ' . $page_title . '</title>';

  function alert($msg) {
    echo "<script type='text/javascript'>alert('$msg');</script>";
  }
?>

<script type='text/javascript'>
  
  function displayStudentTypeDropdown(that) {
    if (that.value == "Student") {
        document.getElementById("studenttype").style.display = "block";
        document.getElementById("studenttype").required = true;
    } else {
        document.getElementById("studenttype").style.display = "none";
        document.getElementById("studenttype").required = false;

    }
}
</script>

</head>
<body>
    <div class="p-3 mb-2 bg-primary text-white">
        <h1 style="text-align:center">Bronco University <?php echo $page_title;?></h1>
    </div>
</body>
</html>

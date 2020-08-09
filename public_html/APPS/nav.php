<!-- Navigation -->
  <nav class="navbar navbar-inverse" >
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand text-white" href="viewapp.php" > <b> BroncoPlusPlus <b> </a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav navbar-right">
        <li><a href="viewapp.php">Application</a></li>

        <?php
        if(!isset($_SESSION['username'])){
          echo '<li><a href="../index.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>';
        } else {
          echo '<li><a href="../logout.php"><span class="glyphicon glyphicon-log-in"></span> Logout</a>';
        }
        ?>

        </li>
      </ul>
    </div>
  </div>
</nav>

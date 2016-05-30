<?php
session_start();

// Prevent session fixation attack by generating new session ID
session_regenerate_id();

require_once('utils.php');
$utils = new Utils();

// Check if user is already logged in
if(!isset($_SESSION['authenticated']) && !isset($_SESSION['details'])) {
	$utils->redirect('index.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Home : <?php echo $_SESSION['details']['username']; ?></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="includes/bootstrap/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  
  <link rel="shortcut icon" href="includes/ico/favicon.ico">
</head>

<body>

<nav class="navbar navbar-default navbar-static-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#homeNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="home.php">Home</a>
    </div>
    <div class="collapse navbar-collapse" id="homeNavbar">
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#"><span class="glyphicon glyphicon-user"></span>&nbsp; <?php echo $_SESSION['details']['username']; ?></a></li>
        <li><a href="logout.php"><span class="glyphicon glyphicon-log-in"></span> Log out</a></li>
      </ul>
    </div>
  </div>
</nav>
  
<div class="container">
	<div class="row">
	  <div class="col-sm-12 text-center"><p>Welcome, <strong><?php echo $_SESSION['details']['firstname']; ?></strong>! You are now securely logged in.</p></div>
	</div>
</div>

</body>
</html>
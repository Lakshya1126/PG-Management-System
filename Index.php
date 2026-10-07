<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Best PG's in Mumbai | PG Life</title>
<link href="css/bootstrap.min.css" rel="stylesheet" />
<link href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" rel="stylesheet" />
<link
href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&di
splay=swap" rel="stylesheet" />
<link href="css/common.css" rel="stylesheet" />
<link href="css/index.css" rel="stylesheet" />
</head>
<body>
<!-- Header -->
<header>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
<a class="navbar-brand" href="index.php">PG Life</a>
<button class="navbar-toggler" type="button" data-toggle="collapse" datatarget="#navbarNav">
<span class="navbar-toggler-icon"></span>
</button>
<div class="collapse navbar-collapse" id="navbarNav">
<ul class="navbar-nav ml-auto">
<?php if (!isset($_SESSION['user_id'])) { ?>
<li class="nav-item">
<a class="nav-link" href="#" data-toggle="modal" data-target="#login-modal">Login</a>
</li>
<li class="nav-item">
<a class="nav-link" href="#" data-toggle="modal" data-target="#signup-modal">Signup</a>
</li>
<?php } else { ?>
<li class="nav-item">
<a class="nav-link" href="logout.php">Logout</a>
</li>
<?php } ?>
</ul>
</div>
</nav>
</header>
<!-- Loading Placeholder -->
<div id="loading"></div>
<!-- Search Container -->
<div class="container-fluid pg-search-container">
<div class="row justify-content-center mb-3 text-white">
<div class="col-auto">
<h2>&nbsp Happiness per Square Foot</h2>
</div>
</div>
<!-- City Search Dropdown -->
<form action="property_list.php" method="GET">
<div class="row justify-content-center">
<div class="col-md-6 col-sm-8">
<div class="input-group md-form form-sm form-2 pl-0">
<select name="city" class="form-control my-0 py-1 red-border" required>
<option value="">Select your city</option>
<option value="Delhi">Delhi</option>
<option value="Mumbai">Mumbai</option>
<option value="Hyderabad">Hyderabad</option>
<option value="Bengaluru">Bengaluru</option>
</select>
<div class="input-group-append">
<button type="submit" class="btn btn-dark">
<i class="fas fa-search text-grey" aria-hidden="true"></i>
</button>
</div>
</div>
</div>
</div>
</form>
</div>
<!-- City Image Cards -->
<div class="city-container">
<div class="city-caption">
<h2>Major Cities</h2>
</div>
<div class="city-box">
<div class="city-img">
<a href="property_list.php?city=Delhi">
<img src="./img/delhi.png" alt="Delhi">
</a>
</div>
<div class="city-img">
<a href="property_list.php?city=Mumbai">
<img src="./img/mumbai.png" alt="Mumbai">
</a>
</div>
<div class="city-img">
<a href="property_list.php?city=Hyderabad">
<img src="./img/hyderabad.png" alt="Hyderabad">
</a>
</div>
<div class="city-img">
<a href="property_list.php?city=Bengaluru">
<img src="./img/bangalore.png" alt="Bengaluru">
</a>
</div>
</div>
</div>
<!-- Modals -->
<?php require "./includes/signup_modal.php"; ?>
<?php require "./includes/login_modal.php"; ?>
<!-- Footer -->
<?php require "./includes/footer.php"; ?>
<!-- Scripts -->
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/common.js"></script>
</body>
</html>

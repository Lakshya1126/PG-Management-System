<?php
session_start();
require "includes/database_connect.php";
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
header("Location: login.php");
exit();
}
// Check if property_id is provided
if (!isset($_GET['property_id'])) {
header("Location: index.php");
exit();
}
$property_id = $_GET['property_id'];
$user_id = $_SESSION['user_id'];
// Verify property exists
$sql = "SELECT * FROM properties WHERE id = $property_id";
$result = mysqli_query($con, $sql);
if (!$result || mysqli_num_rows($result) == 0) {
header("Location: index.php");
exit();
}
$property = mysqli_fetch_assoc($result);
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
if (isset($_POST['room_type'])) {
// First step: Room type selection
$room_type = $_POST['room_type'];
$next_step = 'ac_selection';
} elseif (isset($_POST['ac_type'])) {
// Second step: AC selection
$ac_type = $_POST['ac_type'];
$room_type = $_POST['hidden_room_type'];
$next_step = 'final_details';
} else {
// Final step: Booking submission
$check_in = $_POST['check_in'];
$duration = $_POST['duration'];
$special_requests = mysqli_real_escape_string($con, $_POST['special_requests']);
$room_type = $_POST['hidden_room_type'];
$ac_type = $_POST['hidden_ac_type'];
// Calculate price based on selections
$base_price = $property['rent'];
$price_multiplier = 1.0;
if ($room_type == 'double') {
$price_multiplier = 0.8;
} elseif ($room_type == 'triple') {
$price_multiplier = 0.6;
}
if ($ac_type == 'ac') {
$price_multiplier *= 1.3;
}
$final_price = $base_price * $price_multiplier;
// Insert booking into database
$insert_sql = "INSERT INTO bookings (user_id, property_id, check_in_date,
duration_months, special_requests, room_type, ac_type, price, status)
VALUES ($user_id, $property_id, '$check_in', $duration, '$special_requests', '$room_type',
'$ac_type', $final_price, 'pending')";
if (mysqli_query($con, $insert_sql)) {
$success = "Booking request submitted successfully!";
} else {
$error = "Error: " . mysqli_error($con);
}
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Book <?php echo $property['name']; ?> | PG Life</title>
<link href="css/bootstrap.min.css" rel="stylesheet" />
<link href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" rel="stylesheet" />
<link
href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,
700;0,800;1,300;1,400;1,600;1,700;1,800&display=swap" rel="stylesheet" />
<link href="css/common.css" rel="stylesheet" />
<style>
.booking-step {
display: none;
}
.booking-step.active {
display: block;
animation: fadeIn 0.5s;
}
.room-option, .ac-option {
cursor: pointer;
transition: all 0.3s;
margin-bottom: 15px;
border: 2px solid #ddd;
border-radius: 8px;
padding: 20px;
text-align: center;
}
.room-option:hover, .ac-option:hover {
border-color: #007bff;
background-color: #f8f9fa;
}
.room-option.selected, .ac-option.selected {
border-color: #28a745;
background-color: #e8f5e9;
}
.room-option i, .ac-option i {
font-size: 2.5rem;
margin-bottom: 10px;
color: #6c757d;
}
.room-option.selected i, .ac-option.selected i {
color: #28a745;
}
.price-highlight {
font-size: 1.2rem;
font-weight: bold;
color: #dc3545;
}
@keyframes fadeIn {
from { opacity: 0; }
to { opacity: 1; }
}
</style>
</head>
<body>
<?php require "./includes/header.php"; ?>
<div class="page-container">
<h1>Book <?php echo $property['name']; ?></h1>
<?php if (isset($success)): ?>
<div class="alert alert-success"><?php echo $success; ?></div>
<p>We'll contact you shortly to confirm your booking.</p>
<a href="property_detail.php?property_id=<?php echo $property_id; ?>" class="btn btnprimary">Back to Property</a>
<?php else: ?>
<div class="booking-process">
<!-- Step 1: Room Type Selection -->
<div class="booking-step <?php echo (!isset($next_step) || $next_step == 'ac_selection') ?
'active' : ''; ?>" id="step-room-type">
<h3>Select Room Type</h3>
<div class="row">
<div class="col-md-4">
<div class="room-option" data-type="single">
<i class="fas fa-bed"></i>
<h4>Single Room</h4>
<p>Private room with single bed</p>
<div class="price-highlight">Rs. <?php echo $property['rent']; ?>/month</div>
</div>
</div>
<div class="col-md-4">
<div class="room-option" data-type="double">
<i class="fas fa-bed"></i>
<h4>Double Room</h4>
<p>Room with double bed (0.8x price)</p>
<div class="price-highlight">Rs. <?php echo $property['rent'] * 0.8; ?>/month</div>
</div>
</div>
<div class="col-md-4">
<div class="room-option" data-type="triple">
<i class="fas fa-bed"></i>
<h4>Triple Sharing</h4>
<p>Room with three beds (0.6x price)</p>
<div class="price-highlight">Rs. <?php echo $property['rent'] * 0.6; ?>/month</div>
</div>
</div>
</div>
<form method="POST" action="booking.php?property_id=<?php echo $property_id; ?>"
id="room-type-form">
<input type="hidden" name="room_type" id="selected-room-type">
<div class="mt-3">
<button type="button" class="btn btn-secondary"
onclick="window.location.href='property_detail.php?property_id=<?php echo
$property_id; ?>'">Cancel</button>
<button type="submit" class="btn btn-primary" id="room-type-next"
disabled>Next</button>
</div>
</form>
</div>
<!-- Step 2: AC Selection -->
<?php if (isset($next_step) && $next_step == 'ac_selection'): ?>
<div class="booking-step active" id="step-ac-type">
<h3>Select AC Preference</h3>
<div class="row">
<div class="col-md-6">
<div class="ac-option" data-type="ac">
<i class="fas fa-snowflake"></i>
<h4>AC Room</h4>
<p>Air conditioned room (1.3x price)</p>
<div class="price-highlight">
Rs. <?php
$multiplier = ($room_type == 'double') ? 0.8 : ($room_type == 'triple' ? 0.6 : 1.0);
echo $property['rent'] * $multiplier * 1.3;
?>/month
</div>
</div>
</div>
<div class="col-md-6">
<div class="ac-option" data-type="non-ac">
<i class="fas fa-fan"></i>
<h4>Non-AC Room</h4>
<p>Regular room with fan</p>
<div class="price-highlight">
Rs. <?php
$multiplier = ($room_type == 'double') ? 0.8 : ($room_type == 'triple' ? 0.6 : 1.0);
echo $property['rent'] * $multiplier;
?>/month
</div>
</div>
</div>
</div>
<form method="POST" action="booking.php?property_id=<?php echo $property_id; ?>"
id="ac-type-form">
<input type="hidden" name="ac_type" id="selected-ac-type">
<input type="hidden" name="hidden_room_type" value="<?php echo $room_type; ?>">
<div class="mt-3">
<button type="button" class="btn btn-secondary" id="ac-type-back">Back</button>
<button type="submit" class="btn btn-primary" id="ac-type-next" disabled>Next</button>
</div>
</form>
</div>
<?php endif; ?>
<!-- Step 3: Final Details -->
<?php if (isset($next_step) && $next_step == 'final_details'): ?>
<div class="booking-step active" id="step-final-details">
<h3>Complete Your Booking</h3>
<div class="booking-summary mb-4 p-3 bg-light rounded">
<h4>Booking Summary</h4>
<p><strong>Property:</strong> <?php echo $property['name']; ?></p>
<p><strong>Room Type:</strong> <?php echo ucfirst($room_type); ?> <?php echo
ucfirst($ac_type); ?></p>
<p><strong>Monthly Price:</strong> Rs.
<?php
$multiplier = 1.0;
if ($room_type == 'double') $multiplier = 0.8;
elseif ($room_type == 'triple') $multiplier = 0.6;
if ($ac_type == 'ac') $multiplier *= 1.3;
echo $property['rent'] * $multiplier;
?>
</p>
</div>
<form method="POST" action="booking.php?property_id=<?php echo $property_id; ?>">
<input type="hidden" name="hidden_room_type" value="<?php echo $room_type; ?>">
<input type="hidden" name="hidden_ac_type" value="<?php echo $ac_type; ?>">
<div class="form-group">
<label for="check_in">Check-in Date</label>
<input type="date" class="form-control" id="check_in" name="check_in" required
min="<?php echo date('Y-m-d'); ?>">
</div>
<div class="form-group">
<label for="duration">Duration (months)</label>
<select class="form-control" id="duration" name="duration" required>
<option value="1">1 Month</option>
<option value="3">3 Months</option>
<option value="6">6 Months</option>
<option value="12">12 Months</option>
</select>
</div>
<div class="form-group">
<label for="special_requests">Special Requests</label>
<textarea class="form-control" id="special_requests" name="special_requests"
rows="3"></textarea>
</div>
<?php if (isset($error)): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<div class="mt-3">
<button type="button" class="btn btn-secondary" id="final-details-back">Back</button>
<button type="submit" class="btn btn-primary">Confirm Booking</button>
</div>
</form>
</div>
<?php endif; ?>
</div>
<?php endif; ?>
</div>
<?php require "./includes/footer.php"; ?>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script>
$(document).ready(function() {
// Room type selection
$('.room-option').click(function() {
$('.room-option').removeClass('selected');
$(this).addClass('selected');
var roomType = $(this).data('type');
$('#selected-room-type').val(roomType);
$('#room-type-next').prop('disabled', false);
});
// AC type selection
$('.ac-option').click(function() {
$('.ac-option').removeClass('selected');
$(this).addClass('selected');
var acType = $(this).data('type');
$('#selected-ac-type').val(acType);
$('#ac-type-next').prop('disabled', false);
});
// Back button for AC selection
$('#ac-type-back').click(function() {
window.location.href = "booking.php?property_id=<?php echo $property_id; ?>";
});
// Back button for final details
$('#final-details-back').click(function() {
// Submit form to go back to AC selection while preserving room type
$('<input>').attr({
type: 'hidden',
name: 'room_type',
value: '<?php echo isset($room_type) ? $room_type : ""; ?>'
}).appendTo('#ac-type-form');
$('#ac-type-form').submit();
});
});
</script>
</body>
</html>

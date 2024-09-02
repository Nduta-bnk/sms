<?php
session_start();
require_once 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    die('You must be logged in to view the confirmation page.');
}

$username = $_SESSION['username'];

// Fetch the user's name from the database
$sql = "SELECT full_name FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('User not found.');
}

$user = $result->fetch_assoc();
$full_name = htmlspecialchars($user['full_name']);

// Retrieve the event name, number of tickets, and total price from the query string
if (!isset($_GET['event_id'])) {
    die('Event ID is missing.');
} else if (!isset($_GET['number_of_tickets'])) {
    die('Number of tickets is missing.');
}else if (!isset($_GET['total_amount_ksh'])) {
    die('Total Amount is missing.');
}

$event_id = intval($_GET['event_id']);
$number_of_tickets = intval($_GET['number_of_tickets']);
$total_amount_ksh = floatval($_GET['total_amount_ksh']);

// Fetch the event name from the database
$sql = "SELECT event_name FROM events WHERE event_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $event_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('Event not found.');
}

$event = $result->fetch_assoc();
$event_name = htmlspecialchars($event['event_name']);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Confirmation</title>
  <style>
body {
    background-image: url('/nyayo.png'); /* Path to your image */
    background-size: cover; /* Ensure the image covers the whole background */
    background-repeat: no-repeat; /* Prevent repeating the image */
    background-attachment: fixed; /* Keep the image fixed during scrolling */
    background-position: center; /* Center the image */
    font-family: Arial, sans-serif;
    background-color: #f0f2f5;
    margin: 0;
    padding: 0;
    color: white;
}

header {
    width: 100%;
    background-color: #2c3e50;
    color: white;
    padding: 20px;
    text-align: center;
}

header h1 {
    margin: 0;
    font-size: 24px;
}

h2 {
    margin: 0;
    font-size: 1.5em;
    color: #1abc9c;
}

h3 {
    margin: 0;
    font-size: 1.5em;
    color: #1abc9c;
    text-align: center;
}

.content {
    max-width: 400px;
    margin: 40px auto;
    padding: 20px;
    background: rgba(0, 0, 0, 0.4);
    border-radius: 8px;
    box-shadow: 0 15px 25px rgba(0, 0, 0, 0.5);
}

h3 {
    margin-top: 0;
    color: white;
}

p {
    font-size: 16px;
    color: white;
    text-align: center; /* Center-align the text */
    margin: 20px 0; /* Add some margin for spacing */
}

/* Link Styles */
p a {
    color: #2196f3; /* Blue color for the link */
    text-decoration: none; /* Remove underline */
    font-weight: bold; /* Make the link bold */
    transition: color 0.3s; /* Smooth transition for color change */
}

p a:hover {
    color: #0d47a1; /* Darker blue for hover effect */
}

footer {
    width: 100%;
    text-align: center;
    padding: 10px 0;
    background-color: #2c3e50;
    color: white;
    position: fixed;
    bottom: 0;
}

footer p {
    margin: 0;
    font-size: 14px;
}

/* Responsive Design */
@media (max-width: 600px) {
    header h1 {
        font-size: 1.5em;
    }
    
    h2 {
        font-size: 1.2em;
    }
    
    .section-content {
        padding: 10px;
    }
}
  </style>
</head>
<body>
<header>
    <h1>Nyayo National Stadium</h1>
    <h2>Payment Confirmation</h2>
</header>
  <div class="content">
    <h3>Thank You for Your Purchase, <?php echo $username; ?>!</h3>
    <p>Event: <?php echo $event_name; ?></p>
    <p>Number of Tickets: <?php echo $number_of_tickets; ?></p>
    <p>Total Amount (KSh): <?php echo number_format($total_amount_ksh); ?></p>
    
    <p>Your payment has been successfully processed.</p>
    
    <p><a href="customer.php">Back to Events</a></p>
</div>
  <footer>
    <p>2024 Nyayo National Stadium</p>
  </footer>
</body>
</html>
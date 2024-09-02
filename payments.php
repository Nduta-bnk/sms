<?php
session_start();
require_once 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    die('You must be logged in to make a purchase.');
}

$username = $_SESSION['username'];

// Fetch the user_id from the users table using the username
$sql = "SELECT user_id FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('User not found.');
}

$user = $result->fetch_assoc();
$user_id = $user['user_id'];

// Check if event_id, number_of_tickets, and total_price are provided
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

// Handle payment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_method = $_POST['payment_method'];
    $status = 'Completed'; // Set the payment status accordingly
    
    // Retrieve the latest purchase_id from the purchases table
    $sql = "SELECT purchase_id FROM purchases WHERE user_id = ? AND event_id = ? ORDER BY purchase_date DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $user_id, $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        die('No purchase record found.');
    }
    
    $purchase = $result->fetch_assoc();
    $purchase_id = $purchase['purchase_id'];

    // Insert payment details into the payments table
    $sql = "INSERT INTO payments (user_id, purchase_id, total_amount_ksh, payment_date, payment_method, status) VALUES (?, ?, ?, NOW(), ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iidss', $user_id, $purchase_id, $total_amount_ksh, $payment_method, $status);
    $stmt->execute();

    // Redirect to confirmation.php with relevant data
    header('Location: confirmation.php?event_id=' . urlencode($event_id) . '&number_of_tickets=' . urlencode($number_of_tickets) . '&total_amount_ksh=' . urlencode($total_amount_ksh));
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Payments</title>
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

form {
    display: flex;
    flex-direction: column;
}

.form-group {
    margin-bottom: 15px;
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: white;
}

select {
    width: 100%;
    padding: 10px;
    font-size: 14px;
    color: #333;
    background-color: #f8f8f8;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
}

.select-container {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1em;
    width: 100%;
    box-sizing: border-box;
}

button {
    background-color: #1abc9c;
    color: white;
    border: none;
    padding: 12px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s;
    margin-top: 10px;
}

button:hover {
    background-color: #16a085;
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
    
    button {
        font-size: 14px;
        padding: 10px;
    }

    a {
        text-decoration: none;
        color: #e3f2fd;
        transition: color 0.3s;
    }

    a:hover {
        color: #e3f2fd;
    }
}
</style>
</head>
<body>
<header>
    <h1>Nyayo National Stadium</h1>
    <h2>Payment for Event</h2>
</header>
<div class="content">
    <h3>Order Summary</h3>
    <p>Number of Tickets: <?php echo htmlspecialchars($number_of_tickets); ?></p>
    <p>Total Amount (KSh): KSh <?php echo htmlspecialchars($total_amount_ksh); ?></p>
    
    <!-- Payment Form -->
    <form method="POST">
    <div class="form-group">
        <label for="payment_method">Select Payment Method:</label><br>
            <div class="select-container">
                <select id="payment_method" name="payment_method" required>
                <option value="credit_card">Credit Card</option>
                <option value="mpesa">Mpesa</option>
                </select>
            </div>
    </div>
        <button type="submit">Confirm Payment</button>
    </form>

    <p><a href="customer.php">Back to Events</a></p>
</div>
<footer>
    <p>2024 Nyayo National Stadium</p>
</footer>
</body>
</html>
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

// Validate the event_id from GET
if (!isset($_GET['event_id']) || !is_numeric($_GET['event_id'])) {
    die('Invalid or missing event ID.');
}

$event_id = intval($_GET['event_id']);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate number_of_tickets from POST
    if (!isset($_POST['number_of_tickets']) || !is_numeric($_POST['number_of_tickets'])) {
        die('Invalid or missing number of tickets.');
    }

    $number_of_tickets = intval($_POST['number_of_tickets']);

    // Fetch event details
    $sql = "SELECT ticket_price_ksh, available_seats FROM events WHERE event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die('Event not found.');
    }

    $event = $result->fetch_assoc();

    // Store the ticket price in a variable
    $ticket_price_ksh = $event['ticket_price_ksh'];

    // Validate ticket availability
    if ($number_of_tickets <= 0 || $number_of_tickets > $event['available_seats']) {
        die('Invalid number of tickets or not enough seats available.');
    }

    // Calculate total amount
    $total_amount_ksh = $number_of_tickets * $event['ticket_price_ksh'];

    // Insert into purchases table
    $sql = "INSERT INTO purchases (event_id, user_id, number_of_tickets, total_amount_ksh, purchase_date) 
            VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iiid', $event_id, $user_id, $number_of_tickets, $total_amount_ksh);
    $stmt->execute();
    $purchase_id = $stmt->insert_id;

    // Fetch current seat numbers
    $sql = "SELECT MAX(seat_number) AS last_seat_number FROM tickets WHERE event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $last_seat_number = $result->fetch_assoc()['last_seat_number'];
    if ($last_seat_number === null) {
        $last_seat_number = 0;
    }

    // Insert into tickets table
    for ($i = 0; $i < $number_of_tickets; $i++) {
        $seat_number = $last_seat_number + $i + 1;  // Start from the next seat number
        $ticket_number = $seat_number;  // Set ticket_number equal to seat_number

        $sql = "INSERT INTO tickets (purchase_id, event_id, user_id, ticket_price_ksh, ticket_number, seat_number, purchase_date) 
                VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iiidis', $purchase_id, $event_id, $user_id, $ticket_price_ksh, $ticket_number, $seat_number);
        $stmt->execute();
    }

    // Update available seats
    $new_available_seats = $event['available_seats'] - $number_of_tickets;
    $sql = "UPDATE events SET available_seats = ? WHERE event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $new_available_seats, $event_id);
    $stmt->execute();

    // Redirect to the payment page with event_id and number_of_tickets
    header("Location: payments.php?event_id=$event_id&number_of_tickets=$number_of_tickets&total_amount_ksh=$total_amount_ksh");
    exit;
} else {
    // Fetch event details to display
    $sql = "SELECT event_name, event_description, event_date, event_start_time, event_end_time, ticket_price_ksh, available_seats FROM events WHERE event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die('Event not found.');
    }

    $event = $result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Tickets</title>
    <style>
        /* Styling as provided */
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
            color: #333;
        }

        header {
            width: 100%;
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
        }

        h1 {
            margin: 0;
            font-size: 24px;
        }

        .content {
            max-width: 400px;
            margin: 40px auto;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.4);
            border-radius: 8px;
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.5);
        }

        h2 {
            margin: 0;
            font-size: 1.5em;
            color: #1abc9c;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
            color: white;
            display: block;
            margin-bottom: 5px;
        }

        input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 1em;
        }

        input[type="number"]:focus {
            border-color: #1abc9c;
            outline: none;
        }

        .number_of_tickets-container {
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
            text-align: center;
            margin: 20px 0;
        }

        p a {
            color: #2196f3;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
        }

        p a:hover {
            color: #0d47a1;
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
    </style>
</head>
<body>
<header>
    <h1>Nyayo National Stadium</h1>
    <h2>Purchase Tickets for <?php echo htmlspecialchars($event['event_name']); ?></h2>
</header>
<section class="content">
    
    <form method="POST">
        <div class="form-group">
            <div class="number_of_tickets-container">
                <input type="number" id="number_of_tickets" name="number_of_tickets" placeholder="Number of Tickets" min="1" max="<?php echo htmlspecialchars($event['available_seats']); ?>" required>
            </div>
        </div>

        <button type="submit">Proceed to Payment</button>
    </form>

    <p><a href="customer.php">Back to Events</a></p>
</section>
<footer>
    <p>2024 Nyayo National Stadium</p>
</footer>
</body>
</html>
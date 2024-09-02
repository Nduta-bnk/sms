<?php
// receipt.php

// Include database connection file
require 'config.php';

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    die('You must be logged in to view your receipt.');
}

// Fetch the user_id from the users table using the username
$username = $_SESSION['username'];
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

// Get the purchase ID from URL
$purchase_id = isset($_GET['purchase_id']) ? intval($_GET['purchase_id']) : 0;

// Fetch purchase details
$sql = "SELECT p.purchase_id, p.event_id, p.user_id, p.number_of_tickets, p.total_amount_ksh, p.purchase_date,
               e.event_name, e.event_date, e.event_start_time, e.event_end_time, e.ticket_price_ksh
        FROM purchases p
        JOIN events e ON p.event_id = e.event_id
        WHERE p.purchase_id = ? AND p.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $purchase_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('Purchase not found.');
}

$purchase = $result->fetch_assoc();

// Fetch ticket details
$sql_tickets = "SELECT ticket_id, ticket_number, seat_number
                FROM tickets
                WHERE purchase_id = ?";
$stmt_tickets = $conn->prepare($sql_tickets);
$stmt_tickets->bind_param('i', $purchase_id);
$stmt_tickets->execute();
$result_tickets = $stmt_tickets->get_result();

$tickets = $result_tickets->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            width: 100%;
            max-width: 900px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #1e88e5;
        }
        .details {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #fafafa;
        }
        .details p {
            margin: 5px 0;
            font-size: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
            color: #333;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .print-button {
            text-align: center;
        }
        .print-button button {
            background-color: #1e88e5;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .print-button button:hover {
            background-color: #155a9e;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Purchase Receipt</h1>
        </div>
        <div class="details">
            <p><strong>Event:</strong> <?php echo htmlspecialchars($purchase['event_name']); ?></p>
            <p><strong>Date:</strong> <?php echo htmlspecialchars($purchase['event_date']); ?></p>
            <p><strong>Start Time:</strong> <?php echo htmlspecialchars($purchase['event_start_time']); ?></p>
            <p><strong>End Time:</strong> <?php echo htmlspecialchars($purchase['event_end_time']); ?></p>
            <p><strong>Total Tickets:</strong> <?php echo htmlspecialchars($purchase['number_of_tickets']); ?></p>
            <p><strong>Total Amount:</strong> KSh <?php echo htmlspecialchars($purchase['total_amount_ksh']); ?></p>
            <p><strong>Purchase Date:</strong> <?php echo htmlspecialchars($purchase['purchase_date']); ?></p>
        </div>
        <h2>Tickets</h2>
        <table>
            <thead>
                <tr>
                    <th>Ticket Number</th>
                    <th>Seat Number</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tickets as $ticket): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($ticket['ticket_number']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['seat_number']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="print-button">
            <button onclick="window.print()">Print Receipt</button>
        </div>
    </div>
</body>
</html>
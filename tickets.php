<?php
// customer_dashboard.php

// Include database connection file
require 'config.php';

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    die('You must be logged in to view your purchase history.');
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

// Fetch purchase history
$sql_purchases = "SELECT p.purchase_id, p.event_id, p.number_of_tickets, p.total_amount_ksh, p.purchase_date,
                          e.event_name, e.event_date
                   FROM purchases p
                   JOIN events e ON p.event_id = e.event_id
                   WHERE p.user_id = ?
                   ORDER BY p.purchase_date DESC";
$stmt_purchases = $conn->prepare($sql_purchases);
$stmt_purchases->bind_param('i', $user_id);
$stmt_purchases->execute();
$result_purchases = $stmt_purchases->get_result();

$purchases = $result_purchases->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchases</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 900px;
            margin: 20px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #1e88e5;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
            color: #333;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        a {
            color: #1e88e5;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
        td a {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 4px;
            background-color: #e3f2fd;
            color: #1e88e5;
            font-size: 14px;
        }
        td a:hover {
            background-color: #c5e1f5;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Purchase History</h1>
        <table>
            <thead>
                <tr>
                    <th>Event Name</th>
                    <th>Date</th>
                    <th>Total Tickets</th>
                    <th>Total Amount</th>
                    <th>Purchase Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($purchases as $purchase): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($purchase['event_name']); ?></td>
                        <td><?php echo htmlspecialchars($purchase['event_date']); ?></td>
                        <td><?php echo htmlspecialchars($purchase['number_of_tickets']); ?></td>
                        <td>KSh <?php echo htmlspecialchars($purchase['total_amount_ksh']); ?></td>
                        <td><?php echo htmlspecialchars($purchase['purchase_date']); ?></td>
                        <td>
                            <a href="receipt.php?purchase_id=<?php echo htmlspecialchars($purchase['purchase_id']); ?>" target="_blank">View Receipt</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
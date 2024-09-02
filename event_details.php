<?php
session_start();
require_once 'config.php';

// Check if event_id is provided in the URL
if (isset($_GET['event_id'])) {
    $event_id = intval($_GET['event_id']); // Sanitize the input

    // Prepare and execute the SQL query to fetch the specific event
    $stmt = $conn->prepare("SELECT * FROM events WHERE event_id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch the event details
    $event = $result->fetch_assoc();
} else {
    // Handle the case where no event_id is provided
    echo "<p>No event selected.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Details</title>
    <style>
        body {
    background-image: url('/nyayo.png'); /* Path to your image */
    background-size: cover; /* Ensure the image covers the whole background */
    background-repeat: no-repeat; /* Prevent repeating the image */
    background-attachment: fixed; /* Keep the image fixed during scrolling */
    background-position: center; /* Center the image */
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f0f2f5;
    color: #333;
}

/* Header Styles */
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

header h2 {
    margin: 0;
    font-size: 1.5em;
}

/* Main Content */
.main-content {
    padding: 20px;
    max-width: 400px;
    color: white;
    margin: 40px auto;
    text-align: center;
}

.card {
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    max-width: 280px; /* Further reduced width */
    margin: 20px auto;
    background-color: #fff;
    overflow: hidden; /* Ensures no overflow issues */
}

.card-header {
    padding: 10px;
    border-bottom: 1px solid #ddd;
    background-color: #008080; /* Using a primary color for header */
    color: #333;
    text-align: center;
}

.card-header h2 {
    margin: 0;
    font-size: 1.2em;
}

.card-body {
    padding: 15px; /* Slightly increased padding for better spacing */
}

.event-info h3 {
    margin: 8px 0 4px; /* Adjusted margins for a compact look */
    font-size: 0.9em; /* Slightly smaller font size */
    color: #333;
}

.event-info p {
    margin: 0 0 10px; /* Adjusted margins for better readability */
    font-size: 0.85em; /* Smaller font size for compactness */
    color: #555;
}

.price {
    font-weight: bold;
    color: #d32f2f; /* Using an accent color for price */
}

.buy-btn {
    display: block;
    width: calc(100% - 20px); /* Full width minus padding */
    margin: 10px auto 0;
    padding: 8px 0; /* Increased height for easier click */
    font-size: 0.9em;
    color: white;
    background-color: #1abc9c; /* Changed to a green color */
    text-align: center;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s; /* Smooth transition effect */
}

.buy-btn:hover {
    background-color: #16a085; /* green for hover effect */
}

/* Footer Styles */
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
@media (max-width: 768px) {
    .main-content {
        padding: 10px;
    }
}
    </style>
</head>
<body>
    <header>
        <h1>Nyayo National Stadium</h1>
        <h2><?php echo htmlspecialchars($event['event_name']); ?></h2>
    </header>
    <div class="main-content">
    <div class="card">
    <div class="card-header">
        <h2>Event Details</h2>
    </div>
    <div class="card-body">
        <div class="event-info">
            <h3>Description:</h3>
            <p><?php echo htmlspecialchars($event['event_description']); ?></p>
            <h3>Date:</h3>
            <p><?php echo htmlspecialchars($event['event_date']); ?></p>
            <h3>Time:</h3>
            <p><?php echo htmlspecialchars($event['event_start_time']); ?> - <?php echo htmlspecialchars($event['event_end_time']); ?></p>
            <h3>Total Seats:</h3>
            <p><?php echo htmlspecialchars($event['total_seats']); ?></p>
            <h3>Available Seats:</h3>
            <p><?php echo htmlspecialchars($event['available_seats']); ?></p>
            <h3 class="price">Ticket Price:</h3>
            <p class="price">Ksh <?php echo htmlspecialchars($event['ticket_price_ksh']); ?></p>
            <a class="buy-btn" href="purchase.php?event_id=<?php echo htmlspecialchars($event['event_id']); ?>">Buy Tickets</a>
        </div>
    </div>
</div>
    </div>
    <footer>
        <p>2024 Nyayo National Stadium</p>
    </footer>
</body>
</html>
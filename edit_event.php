<?php
session_start();

require_once 'config.php'; // Include database connection

// Get event ID from URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Invalid event ID.";
    exit;
}

$event_id = $_GET['id'];

// Fetch event details from the database
$stmt = $conn->prepare("SELECT * FROM events WHERE event_id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Event not found.";
    exit;
}

$event = $result->fetch_assoc();
$stmt->close();

// Handle form submission for updating the event
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_name = $_POST['event_name'];
    $event_description = $_POST['event_description'];
    $event_date = $_POST['event_date'];
    $event_start_time = $_POST['event_start_time'];
    $event_end_time = $_POST['event_end_time'];
    $total_seats = $_POST['total_seats'];
    $available_seats = $_POST['available_seats'];
    $ticket_price_ksh = $_POST['ticket_price_ksh'];

    // Update the event in the database
    $stmt = $conn->prepare("UPDATE events SET event_name = ?, event_description = ?, event_date = ?, event_start_time = ?, event_end_time = ?, total_seats = ?, available_seats = ?, ticket_price_ksh = ? WHERE event_id = ?");
    $stmt->bind_param("sssssiidi", $event_name, $event_description, $event_date, $event_start_time, $event_end_time, $total_seats, $available_seats, $ticket_price_ksh, $event_id);
    $stmt->execute();
    $stmt->close();

    echo "Event updated successfully.";
    header("Location: manage_events.php");
    exit;
}

$conn->close();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Edit Event</title>
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

header {
    width: 100%;
    background-color: #2c3e50;
    color: white;
    padding: 20px;
    text-align: center;
}

header h1 {
    margin: 0;
    font-size: 2em;
}

nav ul {
    list-style: none;
    padding: 0;
    margin: 0;
    text-align: center;
}

nav ul li {
    margin: 0;
}

nav ul a {
    color: #fff;
    text-decoration: none;
    font-weight: bold;
    padding: 10px 15px;
    display: inline-block;
    transition: background 0.3s ease, border-radius 0.3s ease;
    border-radius: 5px;
}

nav ul a:hover {
    background: #0056b3;
}
/* Form Section Styles */
form {
    padding: 20px;
    max-width: 400px;
    margin: 40px auto;
    background: white;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h2 {
    margin: 0;
    font-size: 1.5em;
    color: #1abc9c;
    text-align: center;
}

form {
    display: flex;
    flex-direction: column;
}

.form-group {
    margin-bottom: 15px;
}

form label {
    display: block;
    font-weight: bold;
    color: #333;
    margin-bottom: 5px;
}

form input, form textarea {
    width: 100%;
    padding: 10px;
    box-sizing: border-box;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1em;
}

form textarea {
    height: 50px;
}

form button {
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

form button:hover {
    background: #16a085;
}

/* Table Styles */
table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    font-size: 16px;
    text-align: center;
    background: white;
}

/* Table header styling */
table thead tr {
    background-color: #00796b; /* Teal for headers */
    color: #ffffff;
    text-align: left;
    font-weight: bold;
}

/* Table header cells */
table th, table td {
    padding: 12px 15px;
    border: 1px solid #e0e0e0; /* Light gray border */
}

/* Zebra striping for table rows */
table tbody tr:nth-of-type(even) {
    background-color: #fafafa; /* Light gray stripe */
}

/* Hover effect for table rows */
table tbody tr:hover {
    background-color: #f1f8f4; /* Very light teal */
}

/* Table cell content alignment */
table td {
    vertical-align: middle;
}

/* Specific column width (optional) */
table th.event-name, table td.event-name {
    width: 30%;
}

table th.event-description, table td.event-description {
    width: 40%;
}

table th.event-date, table td.event-date {
    width: 10%;
}

table th.start-time, table td.start-time,
table th.end-time, table td.end-time {
    width: 10%;
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
@media (max-width: 600px) {
    header h1 {
        font-size: 1.5em;
    }
    
    header h2 {
        font-size: 1.2em;
    }
    
    section.content {
        padding: 10px;
    }
    
    button {
        font-size: 14px;
        padding: 10px;
    }
}
  </style>
</head>
<body>
<header>
    <h1>Edit Event</h1>
    <nav>
        <ul>
<a href="manage_events.php">Back to Manage Events</a>
        </ul>
    </nav>
</header>
<section>
    <form action="edit_event.php?id=<?php echo htmlspecialchars($event_id); ?>" method="post">
        <label for="event_name">Event Name:</label><br>
        <input type="text" id="event_name" name="event_name" value="<?php echo htmlspecialchars($event['event_name']); ?>" required><br>

        <label for="event_description">Event Description:</label><br>
        <textarea id="event_description" name="event_description" required><?php echo htmlspecialchars($event['event_description']); ?></textarea><br>

        <label for="event_date">Event Date:</label><br>
        <input type="date" id="event_date" name="event_date" value="<?php echo htmlspecialchars($event['event_date']); ?>" required><br>

        <label for="event_start_time">Start Time:</label><br>
        <input type="time" id="event_start_time" name="event_start_time" value="<?php echo htmlspecialchars($event['event_start_time']); ?>" required><br>

        <label for="event_end_time">End Time:</label><br>
        <input type="time" id="event_end_time" name="event_end_time" value="<?php echo htmlspecialchars($event['event_end_time']); ?>" required><br>

        <label for="total_seats">Total Seats:</label><br>
        <input type="number" id="total_seats" name="total_seats" value="<?php echo htmlspecialchars($event['total_seats']); ?>" required><br>

        <label for="available_seats">Available Seats:</label><br>
        <input type="number" id="available_seats" name="available_seats" value="<?php echo htmlspecialchars($event['available_seats']); ?>" required><br>

        <label for="ticket_price_ksh">Ticket Price (Ksh):</label><br>
        <input type="number" id="ticket_price_ksh" name="ticket_price_ksh" value="<?php echo htmlspecialchars($event['ticket_price_ksh']); ?>" required><br>

        <button type="submit">Update Event</button>
    </form>
</section>
<footer>
    <p>2024 Nyayo National Stadium</p>
</footer>
</body>
</html>
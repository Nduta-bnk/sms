<?php
session_start();

require_once 'config.php'; // Include database connection

// Handling form submission for adding an event
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_name = $_POST['event_name'];
    $event_description = $_POST['event_description'];
    $event_date = $_POST['event_date'];
    $event_start_time = $_POST['event_start_time'];
    $event_end_time = $_POST['event_end_time'];
    $total_seats = $_POST['total_seats'];
    $available_seats = $_POST['available_seats'];
    $ticket_price_ksh = $_POST['ticket_price_ksh'];

    // Insert the event into the database
    $stmt = $conn->prepare("INSERT INTO events (event_name, event_description, event_date, event_start_time, event_end_time, total_seats, available_seats, ticket_price_ksh) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssiid", $event_name, $event_description, $event_date, $event_start_time, $event_end_time, $total_seats, $available_seats, $ticket_price_ksh);
    $stmt->execute();
    $stmt->close();
}

// Fetch events from the database
$result = $conn->query("SELECT * FROM events");

$conn->close();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Manage Events</title>
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
    background: rgba(0, 0, 0, 0.4);
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
    color: white;
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
    <h1>Manage Events</h1>
    <nav>
        <ul>
            <li><a href="admin.php">Back to Admin Panel</a></li>
        </ul>
    </nav>
</header>
<section>
    <h2>Add New Event</h2>
    <form action="manage_events.php" method="post">
        <label for="event_name">Event Name:</label><br>
        <input type="text" id="event_name" name="event_name" required><br>

        <label for="event_description">Event Description:</label><br>
        <textarea id="event_description" name="event_description" required></textarea><br>

        <label for="event_date">Event Date:</label><br>
        <input type="date" id="event_date" name="event_date" required><br>

        <label for="event_start_time">Start Time:</label><br>
        <input type="time" id="event_start_time" name="event_start_time" required><br>

        <label for="event_end_time">End Time:</label><br>
        <input type="time" id="event_end_time" name="event_end_time" required><br>

        <label for="total_seats">Total Seats:</label><br>
        <input type="number" id="total_seats" name="total_seats" required><br>

        <label for="available_seats">Available Seats:</label><br>
        <input type="number" id="available_seats" name="available_seats" required><br>

        <label for="ticket_price_ksh">Ticket Price (Ksh):</label><br>
        <input type="number" id="ticket_price_ksh" name="ticket_price_ksh" required><br>

        <button type="submit">Add Event</button>
    </form>

    <h2>Current Events</h2>
    <table>
        <thead>
            <tr>
                <th>Event Name</th>
                <th>Description</th>
                <th>Date</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Total Seats</th>
                <th>Available Seats</th>
                <th>Ticket Price (Ksh)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Display events from the database
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['event_name']}</td>
                    <td>{$row['event_description']}</td>
                    <td>{$row['event_date']}</td>
                    <td>{$row['event_start_time']}</td>
                    <td>{$row['event_end_time']}</td>
                    <td>{$row['total_seats']}</td>
                    <td>{$row['available_seats']}</td>
                    <td>{$row['ticket_price_ksh']}</td>
                    <td>
                        <a href='edit_event.php?id={$row['event_id']}'>Edit</a> | 
                        <a href='delete_event.php?id={$row['event_id']}'>Delete</a>
                    </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</section>
<footer>
    <p>2024 Nyayo National Stadium</p>
</footer>
</body>
</html>
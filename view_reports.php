<?php
session_start();
require_once 'config.php'; // Include the config file to initialize $conn

// Fetch event data from the events table
$sql = "SELECT event_id, event_name FROM events";
$result = $conn->query($sql);
$events = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Event</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        h1 {
            color: #0056b3;
        }
        form {
            margin-top: 20px;
        }
        label, select, button {
            margin: 10px 0;
        }
    </style>
</head>
<body>

    <h1>Select Event to Generate Report</h1>
    <form action="generate_report.php" method="get">
        <label for="event_id">Select Event:</label>
        <select name="event_id" id="event_id" required>
            <?php foreach ($events as $event): ?>
                <option value="<?php echo htmlspecialchars($event['event_id']); ?>">
                    <?php echo htmlspecialchars($event['event_name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Generate Report</button>
    </form>

</body>
</html>
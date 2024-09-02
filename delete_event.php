<?php
session_start();

require_once 'config.php'; // Include database connection

// Get event ID from URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Invalid event ID.";
    exit;
}

$event_id = $_GET['id'];

// Delete the event from the database
$stmt = $conn->prepare("DELETE FROM events WHERE event_id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$stmt->close();

echo "Event deleted successfully.";
header("Location: manage_events.php");
exit;

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Details</title>
    
</head>
<body>
</body>
</html>
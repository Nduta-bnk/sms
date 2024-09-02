<?php
session_start();
require_once 'config.php';

$sql = "SELECT * FROM events";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>
</head>
<body>
    <header>
        <h1>Nyayo National Stadium</h1>
        <h2>Available Events</h2>
    </header>
    <div class="content">
        <?php if ($result->num_rows > 0): ?>
            <div class="events-grid">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="event-card">
                        <h3><?php echo $row['event_name']; ?></h3>
                        <p><?php echo $row['event_description']; ?></p>
                        <p><strong>Date:</strong> <?php echo $row['event_date']; ?></p>
                        <p><strong>Time:</strong> <?php echo $row['event_start_time']; ?> - <?php echo $row['event_end_time']; ?></p>
                        <p><strong>Available Seats:</strong> <?php echo $row['available_seats']; ?></p>
                        <p><strong>Price:</strong> Ksh <?php echo $row['ticket_price_ksh']; ?></p>
                        <a href="event_details.php?event_id=<?php echo $row['event_id']; ?>" class="btn">View Details</a>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>No events available.</p>
        <?php endif; ?>
    </div>
    <footer>
        <p>&copy; 2024 Nyayo National Stadium</p>
    </footer>
</body>
</html>
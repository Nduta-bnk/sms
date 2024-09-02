<?php
session_start();

require_once 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

// Retrieve the username from the session
$username = $_SESSION['username'];

// Fetch event names from the events table
$sql = "SELECT event_id, event_name FROM events";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard - Nyayo National Stadium</title>
    <script src="customer.js" defer></script>
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
    color: #333;
}

a {
    text-decoration: none;
    color: #2c3e50;
    transition: color 0.3s;
}

a:hover {
    color: #1abc9c;
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

#welcome-message {
    margin-top: 10px;
    font-size: 18px;
    font-weight: bold;
}

/* Navigation Styles */
nav {
    background-color: #34495e;
    color: white;
    padding: 10px;
}

nav ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
    display: flex;
    justify-content: center;
}

nav ul a {
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    transition: background-color 0.3s;
}

nav ul a:hover {
    background-color: #1abc9c;
}

/* Main Content Styles */
main {
    padding: 20px;
    text-align: center;
}

main h2 {
    color: white;
    margin-bottom: 20px;
}

.events-list {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
}

.events-list form {
    margin: 10px;
}

.events-list button {
    background-color: #1abc9c;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s;
}

.events-list button:hover {
    background-color: #16a085;
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
    </style>
</head>
<body>
<div class="container">
    <header>
        <h1>Customer Dashboard</h1>   
        <section id="welcome-message">
            <!-- Welcome message will be displayed here -->
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Display the welcome message
                    const username = '<?php echo $username; ?>';
                    const welcomeMessage = `Welcome, ${username}!`;
                    document.getElementById('welcome-message').textContent = welcomeMessage;
                });
            </script>
        </section>
    </header>      
    <nav>
            <ul>
                <a href="index.php">Home</a>
                <a href="tickets.php">Purchases</a>
                <a href="payments.php">Payments</a>
                <a href="logout.php">Logout</a>
            </ul>
        </nav>
    <main>
            <h2>Available Events</h2>
            <div class="events-list">
                <?php
                if ($result->num_rows > 0) {
                    // Output data of each row
                    while($row = $result->fetch_assoc()) {
                        echo '<form action="event_details.php" method="GET">';
                        echo '<input type="hidden" name="event_id" value="' . $row["event_id"] . '">';
                        echo '<button type="submit">' . $row["event_name"] . '</button>';
                        echo '</form>';
                    }
                } else {
                    echo "No events available.";
                }
                $conn->close();
                ?>
            </div>
        </main>
</div>
    <footer>
        <p>2024 Nyayo National Stadium</p>
    </footer>
</body>
</html>
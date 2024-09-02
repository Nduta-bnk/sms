<?php
session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Nyayo National Stadium</title>
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
.admin-header {
    width: 100%;
    background-color: #2c3e50;
    color: white;
    padding: 15px 20px;
    text-align: center;
    position: relative;
}

.admin-header h1 {
    margin: 0;
    font-size: 24px;
}

#welcome-message {
    margin-top: 10px;
    font-size: 18px;
}

/* Container Styles */
.admin-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px;
}

.admin-content {
    margin: 0;
    width: 100%;
    max-width: 1200px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    padding: 20px; /* Adjusted padding */
}

/* Menu Styles */
.admin-menu {
    display: flex;
    justify-content: space-around;
    margin-bottom: 20px;
}

.admin-menu a {
    color: #2c3e50;
    font-weight: bold;
    padding: 10px 20px;
    border: 2px solid #2c3e50;
    border-radius: 5px;
    transition: background-color 0.3s, color 0.3s;
}

.admin-menu a:hover {
    background-color: #2c3e50;
    color: white;
}

/* Section Styles */
.admin-section {
    margin-bottom: 20px;
}

.admin-section h2 {
    color: #2c3e50;
    border-bottom: 2px solid #2c3e50;
    padding-bottom: 5px;
}

.admin-section p {
    line-height: 1.6;
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
    <div class="admin-header">
            <h1>Admin Panel</h1>
            <section id="welcome-message">
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Display the welcome message
                        const username = '<?php echo $username; ?>';
                        const welcomeMessage = `Welcome, ${username}!`;
                        document.getElementById('welcome-message').textContent = welcomeMessage;
                    });
                </script>
            </section>
    </div>
    <div class="admin-container">
        <div class="admin-content">
            <div class="admin-menu">
                <a href="registeradmin.php">Manage Admin</a>
                <a href="manage_events.php">Manage Events</a>
                <a href="manage_users.php">Manage Users</a>
                <a href="view_reports.php">Reports</a>
                <a href="customer.php">Customer Dashboard</a>
                <a href="logout.php">Logout</a>
            </div>
            <div class="admin-section">
                <h2>Dashboard</h2>
                <p>Welcome to the admin panel. Use the menu above to navigate through the different sections.</p>
            </div>
            <!-- Additional admin sections can be added here -->
        </div>
    </div>
    <footer>
    <p>2024 Nyayo National Stadium</p>
    </footer>
</body>
</html>
<?php

include 'config.php';

// Fetch users from the database
$result = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
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
    <h1>Manage Users</h1>
    <nav>
        <ul>
            <li><a href="admin.php">Back to Admin Panel</a></li>
        </ul>
    </nav>
</header>
<section>
<h2>Users</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Phone Number</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['user_id']}</td>
                    <td>{$row['full_name']}</td>
                    <td>{$row['phone_number']}</td>
                    <td>{$row['username']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['role']}</td>
                    <td>
                        <a href='edit_users.php?id={$row['user_id']}'>Edit</a> |
                        <a href='delete_users.php?id={$row['user_id']}'>Delete</a> 
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
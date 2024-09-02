<?php
session_start();
require_once 'config.php'; // Ensure config.php contains your database connection details

$login_error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form inputs
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Validate inputs
    $errors = [];
    
    if (empty($username)) {
        $errors['username'] = 'Username is required.';
    }

    if (empty($password)) {
        $errors['password'] = 'Password is required.';
    }

    if (empty($errors)) {
        try {
            // Prepare SQL statement to fetch the user and their role
            $sql = "SELECT user_id, username, password, role FROM users WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            // Check if user exists
            if ($stmt->num_rows == 1) {
                $stmt->bind_result($id, $db_username, $db_password, $role);
                $stmt->fetch();

                // Verify password
                if (password_verify($password, $db_password)) {
                    $_SESSION['logged_in'] = true;
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['username'] = $db_username;
                    $_SESSION['role'] = $role;

                    // Redirect user based on role
                    if ($role === 'admin') {
                        header('Location: admin.php');
                    } else {
                        header('Location: customer.php');
                    }
                    exit();
                } else {
                    $login_error = 'Invalid username or password.';
                }
            } else {
                $login_error = 'Invalid username or password.';
            }
        } catch (Exception $e) {
            $login_error = 'Error: ' . $e->getMessage();
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
        }
    } else {
        // Combine errors for display
        $login_error = implode('<br>', $errors);
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        .error {
            color: red;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .toggle-password {
            cursor: pointer;
        }
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

header h2 {
    margin: 0;
    font-size: 1.5em;
    color: #1abc9c;
}

.content {
    max-width: 400px;
    margin: 40px auto;
    padding: 20px;
    background: rgba(0, 0, 0, 0.4);
    border-radius: 8px;
    box-shadow: 0 15px 25px rgba(0, 0, 0, 0.5);
}

form {
    display: flex;
    flex-direction: column;
}

.form-group {
    margin-bottom: 15px;
}

label {
    font-weight: bold;
    color: white;
    display: block;
    margin-bottom: 5px;
}

input[type="text"],
input[type="password"] {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1em;
    width: 100%;
    box-sizing: border-box;
}

input[type="text"]:focus,
input[type="password"]:focus {
    border-color: #1abc9c;
    outline: none;
}

.username-container {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1em;
    width: 100%;
    box-sizing: border-box;
}

.password-container {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1em;
    width: 100%;
    box-sizing: border-box;
}
.password-container input[type="password"] {
    width: 100%;
    padding: 10px; /* Padding inside the input field */
    padding-right: 40px; /* Make space for the icon */
    box-sizing: border-box;
    border: 1px solid #ccc; /* Border style */
    border-radius: 4px; /* Rounded corners */
}

.password-container .toggle-password {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: #999; /* Icon color */
    cursor: pointer;
    font-size: 1.2em; /* Icon size */
}

button {
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

button:hover {
    background-color: #16a085;
}

/* Paragraph Styles */
p {
    font-size: 16px;
    color: white;
    text-align: center; /* Center-align the text */
    margin: 20px 0; /* Add some margin for spacing */
}

/* Link Styles */
p a {
    color: #2196f3; /* Blue color for the link */
    text-decoration: none; /* Remove underline */
    font-weight: bold; /* Make the link bold */
    transition: color 0.3s; /* Smooth transition for color change */
}

p a:hover {
    color: #0d47a1; /* Darker blue for hover effect */
}

/* Error and Success Messages */
.error {
    color: #d9534f;
    font-weight: bold;
    margin-bottom: 15px;
}

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
    
    .content {
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
    <h1>Nyayo National Stadium</h1>
    <h2> Login</h2>
</header>
<div class="content">
    <form method="POST">
        <?php if (!empty($login_error)): ?>
        <p class="error"><?= $login_error ?></p>
        <?php endif; ?>
        <div class="form-group">
            <div class="username-container">
            <input type="text" name="username" id="username" placeholder="Username">
            </div>
        </div>
        <div class="form-group">
            <div class="password-container">
            <input type="password" name="password" id="password" placeholder="Password">
            <span class="toggle-password" id="toggle-password">
                <i class="fas fa-eye"></i>
            </span>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a></p>
</div>
<footer>
    <p>2024 Nyayo National Stadium</p>
</footer>

<script>
document.getElementById('toggle-password').addEventListener('click', function () {
    const passwordField = document.getElementById('password');
    const icon = this.querySelector('i');

    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
});
</script>
</body>
</html>
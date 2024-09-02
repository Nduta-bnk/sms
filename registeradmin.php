<?php
require_once 'config.php'; // Ensure config.php contains your database connection details

$error = '';
$success = '';

// Only process the form if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form inputs
    $full_name = trim($_POST['full_name']);
    $gender=trim($_POST['gender']);
    $email = trim($_POST['email']);
    $phone_number = trim($_POST['phone_number']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $role = trim($_POST['role']);

    // Server-side validation
    if (empty($full_name) || empty($gender) || empty($email) || empty($phone_number) || empty($username) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/@gmail\.com$/', $email)) {
        $error = "Invalid email format.";
    } elseif (!preg_match('/^[0-9]{10}$/', $phone_number)) {
        $error = "Invalid phone number format. It should be 10 digits.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
        $error = "Password must contain at least one uppercase letter, one lowercase letter, one digit, and one special character.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare SQL statement
        $sql = "INSERT INTO users (full_name, gender, email, phone_number, username, password, role, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sssssss", $full_name, $gender, $email, $phone_number, $username, $hashed_password, $role);

            // Execute the statement
            if ($stmt->execute()) {
                $success = "Registration successful. You can now <a href='login.php'>log in</a>.";
            } else {
                $error = "Error: " . $stmt->error;
            }

            // Close the statement
            $stmt->close();
        } else {
            $error = "Error: " . $conn->error;
        }
    }

    // Close the database connection
    $conn->close();
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Register</title>
  <script src="register.js" defer></script>
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

/* Main Content Styles */
section.content {
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
    margin-bottom: 5px;
    font-weight: bold;
    color: white;
    display: block;
}

input[type="text"],
input[type="email"],
input[type="tel"],
input[type="password"] {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1em;
    width: 100%;
    box-sizing: border-box;
}
select {
    width: 100%;
    padding: 10px;
    font-size: 14px;
    color: #333;
    background-color: #f8f8f8;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
}

.fullname-container {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1em;
    width: 100%;
    box-sizing: border-box;
}

.email-container {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1em;
    width: 100%;
    box-sizing: border-box;
}

.phone_number-container {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1em;
    width: 100%;
    box-sizing: border-box;
}

.username-container {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1em;
    width: 100%;
    box-sizing: border-box;
}

.confirm-password-container {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1em;
    width: 100%;
    box-sizing: border-box;
}

.select-container {
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
.toggle-password {
    color: #1abc9c;
    cursor: pointer;
    font-size: 0.9em;
    margin-top: 5px;
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

.success {
    color: #5bc0de;
    font-weight: bold;
    margin-bottom: 15px;
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
    <h1>Nyayo National Stadium</h1>
    <h2>Registration</h2>
</header>
  <section class="content">
    <?php
    if ($error != '') {
        echo "<p class='error'>$error</p>";
    }
    if ($success != '') {
        echo "<p class='success'>$success</p>";
    }
    ?>
    <form id="registration-form" method="post" action="registeradmin.php" onsubmit="return validateForm()">
    <div class="form-group">
        <div class="fullname-container">
            <input type="text" name="full_name" id="fullname" placeholder="Fullname">
            </div>
        </div>

        <div class="form-group">
            <div class="select-container">
                <select name="gender" id="gender">
                    <option value="">Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <div class="email-container">
                <input type="email" name="email" id="email" placeholder="Email">
            </div>
        </div>

        <div class="form-group">
            <div class="phone_number-container">
                <input type="tel" name="phone_number" id="phone_number" placeholder="Phone Number">
            </div>
        </div>

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

        <div class="form-group">
            <div class="confirm-password-container">
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password">
                <span class="toggle-password" id="toggle-password">
                    <i class="fas fa-eye"></i>
                </span>
            </div>
        </div>

        <div class="form-group">
            <div class="select-container">
                <select name="role" id="role">
                    <option value="">Select Role</option>
                    <option value="admin">Admin</option>
                    <option value="customer">Customer</option>
                </select>
            </div>
        </div>
        <button type="submit">Register Admin</button>
    </form>
    <p>Already have an account? <a href="login.php">Log in here</a></p>
  </section>
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
<?php
session_start();
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Nyayo Stadium</title>
  <script src="index.js" defer></script>
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
    background-color: #f4f4f4;
    color: #333;
}

.container {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

/* Header Styles */
header {
    background: #2c3e50;
    color: white;
    padding: 15px 0;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

header h1 {
    margin: 0;
    text-align: center;
    font-size: 24px;
}

nav {
    background: #34495e;
    color: white;
    padding: 10px 0;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

nav ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    justify-content: center;
}

nav ul li {
    margin: 0 15px;
}

nav ul a {
    color: white;
    text-decoration: none;
    font-weight: bold;
    padding: 10px 15px;
    display: block;
    transition: background 0.3s ease, border-radius 0.3s ease;
    border-radius: 5px;
}

nav ul a:hover {
    background: #1abc9c;
}

/* Main Section Styles */
main {
    flex: 1;
    padding: 20px;
}

/* Hero Section Styles */
.hero {
    background: rgba(0, 0, 0, 0.4);
    color: white;
    text-align: center;
    padding: 40px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.hero h2 {
    font-size: 2.5em;
    margin: 0;
}

.hero p {
    font-size: 1.2em;
    margin: 10px 0;
}

.cta-button {
    display: inline-block;
    margin: 10px;
    padding: 12px 25px;
    background: #1abc9c;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    font-weight: bold;
    transition: background 0.3s ease, color 0.3s ease;
}

.cta-button:hover {
    background: #16a085;
}

/* Facilities Section Styles */
.section {
    background: rgba(0, 0, 0, 0.4);
    color: white;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.section h2 {
    font-size: 2em;
    margin-bottom: 10px;
    color: white;
}

.facilities {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
}

.card {
    background: rgba(0, 0, 0, 0.4);
    color: white;
    border-radius: 8px;
    padding: 20px;
    margin: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
    flex: 1 1 200px;
    transition: background 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    background: #e9ecef;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
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
@media (max-width: 768px) {
    .hero h2 {
        font-size: 2em;
    }

    .hero p {
        font-size: 1em;
    }

    .cta-button {
        padding: 10px 20px;
    }

    .facilities {
        flex-direction: column;
        align-items: center;
    }

    .card {
        width: 80%;
        max-width: 300px;
    }
}
  </style>
</head>
<body>
  <div class="container">
    <header>
      <h1>Nyayo National Stadium</h1>
      <nav>
        <ul>
          <li><a href="index.php">Home</a></li>
          <li><a href="logout.php">Logout</a></li>
        </ul>
      </nav>
    </header>
    <main>
      <section class="hero">
        <h2>Nyayo National Stadium!</h2>
        <p>Peace, Love, Unity</p>
          <a href="register.php" class="cta-button">Register</a>
          <a href="login.php" class="cta-button">Login</a>
      </section>
      <div id="facilities" class="section">
        <h2>Our Facilities</h2>
        <div class="facilities">
          <div class="card">
            <p>Comfortable seating for all our guests</p>
          </div>
          <div class="card">
            <p>Wide range of food and beverages</p>
          </div>
          <div class="card">
            <p>Ample parking space available</p>
          </div>
        </div>
      </div>
      <div id="contact" class="section">
        <h2>Contact Us</h2>
        <p>Email: info@nyayostadium.com</p>
        <p>Phone: +254 700 000 000</p>
        <p>Address: Nyayo National Stadium, Nairobi, Kenya</p>
      </div>
    </main>
    <footer>
      <p>2024 Nyayo National Stadium</p>
    </footer>
  </div>
</body>
</html>
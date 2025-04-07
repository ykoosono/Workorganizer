<?php
session_start();


$host = "localhost";
$dbname = "workorganizer_db";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle login (assuming you have a login system, this is just an example)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check login credentials
    $query = "SELECT * FROM users WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // User connects to homepag.php
        //echo "Welcome, " . $email;
        $_SESSION['logged_in'] = true;
        header("Location: homepage.php");
    } else {
        echo "Invalid email or password.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Workorganizer</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<!-- Login Form -->
<div class="container">
    <div class="login-box">
        <h2>Welcome Back</h2>
        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" required />
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required />
            </div>
            <button type="submit">Login</button>
        </form>
        <div class="auth-links">
            <p class="create-account">
                Not yet a member? <a href="signup.php">Create an account</a>
            </p>
            <a href="forgot_password.php" class="forgot-password">Forgot Password?</a>
        </div>
    </div>
</div>

</body>
</html>
<?php
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

// Handle form submission for the forgot password
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Check if email exists in the database
    $query = "SELECT email FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Generate a unique token for password reset
        $token = bin2hex(random_bytes(50));

        // Save the token in the database with expiration (1 hour)
        $expiry_time = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $query = "UPDATE users SET reset_token = ?, token_expiry = ? WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $token, $expiry_time, $email);
        $stmt->execute();

        // Send the reset email with the token
        $reset_link = "http://yourwebsite.com/reset_password.php?token=" . $token;

        $subject = "Password Reset Request";
        $message = "Please click the following link to reset your password: " . $reset_link;
        $headers = "From: no-reply@yourwebsite.com";

        if (mail($email, $subject, $message, $headers)) {
            echo "A password reset link has been sent to your email address.";
        } else {
            echo "Error in sending email. Please try again.";
        }
    } else {
        echo "Email address not found.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!-- forgot_password_form.php -->
<form method="POST" action="forgot_password.php">
    <label for="email">Enter your email address:</label>
    <input type="email" name="email" required>
    <button type="submit">Submit</button>
</form>

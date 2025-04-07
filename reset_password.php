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

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Validate token
    $query = "SELECT id, reset_token, token_expiry FROM users WHERE reset_token = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Fetch user data
        $stmt->bind_result($id, $db_token, $token_expiry);
        $stmt->fetch();

        // Check if token is expired
        if (new DateTime() > new DateTime($token_expiry)) {
            echo "This token has expired.";
        } else {
            // Show the form to reset the password
            echo '
                <form method="POST" action="reset_password.php">
                    <input type="hidden" name="token" value="' . $token . '">
                    <label for="password">Enter New Password:</label>
                    <input type="password" name="password" required>
                    <button type="submit">Submit</button>
                </form>
            ';
        }
    } else {
        echo "Invalid token.";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['token']) && isset($_POST['password'])) {
        $token = $_POST['token'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Update the password in the database
        $query = "UPDATE users SET password = ?, reset_token = NULL, token_expiry = NULL WHERE reset_token = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $password, $token);
        $stmt->execute();

        echo "Your password has been successfully reset.";
    }
}

$conn->close();
?>



<?php
require 'DBConnect.php';
session_start();



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Check if username or password is empty
    if (empty($username) || empty($password)) {
        echo "Username and password cannot be empty.";
        exit;
    }

    // Query database for username
    $stmt = $conn->prepare("SELECT user_id, password FROM users WHERE name = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id,$stored_password);
        $stmt->fetch();

        // Plain text password check (NOT RECOMMENDED)
        if ($password === $stored_password) {
            $_SESSION["user_id"] = $id;
            echo "Login successful!";
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "User not found.";
    }

    $stmt->close();
}
$conn->close();
?>


<form method="post">
    Username: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    <button type="submit">Login</button>
</form>

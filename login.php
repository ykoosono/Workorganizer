<?php
session_start();

$host = "localhost";
$dbname = "workorganizer_db";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['logged_in'] = true;
        header("Location: homepage.php");
        exit();
    } else {
        $login_error = "Invalid email or password.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login | WorkOrganizer</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body style="background: linear-gradient(135deg, #c0d6e4, #f0f4f8); min-height: 100vh;">

  <nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="homepage.php">WorkOrganizer</a>
    </div>
  </nav>

  <div class="container my-5">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        <div class="card p-4 shadow-lg rounded">
          <h2 class="text-center mb-4">Login</h2>
          <?php if (!empty($login_error)) echo "<div class='alert alert-danger'>$login_error</div>"; ?>
          <form method="POST" action="login.php">
            <div class="mb-3">
              <label for="email" class="form-label">Email Address</label>
              <input type="email" name="email" id="email" class="form-control" required />
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" name="password" id="password" class="form-control" required />
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
          </form>
          <div class="mt-3 text-center">
            <p>Not a member? <a href="signup.php">Sign up</a></p>
            <a href="forgot_password.php">Forgot password?</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <footer class="text-center mt-5 p-3 bg-light">
    <p>&copy; 2025 WorkOrganizer. All rights reserved.</p>
  </footer>
</body>
</html>

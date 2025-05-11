<?php
session_start();
$host = 'localhost';
$db = 'workorganizer_db';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB error: " . $e->getMessage());
}

$token = $_GET['token'] ?? '';
$error = '';
$success = false;
$resetData = null;

if (!$token) {
    $error = "Invalid or missing token.";
} else {
    $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW()");
    $stmt->execute([$token]);
    $resetData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$resetData) {
        $error = "Token is invalid or expired.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $resetData) {
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (strlen($new_password) < 6) {
        $error = "Password must be at least 6 characters.";
    } elseif ($new_password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $pdo->prepare("UPDATE users SET password = ? WHERE email = ?")->execute([$hashed, $resetData['email']]);
        $pdo->prepare("DELETE FROM password_resets WHERE token = ?")->execute([$token]);
        $success = true;
    }
}
?>

<?php include 'header.php'; ?>

<body style="background: linear-gradient(135deg, #e3f2fd, #ffffff); min-height: 100vh;">
<main class="flex-grow-1">
  <div class="container my-5">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        <div class="card p-4 shadow-lg rounded">
          <h2 class="text-center mb-4">Reset Password</h2>

          <?php if ($success): ?>
            <div class="alert alert-success">
              Password has been reset. <a href="login.php">Login now</a>.
            </div>
          <?php elseif ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
          <?php endif; ?>

          <?php if (!$success && $resetData): ?>
            <form method="POST" action="">
              <div class="mb-3">
                <label for="new_password" class="form-label">New Password</label>
                <input type="password" name="new_password" id="new_password" class="form-control" required>
              </div>
              <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
              </div>
              <button type="submit" class="btn btn-primary w-100">Update Password</button>
            </form>
          <?php endif; ?>

          <div class="mt-3 text-center">
            <a href="login.php">Back to login</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<?php include 'footer.php'; ?>
</body>

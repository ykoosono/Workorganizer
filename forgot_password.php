<head>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    html, body {
      height: 100%;
    }
    body {
      display: flex;
      flex-direction: column;
    }
    main {
      flex: 1 0 auto; /* Allows main content to grow */
    }
    footer {
      flex-shrink: 0; /* Prevents footer from shrinking */
    }
  </style>
</head>

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

$success = false;
$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (!$email) {
        $error = "Email is required.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Generate token
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', time() + 3600); // 1 hour from now

            // Create password_resets table if not exists
            $pdo->exec("CREATE TABLE IF NOT EXISTS password_resets (
                id INT AUTO_INCREMENT PRIMARY KEY,
                email VARCHAR(100) NOT NULL,
                token VARCHAR(64) NOT NULL,
                expires_at DATETIME NOT NULL
            ) ENGINE=InnoDB");

            // Store token
            $pdo->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)")
                ->execute([$email, $token, $expires]);

            // Build dynamic reset link
            $baseURL = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
            $resetLink = $baseURL . "/reset_password.php?token=$token";

            $success = true;
        } else {
            $error = "No user found with that email.";
        }
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
          <h2 class="text-center mb-4">Forgot Password</h2>

          <?php if ($success): ?>
            <div class="alert alert-success">
              A reset link has been generated. <br>
              <strong>Test Link:</strong><br>
              <a href="<?php echo htmlspecialchars($resetLink); ?>"><?php echo htmlspecialchars($resetLink); ?></a>
            </div>
          <?php elseif ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
          <?php endif; ?>

          <form method="POST" action="">
            <div class="mb-3">
              <label for="email" class="form-label">Enter your email address</label>
              <input type="email" name="email" id="email" class="form-control" required value="<?php echo htmlspecialchars($email); ?>" />
            </div>
            <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
          </form>

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
<?php
session_start();
$host = 'localhost';
$db = 'workorganizer_db';
$user = 'root';
$pass = '';
$pdo = null;

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$errors = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $errors[] = "Email and password are required.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];

            header("Location: homepage.php");
            exit;
        } else {
            $errors[] = "Invalid email or password.";
        }
    }
}
?>
<?php include 'header.php'; ?>

<body style="background: linear-gradient(135deg, #c0d6e4, #f0f4f8); min-height: 100vh;">
  <main class="flex-grow-1">
    <div class="container my-5">
      <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
          <div class="card p-4 shadow-lg rounded">
            <h2 class="text-center mb-4">Login</h2>

            <?php if (isset($_GET['registered'])): ?>
              <div class="alert alert-success">Registration successful. You can now log in.</div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
              <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                  <div><?php echo htmlspecialchars($error); ?></div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>

            <form method="POST" action="login.php">
              <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" name="email" id="email" class="form-control" required value="<?php echo htmlspecialchars($email); ?>" />
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
  </main>

<?php include 'footer.php'; ?>
</body>
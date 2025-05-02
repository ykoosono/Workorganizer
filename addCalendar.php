<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

include 'header.php';

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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title']);
  $description = trim($_POST['description']);

  if (!empty($title)) {
    // Insert into calendars
    $stmt = $pdo->prepare("INSERT INTO calendars (title, description, user_id) VALUES (?, ?, ?)");
    $stmt->execute([$title, $description, $_SESSION['user_id']]);

    $calendarId = $pdo->lastInsertId();

    // Assign creator as Lead Member in users_calendars
    $stmt = $pdo->prepare("INSERT INTO users_calendars (calendar_id, user_id, role_id) VALUES (?, ?, ?)");
    $stmt->execute([$calendarId, $_SESSION['user_id'], 1]); // 1 = Lead Member

    header("Location: homepage.php");
    exit;
  } else {
    $error = "Calendar title is required.";
  }
}
?>

<div class="container mt-5">
  <h2 class="mb-4">Add New Calendar</h2>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <form method="POST" action="addCalendar.php">
    <div class="mb-3">
      <label for="title" class="form-label">Calendar Title</label>
      <input type="text" class="form-control" id="title" name="title" required>
    </div>

    <div class="mb-3">
      <label for="description" class="form-label">Calendar Description</label>
      <textarea class="form-control" id="description" name="description" rows="4"></textarea>
    </div>

    <button type="submit" class="btn btn-success">Create Calendar</button>
    <a href="homepage.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>

<?php include 'footer.php'; ?>

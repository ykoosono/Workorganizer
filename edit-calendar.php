<?php include 'header.php'; ?>

<?php
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

$calendarId = $_GET['id'] ?? null;
if (!$calendarId || !is_numeric($calendarId)) {
  echo "<div class='container mt-5'><div class='alert alert-danger'>Invalid calendar ID.</div></div>";
  include 'footer.php';
  exit;
}

// Fetch calendar
$stmt = $pdo->prepare("SELECT * FROM calendars WHERE id = ?");
$stmt->execute([$calendarId]);
$calendar = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$calendar) {
  echo "<div class='container mt-5'><div class='alert alert-warning'>Calendar not found.</div></div>";
  include 'footer.php';
  exit;
}

// Handle form submission for editing the calendar
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $title = $_POST['title'] ?? '';
  $description = $_POST['description'] ?? '';

  if (!empty($title) && !empty($description)) {
    // Update calendar details
    $stmt = $pdo->prepare("UPDATE calendars SET title = ?, description = ? WHERE id = ?");
    $stmt->execute([$title, $description, $calendarId]);

    header("Location: view-calendar.php?id=$calendarId");
    exit;
  }
}
?>

<div class="container mt-5">
  <h2>Edit Calendar</h2>
  <form method="POST">
    <div class="mb-3">
      <label for="title" class="form-label">Calendar Title</label>
      <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($calendar['title']); ?>" required>
    </div>
    <div class="mb-3">
      <label for="description" class="form-label">Calendar Description</label>
      <textarea class="form-control" id="description" name="description" required><?php echo htmlspecialchars($calendar['description']); ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Save Changes</button>
    <a href="view-calendar.php?id=<?php echo $calendarId; ?>" class="btn btn-secondary">Cancel</a>
  </form>
</div>

<?php include 'footer.php'; ?>


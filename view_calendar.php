<?php include 'header.php'; ?>
<?php
$host = 'localhost';
$db = 'workorganizer_db'; // ✅ corrected name
$user = 'root'; // ← replace with your actual DB username
$pass = ''; // ← replace with your actual DB password
$pdo = null;
try {
  $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("Database connection failed: " . $e->getMessage());
}
?>


<?php
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

// Fetch events
$eventStmt = $pdo->prepare("SELECT * FROM events WHERE calendar_id = ? ORDER BY date ASC");
$eventStmt->execute([$calendarId]);
$events = $eventStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex flex-column min-vh-100">
  <main class="flex-grow-1">
    <div class="container mt-5">
      <h2 class="mb-3"><?php echo htmlspecialchars($calendar['title']); ?></h2>
      <p class="text-muted"><?php echo htmlspecialchars($calendar['description']); ?></p>

      <hr>

      <h4 class="mb-3">Events</h4>
      <?php if ($events): ?>
        <ul class="list-group">
          <?php foreach ($events as $event): ?>
            <li class="list-group-item">
              <h5 class="mb-1"><?php echo htmlspecialchars($event['title']); ?></h5>
              <small class="text-muted"><?php echo htmlspecialchars($event['date']); ?></small>
              <p class="mb-0"><?php echo htmlspecialchars($event['details']); ?></p>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <p>No events found for this calendar.</p>
      <?php endif; ?>

      <a href="homepage.php" class="btn btn-secondary mt-4">Back to My Calendars</a>
    </div>
  </main>

  <?php include 'footer.php'; ?>
</div>

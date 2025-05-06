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

// Delete task if requested
if (isset($_GET['delete_event']) && is_numeric($_GET['delete_event'])) {
  $deleteId = $_GET['delete_event'];
  $pdo->prepare("DELETE FROM events WHERE id = ? AND calendar_id = ?")->execute([$deleteId, $calendarId]);
  header("Location: view-calendar.php?id=$calendarId");
  exit;
}

// Add or edit event
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $title = $_POST['title'] ?? '';
  $date = $_POST['date'] ?? '';
  $details = $_POST['details'] ?? '';
  $eventId = $_POST['event_id'] ?? null;

  if (!empty($title) && !empty($date)) {
    if ($eventId) {
      // Update event
      $stmt = $pdo->prepare("UPDATE events SET title=?, date=?, details=? WHERE id=? AND calendar_id=?");
      $stmt->execute([$title, $date, $details, $eventId, $calendarId]);
    } else {
      // Insert new event
      $stmt = $pdo->prepare("INSERT INTO events (calendar_id, title, date, details) VALUES (?, ?, ?, ?)");
      $stmt->execute([$calendarId, $title, $date, $details]);
    }
    header("Location: view-calendar.php?id=$calendarId");
    exit;
  }
}

// Fetch events
$eventStmt = $pdo->prepare("SELECT * FROM events WHERE calendar_id = ? ORDER BY date ASC");
$eventStmt->execute([$calendarId]);
$events = $eventStmt->fetchAll(PDO::FETCH_ASSOC);

// Editing specific event
$editingEvent = null;
if (isset($_GET['edit_event']) && is_numeric($_GET['edit_event'])) {
  $editId = $_GET['edit_event'];
  $stmt = $pdo->prepare("SELECT * FROM events WHERE id = ? AND calendar_id = ?");
  $stmt->execute([$editId, $calendarId]);
  $editingEvent = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<div class="d-flex flex-column min-vh-100">
  <main class="flex-grow-1">
    <div class="container mt-5">
      <h2 class="mb-3"><?php echo htmlspecialchars($calendar['title']); ?></h2>
      <p class="text-muted"><?php echo htmlspecialchars($calendar['description']); ?></p>

      <hr>
      <h4 class="mb-3">Events</h4>

      <?php if ($events): ?>
        <ul class="list-group mb-4">
          <?php foreach ($events as $event): ?>
            <li class="list-group-item d-flex justify-content-between align-items-start">
              <div>
                <h5 class="mb-1"><?php echo htmlspecialchars($event['title']); ?></h5>
                <small class="text-muted"><?php echo htmlspecialchars($event['date']); ?></small>
                <p class="mb-1"><?php echo htmlspecialchars($event['details']); ?></p>
              </div>
              <div class="btn-group btn-group-sm">
                <a href="?id=<?php echo $calendarId; ?>&edit_event=<?php echo $event['id']; ?>" class="btn btn-outline-primary">Edit</a>
                <a href="?id=<?php echo $calendarId; ?>&delete_event=<?php echo $event['id']; ?>" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this event?');">Delete</a>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <p>No events found for this calendar.</p>
      <?php endif; ?>

      <!-- Add/Edit Task Form -->
      <hr>
      <h4 class="mb-3"><?php echo $editingEvent ? 'Edit Task' : 'Add a New Task'; ?></h4>
      <form method="POST">
        <input type="hidden" name="event_id" value="<?php echo $editingEvent['id'] ?? ''; ?>">
        <div class="mb-3">
          <label for="title" class="form-label">Task Title</label>
          <input type="text" class="form-control" id="title" name="title" required value="<?php echo $editingEvent['title'] ?? ''; ?>">
        </div>
        <div class="mb-3">
          <label for="date" class="form-label">Date & Time</label>
          <input type="datetime-local" class="form-control" id="date" name="date" required value="<?php echo isset($editingEvent['date']) ? date('Y-m-d\TH:i', strtotime($editingEvent['date'])) : ''; ?>">
        </div>
        <div class="mb-3">
          <label for="details" class="form-label">Details</label>
          <textarea class="form-control" id="details" name="details"><?php echo $editingEvent['details'] ?? ''; ?></textarea>
        </div>
        <button type="submit" class="btn btn-<?php echo $editingEvent ? 'primary' : 'success'; ?>">
          <?php echo $editingEvent ? 'Update Task' : 'Add Task'; ?>
        </button>
        <?php if ($editingEvent): ?>
          <a href="view-calendar.php?id=<?php echo $calendarId; ?>" class="btn btn-secondary">Cancel</a>
        <?php endif; ?>
      </form>

      <!-- Navigation -->
      <div class="mt-4">
        <a href="homepage.php" class="btn btn-primary">Back to My Calendars</a>
        <a href="edit-calendar.php?id=<?php echo $calendarId; ?>" class="btn btn-warning">Edit Calendar</a>
        <a href="add-member.php?id=<?php echo $calendarId; ?>" class="btn btn-success">Add Member</a>
        <a href="remove-member.php?id=<?php echo $calendarId; ?>" class="btn btn-danger">Remove Member</a>
      </div>
    </div>
  </main>
  <?php include 'footer.php'; ?>
</div>

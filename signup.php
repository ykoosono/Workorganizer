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

// Fetch incomplete tasks
$incompleteStmt = $pdo->prepare("SELECT * FROM events WHERE calendar_id = ? AND is_complete = 0 ORDER BY date ASC");
$incompleteStmt->execute([$calendarId]);
$incompleteEvents = $incompleteStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch complete tasks
$completeStmt = $pdo->prepare("SELECT * FROM events WHERE calendar_id = ? AND is_complete = 1 ORDER BY date ASC");
$completeStmt->execute([$calendarId]);
$completeEvents = $completeStmt->fetchAll(PDO::FETCH_ASSOC);

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

            <!-- Incomplete Tasks -->
            <h4 class="mb-3">Incomplete Tasks</h4>
            <?php if ($incompleteEvents): ?>
                <ul class="list-group mb-4">
                    <?php foreach ($incompleteEvents as $event): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="mb-1"><?php echo htmlspecialchars($event['title']); ?></h5>
                                <small class="text-muted"><?php echo htmlspecialchars($event['date']); ?></small>
                                <p class="mb-1"><?php echo htmlspecialchars($event['details']); ?></p>
                            </div>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-success toggle-complete"
                                        data-event-id="<?php echo $event['id']; ?>"
                                        data-status="1">
                                    Mark Complete
                                </button>
                                <a href="?id=<?php echo $calendarId; ?>&edit_event=<?php echo $event['id']; ?>" class="btn btn-outline-primary">Edit</a>
                                <a href="?id=<?php echo $calendarId; ?>&delete_event=<?php echo $event['id']; ?>" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this event?');">Delete</a>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No incomplete tasks found for this calendar.</p>
            <?php endif; ?>

            <hr>

            <!-- Complete Tasks -->
            <h4 class="mb-3">Complete Tasks</h4>
            <?php if ($completeEvents): ?>
                <ul class="list-group mb-4">
                    <?php foreach ($completeEvents as $event): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="mb-1"><?php echo htmlspecialchars($event['title']); ?></h5>
                                <small class="text-muted"><?php echo htmlspecialchars($event['date']); ?></small>
                                <p class="mb-1"><?php echo htmlspecialchars($event['details']); ?></p>
                            </div>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-secondary toggle-complete"
                                        data-event-id="<?php echo $event['id']; ?>"
                                        data-status="0">
                                    Mark Incomplete
                                </button>
                                <a href="?id=<?php echo $calendarId; ?>&edit_event=<?php echo $event['id']; ?>" class="btn btn-outline-primary">Edit</a>
                                <a href="?id=<?php echo $calendarId; ?>&delete_event=<?php echo $event['id']; ?>" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this event?');">Delete</a>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No complete tasks found for this calendar.</p>
            <?php endif; ?>

            <!-- Add/Edit Task Form -->
            <hr>
            <h4 class="mb-3"><?php echo $editingEvent ? 'Edit Task' : 'Add a New Task'; ?></h4>
            <form method="POST">
                <input type="hidden" name="event_id" value="<?php echo $editingEvent['id'] ?? ''; ?>">
                <div class="mb-3">
                    <label for
::contentReference[oaicite:0]{index=0}


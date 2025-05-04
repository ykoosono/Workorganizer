<?php
session_start();
include 'header.php';

// DB connection
$host = 'localhost';
$db = 'workorganizer_db';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB connection failed: " . $e->getMessage());
}

// Validate session and calendar ID
$userId = $_SESSION['user_id'] ?? null;
$calendarId = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$userId) {
    echo "<div class='alert alert-danger'>Please log in to access this calendar.</div>";
    include 'footer.php'; exit;
}
if (!$calendarId) {
    echo "<div class='alert alert-danger'>Invalid calendar ID.</div>";
    include 'footer.php'; exit;
}

// Verify user access
$stmt = $pdo->prepare("SELECT * FROM calendars WHERE id = ? AND user_id = ?");
$stmt->execute([$calendarId, $userId]);
$calendar = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$calendar) {
    echo "<div class='alert alert-danger'>You do not have permission to access this calendar.</div>";
    include 'footer.php'; exit;
}

// Get user role
$stmt = $pdo->prepare("
    SELECT r.role_name
    FROM users_calendars uc
    JOIN roles r ON uc.role_id = r.id
    WHERE uc.user_id = ? AND uc.calendar_id = ?
");
$stmt->execute([$userId, $calendarId]);
$userRole = $stmt->fetchColumn();

if ($userRole === 'viewer') {
    echo "<div class='container mt-5'><div class='alert alert-warning'>You have view-only access to this calendar.</div></div>";
    include 'footer.php'; exit;
}

// Add/Edit task
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST['title'] ?? '');
    $date = $_POST['date'] ?? '';
    $details = $_POST['details'] ?? '';
    $eventId = $_POST['event_id'] ?? null;
    $assignedUserId = $_POST['assigned_user_id'] ?? null;

    if ($title && $date) {
        if ($eventId) {
            $stmt = $pdo->prepare("UPDATE events SET title = ?, date = ?, details = ? WHERE id = ? AND calendar_id = ?");
            $stmt->execute([$title, $date, $details, $eventId, $calendarId]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO events (calendar_id, title, date, details) VALUES (?, ?, ?, ?)");
            $stmt->execute([$calendarId, $title, $date, $details]);
            $eventId = $pdo->lastInsertId();
        }

        // Assign user if provided
        if ($assignedUserId) {
            $stmt = $pdo->prepare("INSERT IGNORE INTO task_assignments (event_id, user_id) VALUES (?, ?)");
            $stmt->execute([$eventId, $assignedUserId]);
        }

        header("Location: view_calendar.php?id=$calendarId");
        exit;
    }
}

// Fetch tasks
$incompleteEvents = $pdo->prepare("SELECT * FROM events WHERE calendar_id = ? AND is_complete = 0 ORDER BY date ASC");
$incompleteEvents->execute([$calendarId]);
$incompleteEvents = $incompleteEvents->fetchAll(PDO::FETCH_ASSOC);

$completeEvents = $pdo->prepare("SELECT * FROM events WHERE calendar_id = ? AND is_complete = 1 ORDER BY date ASC");
$completeEvents->execute([$calendarId]);
$completeEvents = $completeEvents->fetchAll(PDO::FETCH_ASSOC);

// Editing
$editingEvent = null;
if (isset($_GET['edit_event']) && is_numeric($_GET['edit_event'])) {
    $stmt = $pdo->prepare("SELECT * FROM events WHERE id = ? AND calendar_id = ?");
    $stmt->execute([$_GET['edit_event'], $calendarId]);
    $editingEvent = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Calendar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
    <style>
        #calendar { max-width: 900px; margin: auto; font-size: 0.85rem; }
        .fc-event { cursor: pointer; }
    </style>
</head>
<body>
<main class="container mt-5">
    <h2 class="mb-3"><?= htmlspecialchars($calendar['title']) ?></h2>
    <p class="text-muted"><?= htmlspecialchars($calendar['description']) ?></p>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">Calendar</div>
        <div class="card-body">
            <div id="calendar"></div>
        </div>
    </div>

    <script>
    $(function () {
        $('#calendar').fullCalendar({
            events: {
                url: 'fetch_events.php',
                data: { calendar_id: <?= $calendarId ?> },
                error: () => alert('There was an error while fetching events.')
            },
            editable: true,
            droppable: true,
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            eventClick: function(event) {
                alert('Task: ' + event.title + '\nStarts: ' + event.start.format());
            },
            eventDrop: function(event, delta) {
                $.post('update_event.php', {
                    id: event.id,
                    title: event.title,
                    start: event.start.format(),
                    end: event.end ? event.end.format() : null
                }, function(response) {
                    if (response.trim() !== 'success') {
                        alert('Update failed.');
                    }
                });
            }
        });
    });
    </script>

    <!-- Incomplete Tasks -->
    <h4>Incomplete Tasks</h4>
    <?php if ($incompleteEvents): ?>
        <ul class="list-group mb-4">
        <?php foreach ($incompleteEvents as $event): ?>
            <li class="list-group-item d-flex justify-content-between">
                <div>
                    <strong><?= htmlspecialchars($event['title']) ?></strong><br>
                    <small><?= htmlspecialchars($event['date']) ?></small>
                    <p><?= nl2br(htmlspecialchars($event['details'])) ?></p>
                </div>
                <div>
                    <button class="btn btn-sm btn-success toggle-complete" data-event-id="<?= $event['id'] ?>" data-status="1">Complete</button>
                    <a href="?id=<?= $calendarId ?>&edit_event=<?= $event['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                    <a href="?id=<?= $calendarId ?>&delete_event=<?= $event['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete?');">Delete</a>
                </div>
            </li>
        <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No incomplete tasks found.</p>
    <?php endif; ?>

    <!-- Complete Tasks -->
    <h4>Complete Tasks</h4>
    <?php if ($completeEvents): ?>
        <ul class="list-group mb-4">
        <?php foreach ($completeEvents as $event): ?>
            <li class="list-group-item d-flex justify-content-between">
                <div>
                    <strong><?= htmlspecialchars($event['title']) ?></strong><br>
                    <small><?= htmlspecialchars($event['date']) ?></small>
                    <p><?= nl2br(htmlspecialchars($event['details'])) ?></p>
                </div>
                <div>
                    <button class="btn btn-sm btn-secondary toggle-complete" data-event-id="<?= $event['id'] ?>" data-status="0">Undo</button>
                    <a href="?id=<?= $calendarId ?>&edit_event=<?= $event['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                    <a href="?id=<?= $calendarId ?>&delete_event=<?= $event['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete?');">Delete</a>
                </div>
            </li>
        <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No completed tasks found.</p>
    <?php endif; ?>

    <!-- Add/Edit Task -->
    <h4><?= $editingEvent ? 'Edit Task' : 'Add New Task' ?></h4>
    <form method="POST" action="view_calendar.php?id=<?= $calendarId ?>">
        <input type="hidden" name="event_id" value="<?= $editingEvent['id'] ?? '' ?>">
        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" class="form-control" required value="<?= htmlspecialchars($editingEvent['title'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label>Date & Time</label>
            <input type="datetime-local" name="date" class="form-control" required
                value="<?= isset($editingEvent['date']) ? date('Y-m-d\TH:i', strtotime($editingEvent['date'])) : '' ?>">
        </div>
        <div class="mb-3">
            <label>Details</label>
            <textarea name="details" class="form-control"><?= htmlspecialchars($editingEvent['details'] ?? '') ?></textarea>
        </div>
        <div class="mb-3">
            <label>Assign To</label>
            <select name="assigned_user_id" class="form-control">
                <option value="">-- None --</option>
                <?php
                $stmt = $pdo->query("SELECT user_id as id, name FROM users");
                foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $user) {
                    $selected = ($editingEvent['assigned_user_id'] ?? '') == $user['id'] ? 'selected' : '';
                    echo "<option value='{$user['id']}' $selected>{$user['name']}</option>";
                }
                ?>
            </select>
        </div>
        <button class="btn btn-<?= $editingEvent ? 'primary' : 'success' ?>"><?= $editingEvent ? 'Update' : 'Add' ?> Task</button>
        <?php if ($editingEvent): ?>
            <a href="view_calendar.php?id=<?= $calendarId ?>" class="btn btn-secondary">Cancel</a>
        <?php endif; ?>
    </form>

    <hr>
    <div class="mt-4">
        <a href="homepage.php" class="btn btn-primary">Back</a>
        <a href="assign_task_form.php?id=<?= $calendarId ?>" class="btn btn-success">Assign Tasks</a>
        <a href="edit-calendar.php?id=<?= $calendarId ?>" class="btn btn-warning">Edit Calendar</a>
        <a href="add-member.php?id=<?= $calendarId ?>" class="btn btn-info">Add Member</a>
        <a href="remove-member.php?id=<?= $calendarId ?>" class="btn btn-danger">Remove Member</a>
    </div>
</main>

<!-- AJAX for toggling completion -->
<script>
document.querySelectorAll('.toggle-complete').forEach(btn => {
    btn.addEventListener('click', function () {
        const eventId = this.dataset.eventId;
        const status = this.dataset.status;

        fetch('toggle-completion.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `event_id=${eventId}&is_complete=${status}`
        })
        .then(res => res.json())
        .then(data => data.success ? location.reload() : alert('Failed to update task.'));
    });
});
</script>

<?php include 'footer.php'; ?>
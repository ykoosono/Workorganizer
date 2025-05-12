<?php
session_start();
include 'header.php';

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

$stmt = $pdo->prepare("
SELECT * FROM calendars c
JOIN users_calendars uc
ON c.id = uc.calendar_id
WHERE c.id = ? AND uc.user_id = ?");
$stmt->execute([$calendarId, $userId]);
$calendar = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$calendar) {
    echo "<div class='alert alert-danger'>You do not have permission to access this calendar.</div>";
    include 'footer.php'; exit;
}

$stmt = $pdo->prepare("
    SELECT rp.permission_id
    FROM role_permissions rp
    JOIN users_calendars uc
    ON uc.role_id = rp.role_id
    WHERE uc.calendar_id = ? AND uc.user_id = ?
");
$stmt->execute([$calendarId, $userId]);
$userRolePermission = $stmt->fetchAll(PDO::FETCH_COLUMN);

if (in_array(6, $userRolePermission)) {
    echo "<div class='container mt-5'><div class='alert alert-warning'>You have view-only access to this calendar.</div></div>";
}

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

        if ($assignedUserId) {
            $stmt = $pdo->prepare("INSERT IGNORE INTO task_assignments (event_id, user_id) VALUES (?, ?)");
            $stmt->execute([$eventId, $assignedUserId]);
        }

        header("Location: view_calendar.php?id=$calendarId&success=Task saved successfully");
        exit;
    }
}

$incompleteEvents = $pdo->prepare("
    SELECT e.*, u.name AS assigned_to
    FROM events e
    LEFT JOIN task_assignments ta ON e.event_id = ta.event_id
    LEFT JOIN users u ON ta.user_id = u.user_id
    WHERE e.calendar_id = ? AND e.is_complete = 0
    ORDER BY e.date ASC
");
$incompleteEvents->execute([$calendarId]);
$incompleteEvents = $incompleteEvents->fetchAll(PDO::FETCH_ASSOC);

$completeEvents = $pdo->prepare("
    SELECT e.*, u.name AS assigned_to
    FROM events e
    LEFT JOIN task_assignments ta ON e.event_id = ta.event_id
    LEFT JOIN users u ON ta.user_id = u.user_id
    WHERE e.calendar_id = ? AND e.is_complete = 1
    ORDER BY e.date ASC
");
$completeEvents->execute([$calendarId]);
$completeEvents = $completeEvents->fetchAll(PDO::FETCH_ASSOC);


$completeEvents = $pdo->prepare("SELECT * FROM events WHERE calendar_id = ? AND is_complete = 1 ORDER BY date ASC");
$completeEvents->execute([$calendarId]);
$completeEvents = $completeEvents->fetchAll(PDO::FETCH_ASSOC);

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    <style>
        body { background: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        #calendar { max-width: 100%; background: white; padding: 20px; border-radius: 12px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .fc-event { border-radius: 5px; padding: 2px 5px; }
        .card { border-radius: 1rem; box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05); }
        .btn-sm { margin: 2px; }
        .form-control, .btn { border-radius: 0.5rem; }
    </style>
</head>
<body>
<main class="container mt-5">
    <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_GET['success']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <h2 class="mb-3"><?= htmlspecialchars($calendar['title']) ?></h2>
    <p class="text-muted"><?= htmlspecialchars($calendar['description']) ?></p>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">Calendar</div>
                <div class="card-body">
                    <div id="calendar"></div>
                    <div class="mt-3">
                        <h6>Legend:</h6>
                        <div class="d-flex align-items-center mb-2">
                            <div style="width: 20px; height: 20px; background-color: #28a745; margin-right: 10px;"></div>
                            <span>Completed Task</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <div style="width: 20px; height: 20px; background-color: #ffc107; margin-right: 10px;"></div>
                            <span>Incomplete Task</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <h4>Incomplete Tasks</h4>
            <?php if ($incompleteEvents): ?>
                <ul class="list-group mb-4">
                <?php foreach($incompleteEvents as $event): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-start animate__animated animate__fadeIn">
                        <div>
                            <strong><?= htmlspecialchars($event['title']) ?></strong><br>
                            <small><?= htmlspecialchars($event['date']) ?></small>
                            <p><?= nl2br(htmlspecialchars($event['details'])) ?></p>
                            <?php if (!empty($event['assigned_to'])): ?>
                                <small class="text-muted">Assigned to: <?= htmlspecialchars($event['assigned_to']) ?></small>
                            <?php endif; ?>

                        </div>
                        <div>
                        <?php if(in_array(5, $userRolePermission)) {
                            echo '<button class="btn btn-sm btn-success toggle-complete" data-event-id="'.$event['id'].'" data-status="1" title="Mark Complete">
                                <i class="bi bi-check-circle"></i>
                            </button>';
                            }

                             if(in_array(4, $userRolePermission)) {
                            echo '<a href="?id='.$calendarId.'&edit_event='.$event['id'].'" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>';
                             }

                            if(in_array(8, $userRolePermission)) {
                            echo '<a href="?id='.$calendarId.'&delete_event='.$event['id'].'" class="btn btn-sm btn-outline-danger" title="Delete">
                                <i class="bi bi-trash"></i>
                            </a>';
                            }
                            ?>
                        </div>
                    </li>
                <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No incomplete tasks found.</p>
            <?php endif; ?>

            <h4>Completed Tasks</h4>
            <?php if ($completeEvents): ?>
                <ul class="list-group mb-4">
                <?php foreach ($completeEvents as $event): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-start animate__animated animate__fadeIn">
                        <div>
                            <strong><?= htmlspecialchars($event['title']) ?></strong><br>
                            <small><?= htmlspecialchars($event['date']) ?></small>
                           <p><?= nl2br(htmlspecialchars($event['details'])) ?></p>
                           <?php if (!empty($event['assigned_to'])): ?>
                               <small class="text-muted">Assigned to: <?= htmlspecialchars($event['assigned_to']) ?></small>
                           <?php endif; ?>

                        </div>
                        <div>
                        <?php if(in_array(5, $userRolePermission)) {?>
                            <button class="btn btn-sm btn-secondary toggle-complete" data-event-id="<?= $event['id'] ?>" data-status="0" title="Mark Incomplete">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </button>
                            <?php } ?>
                            <?php

                             if(in_array(4, $userRolePermission)) {
                             echo "Hello World!";
                            echo '<a href="?id='.$calendarId.'&edit_event='.$event['id'].'" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>';
                            }
                            if(in_array(8, $userRolePermission)) {
                                                        echo '<a href="?id='.$calendarId.'&delete_event='.$event['id'].'" class="btn btn-sm btn-outline-danger" title="Delete">
                                                            <i class="bi bi-trash"></i>
                                                        </a>';
                            }
                            ?>


                        </div>
                    </li>
                <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No completed tasks found.</p>
            <?php endif; ?>
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
            eventDrop: function(event) {
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
    <?php

    if(in_array(7, $userRolePermission)){
    ?>
    <h4><?= $editingEvent ? 'Edit Task' : 'Add New Task'
    ?>
    </h4>
    <form method="POST" action="view_calendar.php?id=<?= $calendarId ?>">
        <input type="hidden" name="event_id" value="<?= $editingEvent['id'] ?? '' ?>">
        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" class="form-control" required value="<?= htmlspecialchars($editingEvent['title'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label>Date & Time</label>
            <input type="datetime-local" name="date" class="form-control" required
                value="<?= isset($editingEvent['date']) ? date('Y-m-d\TH:i', strtotime($editingEvent['date'])) : '' ?>"
                min="<?= date('Y-m-d\TH:i') ?>">
        </div>
        <div class="mb-3">
            <label>Details</label>
            <textarea name="details" class="form-control"><?= htmlspecialchars($editingEvent['details'] ?? '') ?></textarea>
        </div>
        <?php
            if(in_array(3, $userRolePermission)) {

           ?>
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
        <?php
        }
        ?>
            <button class="btn btn-<?= $editingEvent ? 'primary' : 'success' ?>"><?= $editingEvent ? 'Update' : 'Add' ?> Task</button>
            <?php if ($editingEvent): ?>
                <a href="view_calendar.php?id=<?= $calendarId ?>" class="btn btn-secondary">Cancel</a>
            <?php endif; ?>

    </form>
<?php }; ?>
    <hr>
    <div class="mt-4 d-flex flex-wrap gap-2">
        <a href="homepage.php" class="btn btn-outline-primary">Back</a>
        <?php if(in_array(1, $userRolePermission)) { ?>
            <a href="add-member.php?id=<?= $calendarId ?>" class="btn btn-outline-info">Add Member</a>
            <?php if(in_array(2, $userRolePermission)) { ?>
                <a href="remove-member.php?id=<?= $calendarId ?>" class="btn btn-outline-danger">Remove Member</a>
            <?php } } ?>
    </div>
</main>

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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">Confirm Deletion</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this task?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Delete</a>
      </div>
    </div>
  </div>
</div>
<script>
document.querySelectorAll('.btn-outline-danger[href*="delete_event"]').forEach(btn => {
    btn.addEventListener('click', function (e) {
        e.preventDefault();
        document.getElementById('confirmDeleteBtn').href = this.href;
        new bootstrap.Modal(document.getElementById('confirmDeleteModal')).show();
    });
});
</script>

<?php include 'footer.php'; ?>
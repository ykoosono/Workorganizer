<?php include 'header.php'; ?>
<?php
// Database connection (Put this at the very beginning)
$host = 'localhost';       // Change as needed
$db = 'workorganizer_db';  // Change to your database name
$user = 'root';            // Change to your database username
$pass = '';                // Change to your database password

try {
    // Establish PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // Enable error mode
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Other code goes here...
?>

<?php
session_start();
$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    echo "<div class='alert alert-danger'>Please log in to access this calendar.</div>";
    exit;
}

$calendarId = $_GET['id'] ?? null;
if (!$calendarId || !is_numeric($calendarId)) {
    echo "<div class='alert alert-danger'>Invalid calendar ID.</div>";
    exit;
}
echo $userId;
echo $calendarId;
// Check if the calendar belongs to the user
$stmt = $pdo->prepare("SELECT * FROM calendars WHERE id = ? AND user_id = ?");
$stmt->execute([$calendarId, $userId]);
$calendar = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$calendar) {
    echo "<div class='alert alert-danger'>You do not have permission to access this calendar.</div>";
    exit;
}


// Fetch user role
$stmt = $pdo->prepare("SELECT r.role_name FROM users_calendars uc JOIN roles r ON uc.role_id = r.id WHERE uc.user_id = ? AND uc.calendar_id = ?");
$stmt->execute([$userId, $calendarId]);
$userRole = $stmt->fetchColumn();

// Role-based access control
if ($userRole === 'viewer') {
    echo "<div class='container mt-5'><div class='alert alert-warning'>You have view-only access to this calendar.</div></div>";
    include 'footer.php';
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'] ?? '';
    $date = $_POST['date'] ?? '';
    $details = $_POST['details'] ?? '';
    $eventId = $_POST['event_id'] ?? null;
    $assignedUserId = $_POST['assigned_user_id'] ?? null; // New field for assigned user

    if (!empty($title) && !empty($date)) {
        if ($eventId) {
            // Update event
            $stmt = $pdo->prepare("UPDATE events SET title=?, date=?, details=? WHERE id=? AND calendar_id=?");
            $stmt->execute([$title, $date, $details, $eventId, $calendarId]);
        } else {
            // Insert new event
            $stmt = $pdo->prepare("INSERT INTO events (calendar_id, title, date, details) VALUES (?, ?, ?, ?)");
            $stmt->execute([$calendarId, $title, $date, $details]);
            $eventId = $pdo->lastInsertId();
        }

        // Assign user to event
        if ($assignedUserId) {
            $stmt = $pdo->prepare("INSERT INTO task_assignments (event_id, user_id) VALUES (?, ?)");
            $stmt->execute([$eventId, $assignedUserId]);
        }

        header("Location: view-calendar.php?id=$calendarId");
        exit;
    }
}

// Fetch incomplete events
$incompleteStmt = $pdo->prepare("SELECT * FROM events WHERE calendar_id = ? AND is_complete = 0 ORDER BY date ASC");
$incompleteStmt->execute([$calendarId]);
$incompleteEvents = $incompleteStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch complete events
$completeStmt = $pdo->prepare("SELECT * FROM events WHERE calendar_id = ? AND is_complete = 1 ORDER BY date ASC");
$completeStmt->execute([$calendarId]);
$completeEvents = $completeStmt->fetchAll(PDO::FETCH_ASSOC);


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
<!-- Include FullCalendar CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.css" rel="stylesheet" />

<!-- Include jQuery and FullCalendar JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>

<div id="calendar"></div>

<script>
$(document).ready(function() {
    $('#calendar').fullCalendar({
        events: 'get_tasks.php',
        editable: true,
        droppable: true,
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        defaultView: 'month',
        eventClick: function(event) {
            alert('Task: ' + event.title + '\nStarts: ' + event.start.format());
        },
        eventDrop: function(event, delta) {
            alert(event.title + ' was moved ' + delta + ' days');
        }
    });
});
</script>
<script>
$(document).ready(function() {
    var calendar = $('#calendar').fullCalendar({
        events: 'get_events.php',
        editable: true,
        droppable: true,
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        defaultView: 'month',
        eventClick: function(event) {
            if (confirm('Are you sure you want to delete this event?')) {
                $.ajax({
                    url: 'delete_event.php',
                    type: 'POST',
                    data: { id: event.id },
                    success: function(response) {
                        if (response.trim() === 'success') {
                            $('#calendar').fullCalendar('removeEvents', event.id);
                        } else {
                            alert('Failed to delete event.');
                        }
                    }
                });
            }
        },
        eventDrop: function(event) {
            $.ajax({
                url: 'update_event.php',
                type: 'POST',
                data: {
                    id: event.id,
                    title: event.title,
                    start: event.start.format(),
                    end: event.end.format()
                },
                success: function(response) {
                    if (response.trim() !== 'success') {
                        alert('Failed to update event.');
                    }
                }
            });
        },
        select: function(start, end) {
            var title = prompt('Enter event title:');
            if (title) {
                $.ajax({
                    url: 'insert_event.php',
                    type: 'POST',
                    data: {
                        title: title,
                        start: start.format(),
                        end: end.format()
                    },
                    success: function(response) {
                        if (response.trim() === 'success') {
                            $('#calendar').fullCalendar('refetchEvents');
                        } else {
                            alert('Failed to add event.');
                        }
                    }
                });
            }
            $('#calendar').fullCalendar('unselect');
        }
    });
});
</script>


  <main class="flex-grow-1">
  <div class="card">
    <div class="card-header bg-primary text-white">
      My Calendar Widget
    </div>
    <div class="card-body">
      <div id="calendar"></div>
    </div>
  </div>
<style>
#calendar {
    max-width: 800px;
    margin: 0 auto;
    font-size: 0.85rem;
}
.fc {
    font-family: "Segoe UI", sans-serif;
}
.fc-event {
    cursor: pointer;
}
</style>


<div id="calendar"></div>

  <div id="calendar"></div>

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
                <a href="?id=<?php echo $calendarId; ?>&edit_event=<?php echo $event['id'].'#edit_form'; ?>" class="btn btn-outline-primary">Edit</a>
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
      <h4 class="mb-3" id=<?php echo $editingEvent ? 'edit_form':'' ?>><?php echo $editingEvent ? 'Edit Task' : 'Add a New Task'; ?></h4>
      <form method="POST" action=<?php echo $editingEvent ? 'update_assignment.php': '' ?>>
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
        <div class="mb-3">
          <label for="assigned_user_id" class="form-label">Assign User</label>
          <select class="form-control" id="assigned_user_id" name="assigned_user_id">

            <?php
            $stmt = $pdo->prepare("SELECT user_id as id, name as username FROM users");
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($users as $user) {
              $selected = ($editingEvent && $editingEvent['assigned_user_id'] == $user['id']) ? 'selected' : '';
              echo "<option value='{$user['id']}' $selected>{$user['username']}</option>";
            }
            ?>
          </select>
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
        <a href="assign_task_form.php?id=<?php echo $calendarId; ?>" class="btn btn-success">Assign Tasks</a>
        <a href="edit-calendar.php?id=<?php echo $calendarId; ?>" class="btn btn-warning">Edit Calendar</a>
        <a href="add-member.php?id=<?php echo $calendarId; ?>" class="btn btn-success">Add Member</a>
        <a href="remove-member.php?id=<?php echo $calendarId; ?>" class="btn btn-danger">Remove Member</a>
      </div>
    </div>


  </main>
  <?php include 'footer.php'; ?>
</div>
<div id="calendar"></div>

<script>
$(document).ready(function() {
    $('#calendar').fullCalendar({
        events: 'get_events.php',
        editable: true,
        droppable: true, // Enable dragging and dropping
        eventDrop: function(event, delta) {
            // Handle event drop (e.g., update event in database)
            alert(event.title + ' was moved ' + delta + ' days');
        },
        eventClick: function(calEvent, jsEvent, view) {
            // Handle event click (e.g., show event details)
            alert('Event: ' + calEvent.title);
        }
    });
});
</script>

<!-- AJAX Script for Toggle Completion -->
<script>
document.querySelectorAll('.toggle-complete').forEach(button => {
  button.addEventListener('click', function () {
    const eventId = this.getAttribute('data-event-id');
    const status = this.getAttribute('data-status');

    fetch('toggle-completion.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `event_id=${eventId}&is_complete=${status}`
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        location.reload(); // Refresh the page to reflect changes
      } else {
        alert('Error updating task.');
      }
    })
    .catch(error => {
      console.error('Error:', error);
    });
  });
});
</script>




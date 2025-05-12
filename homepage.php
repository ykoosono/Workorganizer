<?php
include 'header.php';
include('DBConnect.php');
$message = openDB(); // initializes $conn

if ($message !== "Connected") {
    die("DB error: $message");
}
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = (int) $_SESSION['user_id'];
$sort = $_GET['sort'] ?? 'default';
$query = $_GET['query'] ?? '';
$roleFilter = $_GET['role'] ?? '';
$likeQuery = '%' . $query . '%';

$sortOptions = [
    'alpha' => 'title ASC',
    'recent' => 'id DESC',
    'default' => 'id ASC',
];

$orderBy = $sortOptions[$sort] ?? $sortOptions['default'];

$sql = "
    SELECT * FROM (
        SELECT c.id, c.title, c.description, 'Lead' AS role_label
        FROM users_calendars uc
        JOIN calendars c ON uc.calendar_id = c.id
        WHERE uc.user_id = ? AND uc.role_id = 1 AND c.title LIKE ?

        UNION ALL

        SELECT c.id, c.title, c.description, 'Member' AS role_label
        FROM users_calendars uc
        JOIN calendars c ON uc.calendar_id = c.id
        WHERE uc.user_id = ? AND uc.role_id = 2 AND c.title LIKE ?

        UNION ALL

        SELECT c.id, c.title, c.description, 'Viewer' AS role_label
        FROM users_calendars uc
        JOIN calendars c ON uc.calendar_id = c.id
        WHERE uc.user_id = ? AND uc.role_id = 3 AND c.title LIKE ?
    ) AS all_calendars
    ORDER BY $orderBy
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("isisis", $userId, $likeQuery, $userId, $likeQuery, $userId, $likeQuery);
$stmt->execute();
$result = $stmt->get_result();

// Filter by role if specified
$calendars = [];
while ($row = $result->fetch_assoc()) {
    if ($roleFilter === '' || $row['role_label'] === $roleFilter) {
        $calendars[] = $row;
    }
}




// Get the logged-in user's ID
$user_id = $_SESSION['user_id'];
?>
<script>
$(document).ready(function() {
    $('#calendar').fullCalendar({
        height: 'auto', // Adjusts height to content
        contentHeight: 400, // Max visible height
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        defaultView: 'month', // Default view
        editable: true,
        droppable: true, // Enable dragging and dropping
        events: 'get_events.php', // Fetch events from your PHP script

        eventClick: function(event) {
            alert('Event: ' + event.title + '\nStarts: ' + event.start.format());
        },

        eventDrop: function(event, delta) {
            alert(event.title + ' was moved ' + delta + ' days');
        }
    });
});
</script>

<div class="d-flex flex-column min-vh-100">
  <main class="flex-grow-1">
  <!-- FullCalendar CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.css" rel="stylesheet" />

  <!-- jQuery and FullCalendar JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
   <div id="calendar"></div>
   <style>
   #calendar {
       max-width: 600px;
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

    <div class="container mt-5">
      <h1 class="mb-4">Welcome <?php echo $_SESSION['name'] ?></h1>
      <h2 class="mb-4">My Calendars</h2>

      <!-- Functional Buttons -->
<div class="row mb-4 align-items-end">
  <!-- Add New Calendar Button -->
  <div class="col-lg-3 mb-2">
    <a href="addCalendar.php" class="btn btn-success w-100">
      <i class="bi bi-plus-circle"></i> Add New Calendar
    </a>
  </div>

  <!-- Sort Dropdown -->
  <div class="col-lg-3 mb-2">
    <form method="get" class="d-flex">
      <select class="form-select me-2" name="sort">
        <option value="default" <?= $sort === 'default' ? 'selected' : '' ?>>Sort: Default</option>
        <option value="recent" <?= $sort === 'recent' ? 'selected' : '' ?>>Sort: Most Recent</option>
        <option value="alpha" <?= $sort === 'alpha' ? 'selected' : '' ?>>Sort: Alphabetically</option>
      </select>
  </div>

  <!-- Role Filter -->
  <div class="col-lg-3 mb-2">
      <select class="form-select me-2" name="role">
        <option value="">All Roles</option>
        <option value="Lead" <?= $roleFilter === 'Lead' ? 'selected' : '' ?>>Team Lead</option>
        <option value="Member" <?= $roleFilter === 'Member' ? 'selected' : '' ?>>Member</option>
        <option value="Viewer" <?= $roleFilter === 'Viewer' ? 'selected' : '' ?>>Viewer</option>
      </select>
  </div>

  <!-- Search Field -->
  <div class="col-lg-3 mb-2">
      <input class="form-control me-2" type="search" name="query" placeholder="Search calendars..." value="<?= htmlspecialchars($query) ?>">
  </div>

  <!-- Submit Button -->
  <div class="col-lg-12 mt-2">
      <button class="btn btn-primary w-100" type="submit">Apply Filters</button>
    </form>
  </div>
</div>


      <!-- Calendars grid -->
      <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php
        function renderCalendarCard($calendarId, $calendarName, $roleLabel, $description) {
            echo '
            <div class="col">
              <div class="card shadow-sm h-100">
                <div class="card-body">
                  <h5 class="card-title">' . htmlspecialchars($calendarName) . '</h5>
                  <p class="card-text"><strong>Role:</strong> ' . htmlspecialchars($roleLabel) . '</p>
                  <p class="card-text">' . nl2br(htmlspecialchars($description)) . '</p>
                  <a href="view_calendar.php?id=' . urlencode($calendarId) . '" class="btn btn-primary">View Calendar</a>
                </div>
              </div>
            </div>';
        }

        // Loop through the result set and render the cards
        foreach ($calendars as $row) {
            renderCalendarCard($row['id'], $row['title'], $row['role_label'], $row['description']);
        }
        ?>
      </div>
    </div>
  </main>

  <?php include 'footer.php'; ?>
</div>


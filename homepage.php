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
$likeQuery = '%' . $query . '%';

$sortOptions = [
    'alpha' => 'title ASC',
    'recent' => 'id DESC',
    'default' => 'id ASC',
];

$orderBy = $sortOptions[$sort] ?? $sortOptions['default'];

$sql = "
    SELECT * FROM (
        SELECT c.id, c.title, 'Lead' AS role_label
        FROM users_calendars uc
        JOIN calendars c ON uc.calendar_id = c.id
        WHERE uc.user_id = ? AND uc.role_id = 1 AND c.title LIKE ?

        UNION ALL

        SELECT c.id, c.title, 'Member' AS role_label
        FROM users_calendars uc
        JOIN calendars c ON uc.calendar_id = c.id
        WHERE uc.user_id = ? AND uc.role_id = 2 AND c.title LIKE ?

        UNION ALL

        SELECT c.id, c.title, 'Viewer' AS role_label
        FROM users_calendars uc
        JOIN calendars c ON uc.calendar_id = c.id
        WHERE uc.user_id = ? AND uc.role_id = 3 AND c.title LIKE ?
    ) AS all_calendars
    ORDER BY $orderBy
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("isisis", $userId, $likeQuery, $userId, $likeQuery, $userId, $likeQuery);
$stmt->execute();
$calendars = $stmt->get_result();

// Debugging step: Check the SQL query result.
if ($calendars->num_rows == 0) {
    echo "<p>No calendars found. Please check your data and query parameters.</p>";
} else {
    echo "<p>Calendars found: " . $calendars->num_rows . "</p>";
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
      <div class="row mb-4 align-items-center">
        <div class="col-md-4 mb-2 mb-md-0">
          <a href="addCalendar.php" class="btn btn-success w-100">
            <i class="bi bi-plus-circle"></i> Add New Calendar
          </a>
        </div>

        <div class="col-md-4 mb-2 mb-md-0">
          <div class="dropdown w-100">
            <button class="btn btn-outline-secondary dropdown-toggle w-100" type="button" data-bs-toggle="dropdown" aria-expanded="false">
              Sort Calendars
            </button>
            <ul class="dropdown-menu w-100">
              <li><a class="dropdown-item <?= $sort === 'default' ? 'active' : '' ?>" href="?sort=default&query=<?= urlencode($query) ?>">Default</a></li>
              <li><a class="dropdown-item <?= $sort === 'recent' ? 'active' : '' ?>" href="?sort=recent&query=<?= urlencode($query) ?>">Most Recent</a></li>
              <li><a class="dropdown-item <?= $sort === 'alpha' ? 'active' : '' ?>" href="?sort=alpha&query=<?= urlencode($query) ?>">Alphabetically</a></li>
            </ul>
          </div>
        </div>

        <div class="col-md-4">
          <form class="d-flex" action="" method="get">
            <input class="form-control me-2" type="search" name="query" placeholder="Search calendars..." value="<?= htmlspecialchars($query) ?>" aria-label="Search">
            <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">
            <button class="btn btn-primary" type="submit">Search</button>
          </form>
        </div>
      </div>

      <!-- Calendars grid -->
      <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php
        function renderCalendarCard($calendarId, $calendarName, $roleLabel) {
            echo '
            <div class="col">
              <div class="card shadow-sm h-100">
                <div class="card-body">
                  <h5 class="card-title">'.htmlspecialchars($calendarName).'</h5>
                  <p class="card-text">Role: '.htmlspecialchars($roleLabel).'</p>
                  <a href="view-calendar.php?id=' . urlencode($calendarId) . '" class="btn btn-primary">View Calendar</a>
                </div>
              </div>
            </div>';
        }

        // Loop through the result set and render the cards
        while ($row = $calendars->fetch_assoc()) {
            renderCalendarCard($row['id'], $row['title'], $row['role_label']);
        }
        ?>
      </div>
    </div>
  </main>

  <?php include 'footer.php'; ?>
</div>

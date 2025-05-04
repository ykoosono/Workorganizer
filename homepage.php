<?php
include 'header.php';

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Database connection details
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
              <li><a class="dropdown-item" href="?sort=recent">Most Recent</a></li>
              <li><a class="dropdown-item" href="?sort=alpha">Alphabetically</a></li>
            </ul>
          </div>
        </div>

        <div class="col-md-4">
          <form class="d-flex" action="search-calendars.php" method="get">
            <input class="form-control me-2" type="search" name="query" placeholder="Search calendars..." aria-label="Search">
            <button class="btn btn-primary" type="submit">Search</button>
          </form>
        </div>
      </div>

      <!-- Calendars grid -->
      <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php
        // Fetch calendars that belong to the logged-in user
        $stmt = $pdo->prepare("SELECT * FROM calendars WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($results):
            foreach ($results as $row):
        ?>
                <div class="col">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($row['description']); ?></p>
                            <!-- Ensure the link goes to view_calendar.php with the correct calendar ID -->
                            <a href="view_calendar.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">View Calendar</a>
                        </div>
                    </div>
                </div>
        <?php
            endforeach;
        else:
        ?>
            <p>No calendars found.</p>
        <?php endif; ?>
      </div>
    </div>
  </main>

  <?php include 'footer.php'; ?>
</div>

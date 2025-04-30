<?php
include('DBConnect.php'); 
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = (int) $_SESSION['user_id'];

$stmt1 = $conn->prepare("SELECT c.calendar_name 
                         FROM users_calendars uc 
                         JOIN calendar c ON uc.calendar_id = c.id
                         WHERE uc.user_id = ? AND uc.role_id = 1");
$stmt1->bind_param("i", $userId);
$stmt1->execute();
$leadCalendars = $stmt1->get_result();

$stmt2 = $conn->prepare("SELECT c.calendar_name 
                         FROM users_calendars uc 
                         JOIN calendar c ON uc.calendar_id = c.id
                         WHERE uc.user_id = ? AND uc.role_id = 2");
$stmt2->bind_param("i", $userId);
$stmt2->execute();
$memberCalendars = $stmt2->get_result();

$stmt3 = $conn->prepare("SELECT c.calendar_name 
                         FROM users_calendars uc 
                         JOIN calendar c ON uc.calendar_id = c.id
                         WHERE uc.user_id = ? AND uc.role_id = 3");
$stmt3->bind_param("i", $userId);
$stmt3->execute();
$viewerCalendars = $stmt3->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Home | WorkOrganizer</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body style="background: linear-gradient(135deg, #c0d6e4, #f0f4f8); min-height: 100vh;">
<nav class="navbar navbar-expand-sm navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="homepage.php">Home</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mynavbar">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link" href="addCalendar.php">New Calendar</a>
        </li>
      </ul>
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link btn btn-danger text-white px-3" href="signout.php">Sign Out</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-5">
  <h1>Your Calendars</h1>

  <h3>Lead Calendars</h3>
  <ul>
    <?php while ($row = $leadCalendars->fetch_assoc()): ?>
      <li><?php echo htmlspecialchars($row['calendar_name']); ?></li>
    <?php endwhile; ?>
  </ul>

  <h3>Member Calendars</h3>
  <ul>
    <?php while ($row = $memberCalendars->fetch_assoc()): ?>
      <li><?php echo htmlspecialchars($row['calendar_name']); ?></li>
    <?php endwhile; ?>
  </ul>

  <h3>Viewer Calendars</h3>
    <ul>
    <?php while ($row = $viewerCalendars->fetch_assoc()): ?>
      <li><?php echo htmlspecialchars($row['calendar_name']); ?></li>
    <?php endwhile; ?>
  </ul>
</div>

<footer class="text-center mt-5 p-3 bg-light">
  <p>&copy; 2025 WorkOrganizer. All rights reserved.</p>
</footer>
</body>
</html>
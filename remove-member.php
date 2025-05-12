<!DOCTYPE html>
<html lang="en">
<head>
  <!-- your existing head content -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    html, body {
      height: 100%;
    }
    body {
      display: flex;
      flex-direction: column;
    }
    main {
      flex: 1 0 auto; /* Allows main content to grow */
    }
    footer {
      flex-shrink: 0; /* Prevents footer from shrinking */
    }
  </style>
</head>
<body>
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$host = 'localhost';
$db   = 'workorganizer_db';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$calendarId = $_GET['id'] ?? null;
$userId     = $_SESSION['user_id'];

// Ensure user is team lead
$checkStmt = $pdo->prepare("
    SELECT uc.role_id, r.role_name
    FROM users_calendars uc
    JOIN roles r ON uc.role_id = r.id
    WHERE uc.user_id = ? AND uc.calendar_id = ?
");
$checkStmt->execute([$userId, $calendarId]);
$role = $checkStmt->fetch(PDO::FETCH_ASSOC);

if (!$role || strtolower($role['role_name']) !== 'team lead') {
    echo "<div class='alert alert-danger'>You must be a team lead to remove members from this calendar.</div>";
    exit;
}

$success = '';
$error = '';

//Handle user removal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_user_id'])) {
    $removeUserId = $_POST['remove_user_id'];

    // Prevent team lead from deleting themselves
    if ($removeUserId == $userId) {
        $error = "You cannot remove yourself from the calendar.";
    } else {
        $deleteStmt = $pdo->prepare("DELETE FROM users_calendars WHERE calendar_id = ? AND user_id = ?");
        $deleteStmt->execute([$calendarId, $removeUserId]);
        $success = "User removed from the calendar.";
    }
}

// Fetch current members
$membersStmt = $pdo->prepare("
    SELECT u.user_id, u.name, r.role_name
    FROM users u
    JOIN users_calendars uc ON u.user_id = uc.user_id
    JOIN roles r ON uc.role_id = r.id
    WHERE uc.calendar_id = ?
");
$membersStmt->execute([$calendarId]);
$members = $membersStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'header.php'; ?>

<div class="container mt-5">
    <h3>Remove Members from Calendar</h3>

    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <hr>
    <h4>Current Members</h4>
    <ul class="list-group">
        <?php foreach ($members as $member): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <?php echo htmlspecialchars($member['name']) . ' - ' . htmlspecialchars($member['role_name']); ?>
                <?php if ($member['user_id'] != $userId): ?>
                    <form method="POST" style="margin: 0;">
                        <input type="hidden" name="remove_user_id" value="<?php echo $member['user_id']; ?>">
                        <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                    </form>
                <?php else: ?>
                    <span class="badge bg-secondary">You</span>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <a href="view_calendar.php?id=<?php echo $calendarId; ?>" class="btn btn-secondary mt-4">Back</a>
</div>

<?php include 'footer.php'; ?>

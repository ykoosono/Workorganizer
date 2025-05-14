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

// 🔒 Check if current user is team lead of this calendar
$checkStmt = $pdo->prepare("
    SELECT uc.role_id, r.role_name
    FROM users_calendars uc
    JOIN roles r ON uc.role_id = r.id
    WHERE uc.user_id = ? AND uc.calendar_id = ?
");
$checkStmt->execute([$userId, $calendarId]);
$role = $checkStmt->fetch(PDO::FETCH_ASSOC);

if (!$role || strtolower($role['role_name']) == 'Viewer') {
    echo "<div class='alert alert-danger'>You must be a team lead to add members to this calendar.</div>";
    exit;
}

$success = '';
$error = '';

// Handle member addition
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newUserId = $_POST['user_id'] ?? null;
    $newRoleId = $_POST['role_id'] ?? null;

    // Check if already assigned
    $existsStmt = $pdo->prepare("SELECT * FROM users_calendars WHERE calendar_id = ? AND user_id = ?");
    $existsStmt->execute([$calendarId, $newUserId]);
    if ($existsStmt->rowCount() > 0) {
        $error = 'This user is already a member of the calendar.';
    } else {
        $insertStmt = $pdo->prepare("INSERT INTO users_calendars (calendar_id, user_id, role_id) VALUES (?, ?, ?)");
        $insertStmt->execute([$calendarId, $newUserId, $newRoleId]);
        $success = 'User successfully added to the calendar!';
    }
}

// Fetch all users
$users = $pdo->query("SELECT user_id, name FROM users")->fetchAll(PDO::FETCH_ASSOC);

// Fetch all roles
$roles = $pdo->query("SELECT id, role_name FROM roles")->fetchAll(PDO::FETCH_ASSOC);

// Fetch existing members
$membersStmt = $pdo->prepare("
    SELECT u.name, r.role_name
    FROM users u
    JOIN users_calendars uc ON u.user_id = uc.user_id
    JOIN roles r ON uc.role_id = r.id
    WHERE uc.calendar_id = ?
");
$membersStmt->execute([$calendarId]);
$members = $membersStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'header.php'; ?>
<div id="wrap">
<div class="container mt-5">
    <h3>Add Member to Calendar</h3>

    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" class="mb-4">
        <div class="mb-3">
            <label for="user_id" class="form-label">Select User</label>
            <select name="user_id" id="user_id" class="form-select" required>
                <option value="">-- Select User --</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?php echo $user['user_id']; ?>"><?php echo htmlspecialchars($user['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="role_id" class="form-label">Assign Role</label>
            <select name="role_id" id="role_id" class="form-select" required>
                <option value="">-- Select Role --</option>
                <?php foreach (array_slice($roles, 1) as $role): ?>
                <option value="<?php echo $role['id']; ?>"><?php echo htmlspecialchars($role['role_name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Add Member</button>
        <a href="view_calendar.php?id=<?php echo $calendarId; ?>" class="btn btn-secondary">Back</a>
    </form>

    <hr>
    <h4>Current Members</h4>
    <ul class="list-group">
        <?php foreach ($members as $member): ?>
            <li class="list-group-item d-flex justify-content-between">
                <?php echo htmlspecialchars($member['name']) . ' - ' . htmlspecialchars($member['role_name']); ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
</div>
<div id="main"></div>

<?php include 'footer.php'; ?>
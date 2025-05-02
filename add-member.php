<?php
include 'header.php';
session_start();

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

$calendarId = $_GET['id'] ?? null;
$currentUserId = $_SESSION['user_id'] ?? null;

if (!$calendarId || !$currentUserId) {
    echo "<div class='container mt-5 alert alert-danger'>Missing calendar or user information.</div>";
    include 'footer.php';
    exit;
}
// Fetch the current user's role for the calendar
$stmt = $pdo->prepare("SELECT r.role_name FROM users_calendars uc
    JOIN roles r ON uc.role_id = r.id
    WHERE uc.calendar_id = ? AND uc.user_id = ?");
$stmt->execute([$calendarId, $currentUserId]);
$currentRole = $stmt->fetchColumn();

// Check if the user has permission to add members
if (!in_array($currentRole, ['Lead Member', 'Team Member'])) {
    echo "<div class='container mt-5 alert alert-warning'>You do not have permission to add members.</div>";
    include 'footer.php';
    exit;
}

// Get current user's role on the calendar
$stmt = $pdo->prepare("SELECT r.role_name FROM users_calendars uc
    JOIN roles r ON uc.role_id = r.id
    WHERE uc.calendar_id = ? AND uc.user_id = ?");
$stmt->execute([$calendarId, $currentUserId]);
$currentRole = $stmt->fetchColumn();

if (!in_array($currentRole, ['Lead Member', 'Team Member'])) {
    echo "<div class='container mt-5 alert alert-warning'>You do not have permission to add members.</div>";
    include 'footer.php';
    exit;
}

// Fetch all roles for the dropdown
$rolesStmt = $pdo->query("SELECT id, role_name FROM roles");
$roles = $rolesStmt->fetchAll(PDO::FETCH_ASSOC);

$successMsg = $errorMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $roleId = $_POST['role_id'] ?? '';

    if (filter_var($email, FILTER_VALIDATE_EMAIL) && is_numeric($roleId)) {
        // Check if user exists
        $userStmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
        $userStmt->execute([$email]);
        $user = $userStmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $newUserId = $user['user_id'];

            // Check if already a member
            $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM users_calendars WHERE calendar_id = ? AND user_id = ?");
            $checkStmt->execute([$calendarId, $newUserId]);
            if ($checkStmt->fetchColumn() == 0) {
                $insertStmt = $pdo->prepare("INSERT INTO users_calendars (calendar_id, user_id, role_id) VALUES (?, ?, ?)");
                $insertStmt->execute([$calendarId, $newUserId, $roleId]);
                $successMsg = "Member added successfully.";
            } else {
                $errorMsg = "This user is already a member of the calendar.";
            }
        } else {
            $errorMsg = "No user found with that email.";
        }
    } else {
        $errorMsg = "Please enter a valid email and select a role.";
    }
}
?>


<div class="container mt-5">
    <h3>Add Member to Calendar</h3>

    <?php if ($successMsg): ?>
        <div class="alert alert-success"><?php echo $successMsg; ?></div>
    <?php elseif ($errorMsg): ?>
        <div class="alert alert-danger"><?php echo $errorMsg; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="email" class="form-label">User Email</label>
            <input type="email" name="email" class="form-control" required placeholder="Enter user's email">
        </div>

        <div class="mb-3">
            <label for="role_id" class="form-label">Assign Role</label>
            <select name="role_id" class="form-select" required>
                <option value="">Select a role</option>
                <?php foreach ($roles as $role): ?>
                    <option value="<?php echo $role['id']; ?>"><?php echo htmlspecialchars($role['role_name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Add Member</button>
        <a href="view-calendar.php?id=<?php echo $calendarId; ?>" class="btn btn-secondary">Back</a>
    </form>
</div>

<?php include 'footer.php'; ?>

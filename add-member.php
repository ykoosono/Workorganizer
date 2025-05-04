<?php
include 'header.php';

// Database connection
$host = 'localhost';
$db = 'workorganizer_db';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Calendar ID validation
$calendarId = $_GET['id'] ?? null;
if (!$calendarId || !is_numeric($calendarId)) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>Invalid calendar ID.</div></div>";
    include 'footer.php';
    exit;
}

// Fetch users for dropdown
$userStmt = $pdo->query("SELECT user_id, name, email FROM users");
$users = $userStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch roles for dropdown
$roleStmt = $pdo->query("SELECT id, role_name FROM roles");
$roles = $roleStmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'] ?? null;
    $roleId = $_POST['role_id'] ?? null;

    if (!$userId || !$roleId) {
        $error = "Please select both a user and a role.";
    } else {
        // Check if user is already a member
        $check = $pdo->prepare("SELECT id FROM users_calendars WHERE calendar_id = ? AND user_id = ?");
        $check->execute([$calendarId, $userId]);
        if ($check->fetch()) {
            $error = "User is already a member of this calendar.";
        } else {
            // Check if role is Lead Member
            $roleCheck = $pdo->prepare("SELECT role_name FROM roles WHERE id = ?");
            $roleCheck->execute([$roleId]);
            $selectedRole = $roleCheck->fetchColumn();

            if ($selectedRole === 'Lead Member') {
                $leadCheck = $pdo->prepare("
                    SELECT uc.id FROM users_calendars uc
                    JOIN roles r ON uc.role_id = r.id
                    WHERE uc.calendar_id = ? AND r.role_name = 'Lead Member'
                ");
                $leadCheck->execute([$calendarId]);
                if ($leadCheck->fetch()) {
                    $error = "A Lead Member already exists for this calendar.";
                }
            }

            // Add member
            if (!isset($error)) {
                $insert = $pdo->prepare("INSERT INTO users_calendars (calendar_id, user_id, role_id) VALUES (?, ?, ?)");
                $insert->execute([$calendarId, $userId, $roleId]);
                $success = "User successfully added to the calendar.";
            }
        }
    }
}
?>

<div class="d-flex flex-column min-vh-100">
    <main class="flex-grow-1">
        <div class="container mt-5">
            <h2 class="mb-3">Add Member to Calendar</h2>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <form method="POST" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="user_id" class="form-label">Select User</label>
                    <select class="form-select" id="user_id" name="user_id" required>
                        <option value="">Choose a user</option>
                        <?php foreach ($users as $user): ?>
                            <option value="<?= $user['user_id']; ?>">
                                <?= htmlspecialchars($user['name']) . ' (' . htmlspecialchars($user['email']) . ')'; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">Please select a user.</div>
                </div>

                <div class="mb-3">
                    <label for="role_id" class="form-label">Select Role</label>
                    <select class="form-select" id="role_id" name="role_id" required>
                        <option value="">Choose a role</option>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?= $role['id']; ?>"><?= htmlspecialchars($role['role_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">Please select a role.</div>
                </div>

                <button type="submit" class="btn btn-primary">Add Member</button>
            </form>

            <a href="view_calendar.php?id=<?= $calendarId; ?>" class="btn btn-secondary mt-3">Back to Calendar</a>
        </div>
    </main>
    <?php include 'footer.php'; ?>
</div>

<!-- JavaScript for form validation -->
<script>
(function () {
    'use strict';
    window.addEventListener('load', function () {
        var forms = document.getElementsByClassName('needs-validation');
        Array.prototype.forEach.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();
</script>

<?php
include 'header.php';

$host = 'localhost';
$db = 'workorganizer_db';
$user = 'root';
$pass = '';
$pdo = null;

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$calendarId = $_GET['id'] ?? null;
if (!$calendarId || !is_numeric($calendarId)) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>Invalid calendar ID.</div></div>";
    include 'footer.php';
    exit;
}

// Fetch the roles for the select dropdown
$rolesStmt = $pdo->prepare("SELECT * FROM roles");
$rolesStmt->execute();
$roles = $rolesStmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $roleId = $_POST['role_id'] ?? null;

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        // Check if user exists
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $error = "User with that email does not exist.";
        } else {
            $userIdToAdd = $user['user_id'];

            // Check if user is already a member
            $stmt = $pdo->prepare("SELECT * FROM users_calendars WHERE calendar_id = ? AND user_id = ?");
            $stmt->execute([$calendarId, $userIdToAdd]);

            if ($stmt->fetch()) {
                $error = "User is already a member of this calendar.";
            } else {
                // Check if role is Lead Member
                $roleCheck = $pdo->prepare("SELECT role_name FROM roles WHERE id = ?");
                $roleCheck->execute([$roleId]);
                $selectedRole = $roleCheck->fetchColumn();

                if ($selectedRole === 'Lead Member') {
                    // Check if a Lead already exists for this calendar
                    $leadCheck = $pdo->prepare("
                        SELECT uc.id FROM users_calendars uc
                        JOIN roles r ON uc.role_id = r.id
                        WHERE uc.calendar_id = ? AND r.role_name = 'Lead Member'
                    ");
                    $leadCheck->execute([$calendarId]);

                    if ($leadCheck->fetch()) {
                        $error = "A Lead Member already exists for this calendar. Only one Lead is allowed.";
                    }
                }

                if (!isset($error)) {
                    // Proceed to add user
                    $stmt = $pdo->prepare("INSERT INTO users_calendars (calendar_id, user_id, role_id) VALUES (?, ?, ?)");
                    $stmt->execute([$calendarId, $userIdToAdd, $roleId]);
                    $success = "User successfully added to the calendar.";
                }
            }
        }
    }
}
?>

<div class="d-flex flex-column min-vh-100">
    <main class="flex-grow-1">
        <div class="container mt-5">
            <h2 class="mb-3">Add Member to Calendar</h2>

            <!-- Display Success or Error Messages -->
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <!-- Add Member Form -->
            <form method="POST" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="email" class="form-label">User Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                    <div class="invalid-feedback">
                        Please provide a valid email address.
                    </div>
                </div>

                <div class="mb-3">
                    <label for="role_id" class="form-label">Role</label>
                    <select class="form-select" id="role_id" name="role_id" required>
                        <option value="">Select Role</option>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?php echo $role['id']; ?>"><?php echo htmlspecialchars($role['role_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">
                        Please select a role.
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Add Member</button>
            </form>
            <a href="view_calendar.php?id=<?php echo $calendarId; ?>" class="btn btn-secondary mt-3">Back to Calendar</a>
        </div>
    </main>
    <?php include 'footer.php'; ?>
</div>

<!-- JavaScript for Form Validation -->
<script>
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function () {
        'use strict'
        window.addEventListener('load', function () {
            var forms = document.getElementsByClassName('needs-validation')
            // Loop over them and prevent submission
            Array.prototype.filter.call(forms, function (form) {
                form.addEventListener('submit', function (event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        }, false)
    })();
</script>

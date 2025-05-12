<?php
class PermissionManager {
    private $pdo;
    private $userId;
    private $calendarId;
    private $roleId;
    private $permissions = [];

    public function __construct(PDO $pdo, int $userId, int $calendarId) {
        $this->pdo = $pdo;
        $this->userId = $userId;
        $this->calendarId = $calendarId;
        $this->loadRoleAndPermissions();
    }

    private function loadRoleAndPermissions(): void {
        // Get the user's role in this calendar
        $stmt = $this->pdo->prepare("
            SELECT r.id AS role_id
            FROM users_calendars uc
            JOIN roles r ON uc.role_id = r.id
            WHERE uc.user_id = ? AND uc.calendar_id = ?
            LIMIT 1
        ");
        $stmt->execute([$this->userId, $this->calendarId]);
        $roleData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$roleData) {
            throw new Exception("Access denied. User has no role for this calendar.");
        }

        $this->roleId = $roleData['role_id'];

        // Load permissions for the role
        $permStmt = $this->pdo->prepare("
            SELECT p.permission_name
            FROM role_permissions rp
            JOIN permissions p ON rp.permission_id = p.id
            WHERE rp.role_id = ?
        ");
        $permStmt->execute([$this->roleId]);
        $this->permissions = $permStmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function has(string $permissionName): bool {
        return in_array($permissionName, $this->permissions);
    }

    public function getRoleId(): int {
        return $this->roleId;
    }

    public function getPermissions(): array {
        return $this->permissions;
    }
}

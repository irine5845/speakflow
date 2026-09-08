<?php

require_once "../../includes/admin_auth.php";
require_once "../../config/database.php";

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: index.php");
    exit;
}

$user_id = (int) $_GET["id"];

$stmt = $pdo->prepare("
    SELECT id, full_name, email, role
    FROM users
    WHERE id = ?
    LIMIT 1
");

$stmt->execute([$user_id]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header("Location: index.php");
    exit;
}

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $full_name = trim($_POST["full_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $role = $_POST["role"] ?? "user";

    if ($full_name === "") {
        $errors[] = "Full name is required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Enter a valid email address.";
    }

    if (!in_array($role, ["user", "admin"], true)) {
        $errors[] = "Invalid role selected.";
    }

    if (empty($errors)) {

        $stmt = $pdo->prepare("
            SELECT id
            FROM users
            WHERE email = ?
            AND id != ?
            LIMIT 1
        ");

        $stmt->execute([$email, $user_id]);

        if ($stmt->fetch()) {

            $errors[] = "Another account already uses this email.";

        } else {

            $stmt = $pdo->prepare("
                UPDATE users
                SET full_name = ?, email = ?, role = ?
                WHERE id = ?
            ");

            $stmt->execute([
                $full_name,
                $email,
                $role,
                $user_id
            ]);

            header("Location: view.php?id=" . $user_id . "&updated=1");
            exit;
        }
    }
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Edit User - SpeakFlow</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link rel="stylesheet" href="../../assets/css/style.css">

</head>

<body>

<div class="container-fluid">

<div class="row">

<div class="col-md-3 col-lg-2 p-0">

<aside class="sidebar">

<div class="sidebar-brand">

<a href="../index.php">
🎙️ <span>SpeakFlow</span>
</a>

</div>

<div class="sidebar-title">
ADMIN PANEL
</div>

<div class="sidebar-menu">

<a href="../index.php" class="sidebar-link">
<span class="menu-icon">📊</span>
Dashboard
</a>

<a href="index.php" class="sidebar-link active">
<span class="menu-icon">👥</span>
Users
</a>

<a href="../documents/index.php" class="sidebar-link">
<span class="menu-icon">📄</span>
Documents
</a>

<a href="../conversions/index.php" class="sidebar-link">
<span class="menu-icon">🔊</span>
Conversions
</a>

<a href="../reports/index.php" class="sidebar-link">
<span class="menu-icon">📈</span>
Reports
</a>

<a href="../activity/index.php" class="sidebar-link">
<span class="menu-icon">📝</span>
Activity Logs
</a>

</div>

<div class="sidebar-divider"></div>

<div class="sidebar-title">
ACCOUNT
</div>

<div class="sidebar-menu">

<a href="../profile/index.php" class="sidebar-link">
<span class="menu-icon">👤</span>
Admin Profile
</a>

<a href="../settings/index.php" class="sidebar-link">
<span class="menu-icon">⚙️</span>
Settings
</a>

<a href="../../dashboard/index.php" class="sidebar-link">
<span class="menu-icon">🏠</span>
User Portal
</a>

<a href="../../auth/logout.php" class="sidebar-link logout-link">
<span class="menu-icon">🚪</span>
Logout
</a>

</div>

</aside>

</div>

<div class="col-md-9 col-lg-10">

<main class="p-4">

<div class="mb-4">

<h2 class="fw-bold">
Edit User
</h2>

<p class="text-muted">
Update account information.
</p>

</div>

<?php if (!empty($errors)): ?>

<div class="alert alert-danger">

<?php foreach ($errors as $error): ?>

<div>
<?= htmlspecialchars($error) ?>
</div>

<?php endforeach; ?>

</div>

<?php endif; ?>

<div class="card border-0 shadow-sm">

<div class="card-body p-4">

<form method="POST">

<div class="mb-3">

<label class="form-label fw-semibold">
Full Name
</label>

<input
type="text"
name="full_name"
class="form-control"
value="<?= htmlspecialchars($user["full_name"]) ?>"
required

>

</div>

<div class="mb-3">

<label class="form-label fw-semibold">
Email
</label>

<input
type="email"
name="email"
class="form-control"
value="<?= htmlspecialchars($user["email"]) ?>"
required

>

</div>

<div class="mb-4">

<label class="form-label fw-semibold">
Role
</label>

<select
name="role"
class="form-select"

>

<option
value="user"
<?= $user["role"] === "user" ? "selected" : "" ?>
>
User
</option>

<option
value="admin"
<?= $user["role"] === "admin" ? "selected" : "" ?>
>
Administrator
</option>

</select>

</div>

<button
type="submit"
class="btn btn-primary"

>

Save Changes </button>

<a
href="view.php?id=<?= $user_id ?>"
class="btn btn-outline-secondary"

>

Cancel </a>

</form>

</div>

</div>

</main>

</div>

</div>

</div>

</body>
</html>

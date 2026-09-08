<?php

require_once "../../includes/admin_auth.php";
require_once "../../config/database.php";

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: index.php");
    exit;
}

$user_id = (int) $_GET["id"];

$stmt = $pdo->prepare("
    SELECT id, full_name, email, role, created_at
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


/* User statistics */

$stmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM documents
    WHERE user_id = ?
");

$stmt->execute([$user_id]);

$total_documents = $stmt->fetchColumn();


$stmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM conversions
    WHERE user_id = ?
");

$stmt->execute([$user_id]);

$total_conversions = $stmt->fetchColumn();


$stmt = $pdo->prepare("
    SELECT COALESCE(SUM(character_count), 0)
    FROM conversions
    WHERE user_id = ?
");

$stmt->execute([$user_id]);

$total_characters = $stmt->fetchColumn();

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>View User - SpeakFlow</title>

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

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h2 class="fw-bold mb-1">
User Details
</h2>

<p class="text-muted mb-0">
View account information and usage statistics.
</p>

</div>

<a href="index.php" class="btn btn-outline-secondary">
← Back to Users
</a>

</div>

<div class="row g-4">

<div class="col-lg-4">

<div class="card border-0 shadow-sm">

<div class="card-body text-center py-5">

<div class="display-4 mb-3">
👤
</div>

<h4 class="fw-bold">

<?= htmlspecialchars($user["full_name"]) ?>

</h4>

<p class="text-muted">

<?= htmlspecialchars($user["email"]) ?>

</p>

<?php if ($user["role"] === "admin"): ?>

<span class="badge bg-primary">
Administrator
</span>

<?php else: ?>

<span class="badge bg-secondary">
User
</span>

<?php endif; ?>

<hr>

<p class="text-muted mb-0">

Registered:

<?= date(
"d M Y",
strtotime($user["created_at"])
) ?>

</p>

</div>

</div>

</div>

<div class="col-lg-8">

<div class="row g-4">

<div class="col-md-4">

<div class="card border-0 shadow-sm">

<div class="card-body">

<p class="text-muted mb-1">
Documents
</p>

<h3 class="fw-bold">
<?= $total_documents ?>
</h3>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card border-0 shadow-sm">

<div class="card-body">

<p class="text-muted mb-1">
Conversions
</p>

<h3 class="fw-bold">
<?= $total_conversions ?>
</h3>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card border-0 shadow-sm">

<div class="card-body">

<p class="text-muted mb-1">
Characters
</p>

<h3 class="fw-bold">
<?= number_format($total_characters) ?>
</h3>

</div>

</div>

</div>

</div>

<div class="card border-0 shadow-sm mt-4">

<div class="card-body">

<h5 class="fw-bold">
Account Information
</h5>

<table class="table">

<tr>
<th>User ID</th>
<td><?= $user["id"] ?></td>
</tr>

<tr>
<th>Full Name</th>
<td><?= htmlspecialchars($user["full_name"]) ?></td>
</tr>

<tr>
<th>Email</th>
<td><?= htmlspecialchars($user["email"]) ?></td>
</tr>

<tr>
<th>Role</th>
<td><?= htmlspecialchars($user["role"]) ?></td>
</tr>

<tr>
<th>Registration Date</th>
<td><?= htmlspecialchars($user["created_at"]) ?></td>
</tr>

</table>

<a
href="edit.php?id=<?= $user["id"] ?>"
class="btn btn-primary"

>

Edit User </a>

</div>

</div>

</div>

</div>

</main>

</div>

</div>

</div>

</body>
</html>

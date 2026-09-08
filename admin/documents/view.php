<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

$user_id = $_SESSION["user_id"];

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: index.php");
    exit;
}

$document_id = (int) $_GET["id"];


$stmt = $pdo->prepare("
    SELECT *
    FROM documents
    WHERE id = ?
    AND user_id = ?
    LIMIT 1
");

$stmt->execute([
    $document_id,
    $user_id
]);

$document = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$document) {
    header("Location: index.php");
    exit;
}


$content = $document["content"] ?? "";

$character_count = mb_strlen($content);

$word_count = str_word_count(
    strip_tags($content)
);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0"

>

<title>
<?= htmlspecialchars(
    $document["title"] ?: "Document"
) ?>
- SpeakFlow
</title>

<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
>

<link
    rel="stylesheet"
    href="../assets/css/style.css"
>

</head>

<body>

<div class="container-fluid">

<div class="row">

<!-- SIDEBAR -->

<div class="col-md-3 col-lg-2 p-0">

<aside class="sidebar">

<div class="sidebar-brand">

<a href="../dashboard/index.php">
🎙️ <span>SpeakFlow</span>
</a>

</div>

<div class="sidebar-title">
MAIN MENU
</div>

<div class="sidebar-menu">

<a href="../dashboard/index.php" class="sidebar-link">

<span class="menu-icon">📊</span>
Dashboard

</a>

<a href="../converter/index.php" class="sidebar-link">

<span class="menu-icon">🎙️</span>
Text to Speech

</a>

<a href="index.php" class="sidebar-link active">

<span class="menu-icon">📄</span>
Documents

</a>

<a href="../history/index.php" class="sidebar-link">

<span class="menu-icon">🕘</span>
History

</a>

</div>

<div class="sidebar-divider"></div>

<div class="sidebar-title">
ACCOUNT
</div>

<div class="sidebar-menu">

<a href="../profile/index.php" class="sidebar-link">

<span class="menu-icon">👤</span>
My Profile

</a>

<a href="../auth/logout.php" class="sidebar-link logout-link">

<span class="menu-icon">🚪</span>
Logout

</a>

</div>

</aside>

</div>

<!-- MAIN -->

<div class="col-md-9 col-lg-10">

<main class="p-4">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h2 class="fw-bold mb-1">
<?= htmlspecialchars(
    $document["title"] ?: "Untitled Document"
) ?>
</h2>

<p class="text-muted mb-0">

Last updated:

<?= date(
    "d M Y, h:i A",
    strtotime($document["updated_at"])
) ?>

</p>

</div>

<div class="d-flex gap-2">

<a
href="../converter/index.php?document_id=<?= $document_id ?>"
class="btn btn-primary"

>

🎙️ Convert to Speech </a>

<a
href="edit.php?id=<?= $document_id ?>"
class="btn btn-outline-secondary"

>

Edit </a>

</div>

</div>

<?php if (isset($_GET["updated"])): ?>

<div class="alert alert-success">

Document updated successfully.

</div>

<?php endif; ?>

<div class="row g-4">

<div class="col-lg-8">

<div class="card border-0 shadow-sm">

<div class="card-body">

<div
    class="document-content p-3"
    style="
        min-height: 500px;
        white-space: pre-wrap;
        line-height: 1.8;
    "
>

<?= htmlspecialchars($content) ?>

</div>

</div>

</div>

</div>

<div class="col-lg-4">

<div class="card border-0 shadow-sm">

<div class="card-body">

<h5 class="fw-bold mb-4">
Document Statistics
</h5>

<div class="d-flex justify-content-between mb-3">

<span class="text-muted">
Words
</span>

<strong>
<?= number_format($word_count) ?>
</strong>

</div>

<div class="d-flex justify-content-between mb-3">

<span class="text-muted">
Characters
</span>

<strong>
<?= number_format($character_count) ?>
</strong>

</div>

<div class="d-flex justify-content-between mb-3">

<span class="text-muted">
Created
</span>

<span>

<?= date(
    "d M Y",
    strtotime($document["created_at"])
) ?>

</span>

</div>

<hr>

<a
href="edit.php?id=<?= $document_id ?>"
class="btn btn-outline-primary w-100 mb-2"

>

✏️ Edit Document </a>

<a
href="delete.php?id=<?= $document_id ?>"
class="btn btn-outline-danger w-100"
onclick="return confirm('Are you sure you want to delete this document?');"

>

🗑️ Delete Document </a>

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

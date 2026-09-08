<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

$user_id = $_SESSION["user_id"];

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: index.php");
    exit;
}

$document_id = (int) $_GET["id"];


/*
|--------------------------------------------------------------------------
| Get document
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
    SELECT id, title, content
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


$title = $document["title"];
$content = $document["content"];

$errors = [];


/*
|--------------------------------------------------------------------------
| Update document
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST["title"] ?? "");
    $content = trim($_POST["content"] ?? "");

    if ($title === "") {
        $errors[] = "Please enter a document title.";
    }

    if ($content === "") {
        $errors[] = "Please enter some content.";
    }

    if (empty($errors)) {

        $stmt = $pdo->prepare("
            UPDATE documents
            SET title = ?, content = ?, updated_at = NOW()
            WHERE id = ?
            AND user_id = ?
        ");

        $stmt->execute([
            $title,
            $content,
            $document_id,
            $user_id
        ]);

        header(
            "Location: view.php?id=" . $document_id . "&updated=1"
        );

        exit;
    }
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0"

>

<title>Edit Document - SpeakFlow</title>

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
Edit Document
</h2>

<p class="text-muted mb-0">
Update your document content.
</p>

</div>

<a
href="view.php?id=<?= $document_id ?>"
class="btn btn-outline-secondary"

>

← Back </a>

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

<div class="mb-4">

<label class="form-label fw-semibold">
Document Title
</label>

<input
type="text"
name="title"
class="form-control form-control-lg"
value="<?= htmlspecialchars($title) ?>"
maxlength="255"
required

>

</div>

<div class="mb-4">

<label class="form-label fw-semibold">
Content
</label>

<textarea
    name="content"
    id="documentContent"
    class="form-control"
    rows="18"
    required
><?= htmlspecialchars($content) ?></textarea>

<div class="text-end mt-2">

<small class="text-muted">

<span id="characterCount">
0
</span>

characters

</small>

</div>

</div>

<button
type="submit"
class="btn btn-primary px-4"

>

💾 Save Changes </button>

<a
href="view.php?id=<?= $document_id ?>"
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

<script>

const content = document.getElementById("documentContent");
const characterCount = document.getElementById("characterCount");

function updateCount() {

    characterCount.textContent =
        content.value.length.toLocaleString();

}

content.addEventListener("input", updateCount);

updateCount();

</script>

</body>

</html>

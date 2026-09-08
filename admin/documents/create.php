<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

$user_id = $_SESSION["user_id"];

$errors = [];

$title = "";
$content = "";

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
            INSERT INTO documents
                (user_id, title, content, created_at, updated_at)
            VALUES
                (?, ?, ?, NOW(), NOW())
        ");

        $stmt->execute([
            $user_id,
            $title,
            $content
        ]);

        header("Location: index.php?saved=1");
        exit;
    }
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>New Document - SpeakFlow</title>

<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
>

<link rel="stylesheet" href="../assets/css/style.css">

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

<!-- MAIN CONTENT -->

<div class="col-md-9 col-lg-10">

<main class="p-4">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h2 class="fw-bold mb-1">
Create Document
</h2>

<p class="text-muted mb-0">
Create a document that you can use for text-to-speech conversion.
</p>

</div>

<a
href="index.php"
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
placeholder="Enter document title..."
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
    rows="16"
    placeholder="Type or paste your text here..."
    required
><?= htmlspecialchars($content) ?></textarea>

<div class="d-flex justify-content-between mt-2">

<small class="text-muted">
You can use this content later in the Text-to-Speech converter.
</small>

<small class="text-muted">

<span id="characterCount">0</span>
characters

</small>

</div>

</div>

<div class="d-flex gap-2">

<button
type="submit"
class="btn btn-primary px-4"

>

💾 Save Document </button>

<a
href="index.php"
class="btn btn-outline-secondary"

>

Cancel </a>

</div>

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

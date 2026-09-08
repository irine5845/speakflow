<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../converter/index.php");
    exit;
}

$user_id = $_SESSION["user_id"];

$title = trim($_POST["title"] ?? "");
$content = trim($_POST["content"] ?? "");

if ($title === "") {
    $title = "Untitled Document";
}

if ($content === "") {
    header("Location: ../converter/index.php?error=empty");
    exit;
}

$word_count = str_word_count($content);
$character_count = mb_strlen($content);

$stmt = $pdo->prepare("
    INSERT INTO documents
    (user_id, title, content, word_count, character_count)
    VALUES (?, ?, ?, ?, ?)
");

$stmt->execute([
    $user_id,
    $title,
    $content,
    $word_count,
    $character_count
]);

$document_id = $pdo->lastInsertId();

header(
    "Location: ../documents/index.php?saved=1"
);

exit;
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
| Make sure the document belongs to the logged-in user
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
    SELECT id
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


/*
|--------------------------------------------------------------------------
| Delete
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
    DELETE FROM documents
    WHERE id = ?
    AND user_id = ?
");

$stmt->execute([
    $document_id,
    $user_id
]);


header("Location: index.php?deleted=1");

exit;

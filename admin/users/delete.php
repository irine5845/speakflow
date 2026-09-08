<?php

require_once "../../includes/admin_auth.php";
require_once "../../config/database.php";

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: index.php");
    exit;
}

$user_id = (int) $_GET["id"];

/*
 * Prevent administrator from deleting their own account.
 */

if ($user_id === (int) $_SESSION["user_id"]) {
    header("Location: index.php");
    exit;
}


/*
 * Delete related records first.
 */

$stmt = $pdo->prepare("
    DELETE FROM conversions
    WHERE user_id = ?
");

$stmt->execute([$user_id]);


$stmt = $pdo->prepare("
    DELETE FROM documents
    WHERE user_id = ?
");

$stmt->execute([$user_id]);


/*
 * Delete user.
 */

$stmt = $pdo->prepare("
    DELETE FROM users
    WHERE id = ?
");

$stmt->execute([$user_id]);


header("Location: index.php?deleted=1");
exit;

<?php

require_once "../../includes/admin_auth.php";
require_once "../../config/database.php";

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: index.php");
    exit;
}

$user_id = (int) $_GET["id"];


/*
 * Prevent admin from disabling their own account.
 */

if ($user_id === (int) $_SESSION["user_id"]) {
    header("Location: index.php");
    exit;
}


/*
 * Get current status.
 */

$stmt = $pdo->prepare("
    SELECT status
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


$new_status =
    $user["status"] === "active"
        ? "inactive"
        : "active";


$stmt = $pdo->prepare("
    UPDATE users
    SET status = ?
    WHERE id = ?
");

$stmt->execute([
    $new_status,
    $user_id
]);


header("Location: index.php?updated=1");
exit;

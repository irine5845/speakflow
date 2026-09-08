<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

$user_id = $_SESSION["user_id"];

$id = intval($_GET["id"] ?? 0);

$stmt = $pdo->prepare("
    SELECT *
    FROM documents
    WHERE id = ?
    AND user_id = ?
");

$stmt->execute([$id, $user_id]);

$document = $stmt->fetch();

if (!$document) {
    header("Location: index.php");
    exit;
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST["title"] ?? "");
    $content = trim($_POST["content"] ?? "");

    if ($title === "") {
        $title = "Untitled Document";
    }

    $word_count = str_word_count($content);
    $character_count = mb_strlen($content);

    $stmt = $pdo->prepare("
        UPDATE documents
        SET title = ?,
            content = ?,
            word_count = ?,
            character_count = ?
        WHERE id = ?
        AND user_id = ?
    ");

    $stmt->execute([
        $title,
        $content,
        $word_count,
        $character_count,
        $id,
        $user_id
    ]);

    header("Location: index.php?saved=1");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Edit Document - SpeakFlow</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link
        rel="stylesheet"
        href="../assets/css/style.css">

</head>

<body class="bg-light">

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-9">

            <div class="card border-0 shadow-sm">

                <div class="card-body p-4">

                    <div
                        class="d-flex justify-content-between
                               align-items-center mb-4">

                        <h3 class="fw-bold">
                            Edit Document
                        </h3>

                        <a
                            href="index.php"
                            class="btn btn-outline-secondary">

                            ← Back

                        </a>

                    </div>


                    <form method="POST">

                        <div class="mb-3">

                            <label class="form-label fw-semibold">

                                Document Title

                            </label>

                            <input
                                type="text"
                                name="title"
                                class="form-control"
                                value="<?= htmlspecialchars(
                                    $document["title"]
                                ) ?>"
                                required>

                        </div>


                        <div class="mb-4">

                            <label class="form-label fw-semibold">

                                Content

                            </label>

                            <textarea
                                name="content"
                                class="form-control"
                                rows="15"
                                required><?= htmlspecialchars(
                                    $document["content"]
                                ) ?></textarea>

                        </div>


                        <button
                            type="submit"
                            class="btn btn-primary">

                            Save Changes

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

</body>

</html>
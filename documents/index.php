<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

$user_id = $_SESSION["user_id"];

$stmt = $pdo->prepare("
    SELECT *
    FROM documents
    WHERE user_id = ?
    ORDER BY updated_at DESC
");

$stmt->execute([$user_id]);

$documents = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>My Documents - SpeakFlow</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link
        rel="stylesheet"
        href="../assets/css/style.css">

</head>

<body class="bg-light">


<nav class="navbar navbar-expand-lg bg-white shadow-sm">

    <div class="container-fluid px-4">

        <a
            class="navbar-brand fw-bold"
            href="../dashboard/index.php">

            🎙️ SpeakFlow

        </a>

        <div class="d-flex align-items-center gap-3">

            <span class="text-muted">

                <?= htmlspecialchars($_SESSION["full_name"]) ?>

            </span>

            <a
                href="../auth/logout.php"
                class="btn btn-outline-danger btn-sm">

                Logout

            </a>

        </div>

    </div>

</nav>


<div class="container-fluid">

    <div class="row">


        <!-- SIDEBAR -->

        <aside
            class="col-md-3 col-lg-2 bg-white min-vh-100 p-3">

            <div class="list-group list-group-flush">

                <a
                    href="../dashboard/index.php"
                    class="list-group-item list-group-item-action">

                    🏠 Dashboard

                </a>

                <a
                    href="../converter/index.php"
                    class="list-group-item list-group-item-action">

                    🔊 Text to Speech

                </a>

                <a
                    href="index.php"
                    class="list-group-item list-group-item-action active">

                    📄 My Documents

                </a>

                <a
                    href="../history/index.php"
                    class="list-group-item list-group-item-action">

                    🕘 Conversion History

                </a>

                <a
                    href="../profile/index.php"
                    class="list-group-item list-group-item-action">

                    👤 Profile

                </a>

            </div>

        </aside>


        <!-- CONTENT -->

        <main class="col-md-9 col-lg-10 p-4">

            <div
                class="d-flex justify-content-between
                       align-items-center mb-4">

                <div>

                    <h2 class="fw-bold">
                        My Documents
                    </h2>

                    <p class="text-muted">
                        Manage your saved text documents.
                    </p>

                </div>

                <a
                    href="../converter/index.php"
                    class="btn btn-primary">

                    + New Document

                </a>

            </div>


            <?php if (isset($_GET["saved"])): ?>

                <div class="alert alert-success">

                    Document saved successfully.

                </div>

            <?php endif; ?>


            <?php if (empty($documents)): ?>

                <div class="card border-0 shadow-sm">

                    <div class="card-body text-center p-5">

                        <div class="display-4">
                            📄
                        </div>

                        <h4 class="fw-bold mt-3">
                            No documents yet
                        </h4>

                        <p class="text-muted">

                            Start by creating your first
                            text-to-speech document.

                        </p>

                        <a
                            href="../converter/index.php"
                            class="btn btn-primary">

                            Create Document

                        </a>

                    </div>

                </div>

            <?php else: ?>

                <div class="row g-4">

                    <?php foreach ($documents as $document): ?>

                        <div class="col-md-6 col-xl-4">

                            <div
                                class="card border-0 shadow-sm h-100">

                                <div class="card-body">

                                    <h5 class="fw-bold">

                                        <?= htmlspecialchars(
                                            $document["title"]
                                        ) ?>

                                    </h5>

                                    <p class="text-muted small">

                                        <?= htmlspecialchars(
                                            mb_substr(
                                                $document["content"],
                                                0,
                                                150
                                            )
                                        ) ?>

                                        <?= mb_strlen(
                                            $document["content"]
                                        ) > 150 ? "..." : "" ?>

                                    </p>

                                    <hr>

                                    <div
                                        class="d-flex
                                               justify-content-between">

                                        <small class="text-muted">

                                            <?= number_format(
                                                $document["word_count"]
                                            ) ?>
                                            words

                                        </small>

                                        <small class="text-muted">

                                            <?= number_format(
                                                $document["character_count"]
                                            ) ?>
                                            characters

                                        </small>

                                    </div>

                                </div>

                                <div class="card-footer bg-white border-0">

                                    <a
                                        href="edit.php?id=<?= $document["id"] ?>"
                                        class="btn btn-sm btn-outline-primary">

                                        Edit

                                    </a>

                                    <a
                                        href="delete.php?id=<?= $document["id"] ?>"
                                        class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Delete this document?');">

                                        Delete

                                    </a>

                                </div>

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>

        </main>

    </div>

</div>

</body>

</html>
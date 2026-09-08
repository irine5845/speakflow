<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

$user_id = $_SESSION["user_id"];

$stmt = $pdo->prepare("
    SELECT *
    FROM conversions
    WHERE user_id = ?
    ORDER BY created_at DESC
");

$stmt->execute([$user_id]);

$conversions = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Conversion History - SpeakFlow</title>

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

        <div>

            <span class="text-muted me-3">

                <?= htmlspecialchars(
                    $_SESSION["full_name"]
                ) ?>

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
                    href="../documents/index.php"
                    class="list-group-item list-group-item-action">

                    📄 My Documents

                </a>

                <a
                    href="index.php"
                    class="list-group-item list-group-item-action active">

                    🕘 Conversion History

                </a>

            </div>

        </aside>


        <main class="col-md-9 col-lg-10 p-4">

            <h2 class="fw-bold">
                Conversion History
            </h2>

            <p class="text-muted mb-4">

                View your previous text-to-speech conversions.

            </p>


            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <?php if (empty($conversions)): ?>

                        <div class="text-center p-5">

                            <div class="display-5">
                                🕘
                            </div>

                            <h5 class="fw-bold mt-3">
                                No conversions yet
                            </h5>

                            <p class="text-muted">
                                Your conversion history will appear here.
                            </p>

                            <a
                                href="../converter/index.php"
                                class="btn btn-primary">

                                Start Converting

                            </a>

                        </div>

                    <?php else: ?>

                        <div class="table-responsive">

                            <table
                                class="table table-hover align-middle">

                                <thead>

                                    <tr>

                                        <th>Date</th>

                                        <th>Language</th>

                                        <th>Voice</th>

                                        <th>Characters</th>

                                        <th>Speed</th>

                                        <th>Status</th>

                                    </tr>

                                </thead>

                                <tbody>

                                <?php foreach (
                                    $conversions as $conversion
                                ): ?>

                                    <tr>

                                        <td>

                                            <?= date(
                                                "d M Y H:i",
                                                strtotime(
                                                    $conversion["created_at"]
                                                )
                                            ) ?>

                                        </td>

                                        <td>

                                            <?= htmlspecialchars(
                                                $conversion["language"]
                                            ) ?>

                                        </td>

                                        <td>

                                            <?= htmlspecialchars(
                                                $conversion["voice"]
                                                    ?? "Default"
                                            ) ?>

                                        </td>

                                        <td>

                                            <?= number_format(
                                                $conversion[
                                                    "character_count"
                                                ]
                                            ) ?>

                                        </td>

                                        <td>

                                            <?= htmlspecialchars(
                                                $conversion["speed"]
                                            ) ?>x

                                        </td>

                                        <td>

                                            <span
                                                class="badge bg-success">

                                                <?= htmlspecialchars(
                                                    $conversion["status"]
                                                ) ?>

                                            </span>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                                </tbody>

                            </table>

                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </main>

    </div>

</div>

</body>

</html>
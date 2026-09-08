<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

$user_id = $_SESSION["user_id"];

$stmt = $pdo->prepare(
    "SELECT COUNT(*) 
     FROM conversions 
     WHERE user_id = ?"
);

$stmt->execute([$user_id]);

$total_conversions = $stmt->fetchColumn();


$stmt = $pdo->prepare(
    "SELECT COUNT(*) 
     FROM documents 
     WHERE user_id = ?"
);

$stmt->execute([$user_id]);

$total_documents = $stmt->fetchColumn();


$stmt = $pdo->prepare(
    "SELECT COALESCE(SUM(character_count), 0)
     FROM conversions
     WHERE user_id = ?"
);

$stmt->execute([$user_id]);

$total_characters = $stmt->fetchColumn();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Dashboard - SpeakFlow</title>

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

        <a class="navbar-brand fw-bold"
           href="index.php">

            🎙️ SpeakFlow

        </a>

        <div class="d-flex align-items-center gap-3">

            <span class="text-muted">

                Hello,
                <?= htmlspecialchars($_SESSION["full_name"]) ?>

            </span>

            <a href="../auth/logout.php"
               class="btn btn-outline-danger btn-sm">

                Logout

            </a>

        </div>

    </div>

</nav>


<div class="container-fluid">

    <div class="row">

        <!-- SIDEBAR -->

        <aside class="col-md-3 col-lg-2 bg-white min-vh-100 p-3">

            <div class="list-group list-group-flush">

                <a href="index.php"
                   class="list-group-item list-group-item-action">

                    🏠 Dashboard

                </a>

                <a href="../converter/index.php"
                   class="list-group-item list-group-item-action">

                    🔊 Text to Speech

                </a>

                <a href="../documents/index.php"
                   class="list-group-item list-group-item-action">

                    📄 My Documents

                </a>

                <a href="../history/index.php"
                   class="list-group-item list-group-item-action">

                    🕘 Conversion History

                </a>

                <a href="../profile/index.php"
                   class="list-group-item list-group-item-action">

                    👤 Profile

                </a>

            </div>

        </aside>


        <!-- MAIN CONTENT -->

        <main class="col-md-9 col-lg-10 p-4">

            <div class="mb-4">

                <h2 class="fw-bold">
                    Dashboard
                </h2>

                <p class="text-muted">
                    Manage your text-to-speech activities.
                </p>

            </div>


            <div class="row g-4">

                <div class="col-md-4">

                    <div class="card border-0 shadow-sm">

                        <div class="card-body">

                            <h6 class="text-muted">
                                Total Conversions
                            </h6>

                            <h2 class="fw-bold">
                                <?= $total_conversions ?>
                            </h2>

                        </div>

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="card border-0 shadow-sm">

                        <div class="card-body">

                            <h6 class="text-muted">
                                Saved Documents
                            </h6>

                            <h2 class="fw-bold">
                                <?= $total_documents ?>
                            </h2>

                        </div>

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="card border-0 shadow-sm">

                        <div class="card-body">

                            <h6 class="text-muted">
                                Characters Converted
                            </h6>

                            <h2 class="fw-bold">
                                <?= number_format($total_characters) ?>
                            </h2>

                        </div>

                    </div>

                </div>

            </div>


            <div class="card border-0 shadow-sm mt-4">

                <div class="card-body p-4">

                    <h4 class="fw-bold">
                        Start Converting
                    </h4>

                    <p class="text-muted">

                        Enter text and transform it into
                        speech using SpeakFlow.

                    </p>

                    <a href="../converter/index.php"
                       class="btn btn-primary">

                        🔊 Open Text Converter

                    </a>

                </div>

            </div>

        </main>

    </div>

</div>

</body>

</html>
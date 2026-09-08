<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

$user_id = $_SESSION["user_id"];

/*
|--------------------------------------------------------------------------
| Total Conversions
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare(
    "SELECT COUNT(*)
     FROM conversions
     WHERE user_id = ?"
);

$stmt->execute([$user_id]);

$total_conversions = $stmt->fetchColumn();


/*
|--------------------------------------------------------------------------
| Total Documents
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare(
    "SELECT COUNT(*)
     FROM documents
     WHERE user_id = ?"
);

$stmt->execute([$user_id]);

$total_documents = $stmt->fetchColumn();


/*
|--------------------------------------------------------------------------
| Total Characters Converted
|--------------------------------------------------------------------------
*/

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

```
<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Dashboard - SpeakFlow</title>

<!-- Bootstrap CSS -->
<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet">

<!-- Custom CSS -->
<link
    rel="stylesheet"
    href="../assets/css/style.css">
```

</head>

<body class="bg-light">

```
<!-- =========================
     NAVBAR
========================== -->

<nav class="navbar navbar-expand-lg bg-white shadow-sm">

    <div class="container-fluid px-4">

        <a class="navbar-brand fw-bold"
           href="index.php">

            🎙️ SpeakFlow

        </a>


        <div class="d-flex align-items-center gap-3">

            <span class="text-muted">

                Hello,
                <?= htmlspecialchars($_SESSION["full_name"] ?? "User") ?>

            </span>


            <a href="../auth/logout.php"
               class="btn btn-outline-danger btn-sm">

                Logout

            </a>

        </div>

    </div>

</nav>


<!-- =========================
     PAGE LAYOUT
========================== -->

<div class="container-fluid">

    <div class="row">


        <!-- =========================
             SIDEBAR
        ========================== -->

        <aside class="col-md-3 col-lg-2 sidebar">


            <!-- Sidebar Brand -->

            <div class="sidebar-brand">

                <a href="../dashboard/index.php">

                    🎙️ <span>SpeakFlow</span>

                </a>

            </div>


            <!-- Main Menu -->

            <div class="sidebar-title">

                MAIN MENU

            </div>


            <div class="sidebar-menu">


                <!-- Dashboard -->

                <a href="../dashboard/index.php"
                   class="sidebar-link active">

                    <span class="menu-icon">
                        🏠
                    </span>

                    <span>
                        Dashboard
                    </span>

                </a>


                <!-- Text to Speech -->

                <a href="../converter/index.php"
                   class="sidebar-link">

                    <span class="menu-icon">
                        🔊
                    </span>

                    <span>
                        Text to Speech
                    </span>

                </a>


                <!-- Documents -->

                <a href="../documents/index.php"
                   class="sidebar-link">

                    <span class="menu-icon">
                        📄
                    </span>

                    <span>
                        My Documents
                    </span>

                </a>


                <!-- History -->

                <a href="../history/index.php"
                   class="sidebar-link">

                    <span class="menu-icon">
                        🕘
                    </span>

                    <span>
                        History
                    </span>

                </a>

            </div>


            <!-- Divider -->

            <div class="sidebar-divider"></div>


            <!-- Account -->

            <div class="sidebar-title">

                ACCOUNT

            </div>


            <div class="sidebar-menu">


                <!-- Profile -->

                <a href="../profile/index.php"
                   class="sidebar-link">

                    <span class="menu-icon">
                        👤
                    </span>

                    <span>
                        My Profile
                    </span>

                </a>


                <!-- Logout -->

                <a href="../auth/logout.php"
                   class="sidebar-link logout-link">

                    <span class="menu-icon">
                        🚪
                    </span>

                    <span>
                        Logout
                    </span>

                </a>

            </div>

        </aside>


        <!-- =========================
             MAIN CONTENT
        ========================== -->

        <main class="col-md-9 col-lg-10 p-4">


            <!-- Page Header -->

            <div class="mb-4">

                <h2 class="fw-bold">
                    Dashboard
                </h2>

                <p class="text-muted mb-0">

                    Manage your text-to-speech activities.

                </p>

            </div>


            <!-- =========================
                 STATISTICS CARDS
            ========================== -->

            <div class="row g-4">


                <!-- Total Conversions -->

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


                <!-- Saved Documents -->

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


                <!-- Characters Converted -->

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


            <!-- =========================
                 START CONVERTING
            ========================== -->

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


<!-- Bootstrap JavaScript -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>
```

</body>

</html>

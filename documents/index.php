```php
<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

$user_id = $_SESSION["user_id"];

/*
|--------------------------------------------------------------------------
| Fetch User Documents
|--------------------------------------------------------------------------
*/

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

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- SpeakFlow Custom CSS -->
    <link
        rel="stylesheet"
        href="../assets/css/style.css">

</head>

<body class="bg-light">


<!-- =========================================================
     NAVBAR
========================================================= -->

<nav class="navbar navbar-expand-lg bg-white shadow-sm">

    <div class="container-fluid px-4">

        <a
            class="navbar-brand fw-bold"
            href="../dashboard/index.php">

            🎙️ SpeakFlow

        </a>

        <div class="d-flex align-items-center gap-3">

            <span class="text-muted">

                <?= htmlspecialchars(
                    $_SESSION["full_name"] ?? "User"
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


<!-- =========================================================
     PAGE LAYOUT
========================================================= -->

<div class="container-fluid">

    <div class="row">


        <!-- =====================================================
             SIDEBAR
        ====================================================== -->

        <aside class="col-md-3 col-lg-2 sidebar">

            <!-- Brand -->

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

                <a
                    href="../dashboard/index.php"
                    class="sidebar-link">

                    <span class="menu-icon">🏠</span>

                    <span>Dashboard</span>

                </a>


                <a
                    href="../converter/index.php"
                    class="sidebar-link">

                    <span class="menu-icon">🔊</span>

                    <span>Text to Speech</span>

                </a>


                <a
                    href="index.php"
                    class="sidebar-link active">

                    <span class="menu-icon">📄</span>

                    <span>My Documents</span>

                </a>


                <a
                    href="../history/index.php"
                    class="sidebar-link">

                    <span class="menu-icon">🕘</span>

                    <span>History</span>

                </a>

            </div>


            <!-- Divider -->

            <div class="sidebar-divider"></div>


            <!-- Account -->

            <div class="sidebar-title">

                ACCOUNT

            </div>

            <div class="sidebar-menu">

                <a
                    href="../profile/index.php"
                    class="sidebar-link">

                    <span class="menu-icon">👤</span>

                    <span>My Profile</span>

                </a>


                <a
                    href="../auth/logout.php"
                    class="sidebar-link logout-link">

                    <span class="menu-icon">🚪</span>

                    <span>Logout</span>

                </a>

            </div>

        </aside>


        <!-- =====================================================
             MAIN CONTENT
        ====================================================== -->

        <main class="col-md-9 col-lg-10 p-4">


            <!-- Page Header -->

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


            <!-- =================================================
                 SUCCESS MESSAGE
            ================================================== -->

            <?php if (isset($_GET["saved"])): ?>

                <div
                    class="alert alert-success
                           alert-dismissible fade show">

                    Document saved successfully.

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                    </button>

                </div>

            <?php endif; ?>


            <!-- =================================================
                 NO DOCUMENTS
            ================================================== -->

            <?php if (empty($documents)): ?>

                <div class="card border-0 shadow-sm">

                    <div
                        class="card-body
                               text-center p-5">

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


            <!-- =================================================
                 DOCUMENT LIST
            ================================================== -->

            <?php else: ?>

                <div class="row g-4">

                    <?php foreach ($documents as $document): ?>

                        <div class="col-md-6 col-xl-4">

                            <div
                                class="card border-0
                                       shadow-sm h-100">


                                <!-- Document Content -->

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

                                        <?php if (
                                            mb_strlen(
                                                $document["content"]
                                            ) > 150
                                        ): ?>

                                            ...

                                        <?php endif; ?>

                                    </p>


                                    <hr>


                                    <!-- Document Statistics -->

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


                                <!-- Document Actions -->

                                <div
                                    class="card-footer
                                           bg-white border-0">

                                    <a
                                        href="edit.php?id=<?= (int) $document["id"] ?>"
                                        class="btn btn-sm
                                               btn-outline-primary">

                                        Edit

                                    </a>


                                    <a
                                        href="delete.php?id=<?= (int) $document["id"] ?>"
                                        class="btn btn-sm
                                               btn-outline-danger"
                                        onclick="return confirm(
                                            'Delete this document?'
                                        );">

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


<!-- Bootstrap JavaScript -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

</body>

</html>
```

This version now follows the same **Dashboard → Text to Speech → Documents → History → Profile** structure, so the interface will feel consistent throughout SpeakFlow.

Next, the logical page to clean is **`documents/edit.php`**, so editing a saved document uses the same design too.

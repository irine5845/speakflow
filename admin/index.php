```php
<?php

require_once "../includes/admin_auth.php";
require_once "../config/database.php";

$user_id = $_SESSION["user_id"];


/*
|--------------------------------------------------------------------------
| Dashboard Statistics
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| Total Users
|--------------------------------------------------------------------------
*/

$stmt = $pdo->query("
    SELECT COUNT(*)
    FROM users
");

$total_users = $stmt->fetchColumn();


/*
|--------------------------------------------------------------------------
| Total Documents
|--------------------------------------------------------------------------
*/

$stmt = $pdo->query("
    SELECT COUNT(*)
    FROM documents
");

$total_documents = $stmt->fetchColumn();


/*
|--------------------------------------------------------------------------
| Total Conversions
|--------------------------------------------------------------------------
*/

$stmt = $pdo->query("
    SELECT COUNT(*)
    FROM conversions
");

$total_conversions = $stmt->fetchColumn();


/*
|--------------------------------------------------------------------------
| Total Characters Converted
|--------------------------------------------------------------------------
*/

$stmt = $pdo->query("
    SELECT COALESCE(SUM(character_count), 0)
    FROM conversions
");

$total_characters = $stmt->fetchColumn();


/*
|--------------------------------------------------------------------------
| Recent Users
|--------------------------------------------------------------------------
*/

$stmt = $pdo->query("
    SELECT id, full_name, email, role
    FROM users
    ORDER BY id DESC
    LIMIT 5
");

$recent_users = $stmt->fetchAll();


/*
|--------------------------------------------------------------------------
| Recent Conversions
|--------------------------------------------------------------------------
*/

$stmt = $pdo->query("
    SELECT
        c.*,
        u.full_name
    FROM conversions c
    INNER JOIN users u
        ON u.id = c.user_id
    ORDER BY c.created_at DESC
    LIMIT 5
");

$recent_conversions = $stmt->fetchAll();


/*
|--------------------------------------------------------------------------
| Today's Conversions
|--------------------------------------------------------------------------
*/

$stmt = $pdo->query("
    SELECT COUNT(*)
    FROM conversions
    WHERE DATE(created_at) = CURDATE()
");

$today_conversions = $stmt->fetchColumn();


/*
|--------------------------------------------------------------------------
| Today's New Users
|--------------------------------------------------------------------------
*/

$stmt = $pdo->query("
    SELECT COUNT(*)
    FROM users
    WHERE DATE(created_at) = CURDATE()
");

$today_users = $stmt->fetchColumn();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard - SpeakFlow</title>


    <!-- Bootstrap -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">


    <!-- SpeakFlow CSS -->

    <link
        rel="stylesheet"
        href="../assets/css/style.css">


    <style>

        /*
        |--------------------------------------------------------------------------
        | Admin Dashboard
        |--------------------------------------------------------------------------
        */

        .admin-content {
            padding: 30px;
        }


        .admin-stat-card {
            border: 0;
            border-radius: 15px;
            transition: all 0.3s ease;
        }


        .admin-stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }


        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 23px;

            background: #eef4ff;
        }


        .stat-number {
            font-size: 28px;
            font-weight: 700;
        }


        .admin-section-card {
            border: 0;
            border-radius: 15px;
        }


        .admin-table th {
            font-size: 13px;
            color: #6b7280;
            font-weight: 600;
        }


        .admin-table td {
            vertical-align: middle;
        }


        .welcome-card {
            border: 0;
            border-radius: 15px;
            background: linear-gradient(
                135deg,
                #0d6efd,
                #4f8cff
            );
            color: white;
        }


        .quick-action {
            text-decoration: none;
            color: inherit;
        }


        .quick-action-card {
            border: 0;
            border-radius: 14px;
            transition: all 0.3s ease;
        }


        .quick-action-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

    </style>

</head>


<body class="bg-light">


<!-- =========================================================
     NAVBAR
========================================================= -->

<nav class="navbar navbar-expand-lg bg-white shadow-sm">

    <div class="container-fluid px-4">


        <!-- Brand -->

        <a
            class="navbar-brand fw-bold"
            href="index.php">

            🎙️ SpeakFlow
            <span class="text-primary">
                Admin
            </span>

        </a>


        <!-- Admin User -->

        <div class="d-flex align-items-center gap-3">

            <span class="text-muted">

                <?= htmlspecialchars(
                    $_SESSION["full_name"] ?? "Administrator"
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
             ADMIN SIDEBAR
        ====================================================== -->

        <aside class="col-md-3 col-lg-2 sidebar">


            <!-- Brand -->

            <div class="sidebar-brand">

                <a href="index.php">

                    🎙️ <span>SpeakFlow</span>

                </a>

            </div>


            <!-- Admin Menu -->

            <div class="sidebar-title">

                ADMINISTRATION

            </div>


            <div class="sidebar-menu">


                <!-- Dashboard -->

                <a
                    href="index.php"
                    class="sidebar-link active">

                    <span class="menu-icon">🏠</span>

                    <span>Dashboard</span>

                </a>


                <!-- Users -->

                <a
                    href="users/index.php"
                    class="sidebar-link">

                    <span class="menu-icon">👥</span>

                    <span>Users</span>

                </a>


                <!-- Documents -->

                <a
                    href="documents/index.php"
                    class="sidebar-link">

                    <span class="menu-icon">📄</span>

                    <span>Documents</span>

                </a>


                <!-- Conversions -->

                <a
                    href="conversions/index.php"
                    class="sidebar-link">

                    <span class="menu-icon">🔊</span>

                    <span>Conversions</span>

                </a>


                <!-- Reports -->

                <a
                    href="reports/index.php"
                    class="sidebar-link">

                    <span class="menu-icon">📊</span>

                    <span>Reports</span>

                </a>


                <!-- Activity -->

                <a
                    href="activity/index.php"
                    class="sidebar-link">

                    <span class="menu-icon">📋</span>

                    <span>Activity Logs</span>

                </a>

            </div>


            <!-- Divider -->

            <div class="sidebar-divider"></div>


            <!-- System -->

            <div class="sidebar-title">

                SYSTEM

            </div>


            <div class="sidebar-menu">


                <!-- Profile -->

                <a
                    href="profile/index.php"
                    class="sidebar-link">

                    <span class="menu-icon">👤</span>

                    <span>Admin Profile</span>

                </a>


                <!-- Settings -->

                <a
                    href="settings/index.php"
                    class="sidebar-link">

                    <span class="menu-icon">⚙️</span>

                    <span>Settings</span>

                </a>


                <!-- Back to User Portal -->

                <a
                    href="../dashboard/index.php"
                    class="sidebar-link">

                    <span class="menu-icon">↩️</span>

                    <span>User Portal</span>

                </a>


                <!-- Logout -->

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

        <main class="col-md-9 col-lg-10 admin-content">


            <!-- =================================================
                 WELCOME CARD
            ================================================== -->

            <div class="card welcome-card shadow-sm mb-4">

                <div class="card-body p-4">

                    <h3 class="fw-bold">

                        Welcome back,
                        <?= htmlspecialchars(
                            $_SESSION["full_name"]
                                ?? "Administrator"
                        ) ?> 👋

                    </h3>


                    <p class="mb-0">

                        Here's an overview of what's happening
                        across your SpeakFlow platform.

                    </p>

                </div>

            </div>



            <!-- =================================================
                 PAGE HEADER
            ================================================== -->

            <div class="mb-4">

                <h2 class="fw-bold">

                    Admin Dashboard

                </h2>


                <p class="text-muted">

                    Monitor users, documents, conversions and
                    system activity.

                </p>

            </div>



            <!-- =================================================
                 STATISTICS
            ================================================== -->

            <div class="row g-4 mb-4">


                <!-- Users -->

                <div class="col-sm-6 col-xl-3">

                    <div
                        class="card admin-stat-card
                               shadow-sm h-100">

                        <div class="card-body">

                            <div
                                class="d-flex
                                       justify-content-between
                                       align-items-center">

                                <div>

                                    <small class="text-muted">

                                        Total Users

                                    </small>


                                    <div class="stat-number">

                                        <?= number_format(
                                            $total_users
                                        ) ?>

                                    </div>

                                </div>


                                <div class="stat-icon">

                                    👥

                                </div>

                            </div>


                            <small class="text-success">

                                +<?= number_format(
                                    $today_users
                                ) ?>

                                today

                            </small>

                        </div>

                    </div>

                </div>



                <!-- Documents -->

                <div class="col-sm-6 col-xl-3">

                    <div
                        class="card admin-stat-card
                               shadow-sm h-100">

                        <div class="card-body">

                            <div
                                class="d-flex
                                       justify-content-between
                                       align-items-center">

                                <div>

                                    <small class="text-muted">

                                        Total Documents

                                    </small>


                                    <div class="stat-number">

                                        <?= number_format(
                                            $total_documents
                                        ) ?>

                                    </div>

                                </div>


                                <div class="stat-icon">

                                    📄

                                </div>

                            </div>

                        </div>

                    </div>

                </div>



                <!-- Conversions -->

                <div class="col-sm-6 col-xl-3">

                    <div
                        class="card admin-stat-card
                               shadow-sm h-100">

                        <div class="card-body">

                            <div
                                class="d-flex
                                       justify-content-between
                                       align-items-center">

                                <div>

                                    <small class="text-muted">

                                        Total Conversions

                                    </small>


                                    <div class="stat-number">

                                        <?= number_format(
                                            $total_conversions
                                        ) ?>

                                    </div>

                                </div>


                                <div class="stat-icon">

                                    🔊

                                </div>

                            </div>


                            <small class="text-success">

                                +<?= number_format(
                                    $today_conversions
                                ) ?>

                                today

                            </small>

                        </div>

                    </div>

                </div>



                <!-- Characters -->

                <div class="col-sm-6 col-xl-3">

                    <div
                        class="card admin-stat-card
                               shadow-sm h-100">

                        <div class="card-body">

                            <div
                                class="d-flex
                                       justify-content-between
                                       align-items-center">

                                <div>

                                    <small class="text-muted">

                                        Characters Converted

                                    </small>


                                    <div class="stat-number">

                                        <?= number_format(
                                            $total_characters
                                        ) ?>

                                    </div>

                                </div>


                                <div class="stat-icon">

                                    🔤

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            <!-- =================================================
                 QUICK ACTIONS
            ================================================== -->

            <div class="mb-4">

                <h5 class="fw-bold mb-3">

                    Quick Actions

                </h5>


                <div class="row g-3">


                    <div class="col-md-4">

                        <a
                            href="users/index.php"
                            class="quick-action">

                            <div
                                class="card
                                       quick-action-card
                                       shadow-sm">

                                <div class="card-body">

                                    <h6 class="fw-bold">

                                        👥 Manage Users

                                    </h6>


                                    <p
                                        class="text-muted
                                               small mb-0">

                                        View and manage
                                        SpeakFlow users.

                                    </p>

                                </div>

                            </div>

                        </a>

                    </div>


                    <div class="col-md-4">

                        <a
                            href="documents/index.php"
                            class="quick-action">

                            <div
                                class="card
                                       quick-action-card
                                       shadow-sm">

                                <div class="card-body">

                                    <h6 class="fw-bold">

                                        📄 Manage Documents

                                    </h6>


                                    <p
                                        class="text-muted
                                               small mb-0">

                                        Review saved user
                                        documents.

                                    </p>

                                </div>

                            </div>

                        </a>

                    </div>


                    <div class="col-md-4">

                        <a
                            href="reports/index.php"
                            class="quick-action">

                            <div
                                class="card
                                       quick-action-card
                                       shadow-sm">

                                <div class="card-body">

                                    <h6 class="fw-bold">

                                        📊 View Reports

                                    </h6>


                                    <p
                                        class="text-muted
                                               small mb-0">

                                        Analyze platform usage.

                                    </p>

                                </div>

                            </div>

                        </a>

                    </div>

                </div>

            </div>



            <!-- =================================================
                 RECENT USERS
            ================================================== -->

            <div class="card admin-section-card shadow-sm mb-4">

                <div class="card-body">

                    <div
                        class="d-flex
                               justify-content-between
                               align-items-center mb-3">

                        <h5 class="fw-bold mb-0">

                            Recent Users

                        </h5>


                        <a
                            href="users/index.php"
                            class="btn btn-sm
                                   btn-outline-primary">

                            View All

                        </a>

                    </div>


                    <div class="table-responsive">

                        <table
                            class="table
                                   admin-table
                                   table-hover
                                   mb-0">

                            <thead>

                                <tr>

                                    <th>Name</th>

                                    <th>Email</th>

                                    <th>Role</th>

                                </tr>

                            </thead>


                            <tbody>

                            <?php if (
                                empty($recent_users)
                            ): ?>

                                <tr>

                                    <td
                                        colspan="3"
                                        class="text-center
                                               text-muted">

                                        No users found.

                                    </td>

                                </tr>

                            <?php else: ?>

                                <?php foreach (
                                    $recent_users
                                    as $user
                                ): ?>

                                    <tr>

                                        <td>

                                            <?= htmlspecialchars(
                                                $user["full_name"]
                                            ) ?>

                                        </td>


                                        <td>

                                            <?= htmlspecialchars(
                                                $user["email"]
                                            ) ?>

                                        </td>


                                        <td>

                                            <?php if (
                                                $user["role"]
                                                === "admin"
                                            ): ?>

                                                <span
                                                    class="badge
                                                           bg-primary">

                                                    Admin

                                                </span>

                                            <?php else: ?>

                                                <span
                                                    class="badge
                                                           bg-secondary">

                                                    User

                                                </span>

                                            <?php endif; ?>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            <?php endif; ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>



            <!-- =================================================
                 RECENT CONVERSIONS
            ================================================== -->

            <div class="card admin-section-card shadow-sm">

                <div class="card-body">

                    <div
                        class="d-flex
                               justify-content-between
                               align-items-center mb-3">

                        <h5 class="fw-bold mb-0">

                            Recent Conversions

                        </h5>


                        <a
                            href="conversions/index.php"
                            class="btn btn-sm
                                   btn-outline-primary">

                            View All

                        </a>

                    </div>


                    <div class="table-responsive">

                        <table
                            class="table
                                   admin-table
                                   table-hover
                                   mb-0">

                            <thead>

                                <tr>

                                    <th>User</th>

                                    <th>Language</th>

                                    <th>Characters</th>

                                    <th>Speed</th>

                                    <th>Date</th>

                                </tr>

                            </thead>


                            <tbody>

                            <?php if (
                                empty($recent_conversions)
                            ): ?>

                                <tr>

                                    <td
                                        colspan="5"
                                        class="text-center
                                               text-muted">

                                        No conversions found.

                                    </td>

                                </tr>

                            <?php else: ?>

                                <?php foreach (
                                    $recent_conversions
                                    as $conversion
                                ): ?>

                                    <tr>

                                        <td>

                                            <?= htmlspecialchars(
                                                $conversion[
                                                    "full_name"
                                                ]
                                            ) ?>

                                        </td>


                                        <td>

                                            <?= htmlspecialchars(
                                                $conversion[
                                                    "language"
                                                ] ?? "Unknown"
                                            ) ?>

                                        </td>


                                        <td>

                                            <?= number_format(
                                                (int) (
                                                    $conversion[
                                                        "character_count"
                                                    ] ?? 0
                                                )
                                            ) ?>

                                        </td>


                                        <td>

                                            <?= htmlspecialchars(
                                                $conversion[
                                                    "speed"
                                                ] ?? "1"
                                            ) ?>x

                                        </td>


                                        <td>

                                            <?= htmlspecialchars(
                                                date(
                                                    "d M Y H:i",
                                                    strtotime(
                                                        $conversion[
                                                            "created_at"
                                                        ]
                                                    )
                                                )
                                            ) ?>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            <?php endif; ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


        </main>

    </div>

</div>



<!-- Bootstrap JS -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


</body>

</html>
```

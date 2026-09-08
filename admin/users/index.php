<?php

require_once "../../includes/admin_auth.php";
require_once "../../config/database.php";

$stmt = $pdo->query("
    SELECT id, full_name, email, role, created_at
    FROM users
    ORDER BY id DESC
");

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>

<html lang="en">

<head>

```
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Users - SpeakFlow Admin</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="../../assets/css/style.css">
```

</head>

<body>

<div class="container-fluid">

```
<div class="row">

    <!-- SIDEBAR -->
    <div class="col-md-3 col-lg-2 p-0">

        <aside class="sidebar">

            <div class="sidebar-brand">
                <a href="../index.php">
                    🎙️ <span>SpeakFlow</span>
                </a>
            </div>

            <div class="sidebar-title">
                ADMIN PANEL
            </div>

            <div class="sidebar-menu">

                <a href="../index.php" class="sidebar-link">
                    <span class="menu-icon">📊</span>
                    Dashboard
                </a>

                <a href="index.php" class="sidebar-link active">
                    <span class="menu-icon">👥</span>
                    Users
                </a>

                <a href="../documents/index.php" class="sidebar-link">
                    <span class="menu-icon">📄</span>
                    Documents
                </a>

                <a href="../conversions/index.php" class="sidebar-link">
                    <span class="menu-icon">🔊</span>
                    Conversions
                </a>

                <a href="../reports/index.php" class="sidebar-link">
                    <span class="menu-icon">📈</span>
                    Reports
                </a>

                <a href="../activity/index.php" class="sidebar-link">
                    <span class="menu-icon">📝</span>
                    Activity Logs
                </a>

            </div>

            <div class="sidebar-divider"></div>

            <div class="sidebar-title">
                ACCOUNT
            </div>

            <div class="sidebar-menu">

                <a href="../profile/index.php" class="sidebar-link">
                    <span class="menu-icon">👤</span>
                    Admin Profile
                </a>

                <a href="../settings/index.php" class="sidebar-link">
                    <span class="menu-icon">⚙️</span>
                    Settings
                </a>

                <a href="../../dashboard/index.php" class="sidebar-link">
                    <span class="menu-icon">🏠</span>
                    User Portal
                </a>

                <a href="../../auth/logout.php" class="sidebar-link logout-link">
                    <span class="menu-icon">🚪</span>
                    Logout
                </a>

            </div>

        </aside>

    </div>


    <!-- MAIN CONTENT -->
    <div class="col-md-9 col-lg-10">

        <main class="p-4">

            <!-- HEADER -->

            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>
                    <h2 class="fw-bold mb-1">
                        Users
                    </h2>

                    <p class="text-muted mb-0">
                        Manage SpeakFlow users and accounts.
                    </p>
                </div>

            </div>


            <!-- ALERTS -->

            <?php if (isset($_GET["deleted"])): ?>

                <div class="alert alert-success">
                    User deleted successfully.
                </div>

            <?php endif; ?>


            <?php if (isset($_GET["updated"])): ?>

                <div class="alert alert-success">
                    User updated successfully.
                </div>

            <?php endif; ?>


            <!-- SEARCH -->

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6">

                            <input
                                type="text"
                                id="userSearch"
                                class="form-control"
                                placeholder="Search users by name or email..."
                            >

                        </div>

                        <div class="col-md-3">

                            <select id="roleFilter" class="form-select">

                                <option value="">All Roles</option>

                                <option value="admin">
                                    Admin
                                </option>

                                <option value="user">
                                    User
                                </option>

                            </select>

                        </div>

                    </div>

                </div>

            </div>


            <!-- USERS TABLE -->

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table align-middle" id="usersTable">

                            <thead>

                                <tr>

                                    <th>#</th>

                                    <th>User</th>

                                    <th>Email</th>

                                    <th>Role</th>

                                    <th>Registered</th>

                                    <th class="text-end">
                                        Actions
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                            <?php if (count($users) > 0): ?>

                                <?php foreach ($users as $index => $user): ?>

                                    <tr>

                                        <td>
                                            <?= $index + 1 ?>
                                        </td>

                                        <td>

                                            <div class="fw-semibold">
                                                <?= htmlspecialchars($user["full_name"]) ?>
                                            </div>

                                        </td>

                                        <td>
                                            <?= htmlspecialchars($user["email"]) ?>
                                        </td>

                                        <td>

                                            <?php if ($user["role"] === "admin"): ?>

                                                <span class="badge bg-primary">
                                                    Admin
                                                </span>

                                            <?php else: ?>

                                                <span class="badge bg-secondary">
                                                    User
                                                </span>

                                            <?php endif; ?>

                                        </td>

                                        <td>

                                            <?= date(
                                                "d M Y",
                                                strtotime($user["created_at"])
                                            ) ?>

                                        </td>

                                        <td class="text-end">

                                            <a
                                                href="view.php?id=<?= $user["id"] ?>"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                View
                                            </a>

                                            <a
                                                href="edit.php?id=<?= $user["id"] ?>"
                                                class="btn btn-sm btn-outline-secondary"
                                            >
                                                Edit
                                            </a>

                                            <?php if ($user["id"] != $_SESSION["user_id"]): ?>

                                                <a
                                                    href="delete.php?id=<?= $user["id"] ?>"
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Are you sure you want to delete this user?');"
                                                >
                                                    Delete
                                                </a>

                                            <?php endif; ?>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            <?php else: ?>

                                <tr>

                                    <td colspan="6" class="text-center py-5">

                                        <div class="fs-1">
                                            👥
                                        </div>

                                        <h5>
                                            No users found
                                        </h5>

                                        <p class="text-muted">
                                            There are currently no registered users.
                                        </p>

                                    </td>

                                </tr>

                            <?php endif; ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </main>

    </div>

</div>
```

</div>

<script>

const searchInput = document.getElementById("userSearch");
const roleFilter = document.getElementById("roleFilter");

function filterUsers() {

    const search = searchInput.value.toLowerCase();
    const role = roleFilter.value.toLowerCase();

    const rows = document.querySelectorAll("#usersTable tbody tr");

    rows.forEach(row => {

        const text = row.innerText.toLowerCase();

        const roleCell = row.cells[3]
            ? row.cells[3].innerText.toLowerCase()
            : "";

        const matchesSearch = text.includes(search);

        const matchesRole =
            role === "" || roleCell.includes(role);

        row.style.display =
            matchesSearch && matchesRole
                ? ""
                : "none";

    });

}

searchInput.addEventListener("keyup", filterUsers);
roleFilter.addEventListener("change", filterUsers);

</script>

</body>
</html>

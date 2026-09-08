```php
<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

$user_id = $_SESSION["user_id"];

$success = "";
$error = "";

/*
|--------------------------------------------------------------------------
| Fetch Current User
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
    SELECT id, full_name, email, created_at
    FROM users
    WHERE id = ?
    LIMIT 1
");

$stmt->execute([$user_id]);

$user = $stmt->fetch();


/*
|--------------------------------------------------------------------------
| Update Profile
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $action = $_POST["action"] ?? "";


    /*
    |--------------------------------------------------------------------------
    | Update Personal Information
    |--------------------------------------------------------------------------
    */

    if ($action === "update_profile") {

        $full_name = trim($_POST["full_name"] ?? "");
        $email = trim($_POST["email"] ?? "");


        if ($full_name === "" || $email === "") {

            $error = "Please fill in all required fields.";

        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $error = "Please enter a valid email address.";

        } else {

            /*
            |--------------------------------------------------------------------------
            | Check if Email Already Exists
            |--------------------------------------------------------------------------
            */

            $checkStmt = $pdo->prepare("
                SELECT id
                FROM users
                WHERE email = ?
                AND id != ?
                LIMIT 1
            ");

            $checkStmt->execute([
                $email,
                $user_id
            ]);

            $existingUser = $checkStmt->fetch();


            if ($existingUser) {

                $error = "That email address is already in use.";

            } else {

                $updateStmt = $pdo->prepare("
                    UPDATE users
                    SET full_name = ?, email = ?
                    WHERE id = ?
                ");

                $updateStmt->execute([
                    $full_name,
                    $email,
                    $user_id
                ]);


                /*
                |--------------------------------------------------------------------------
                | Update Session
                |--------------------------------------------------------------------------
                */

                $_SESSION["full_name"] = $full_name;
                $_SESSION["email"] = $email;


                /*
                |--------------------------------------------------------------------------
                | Refresh User Data
                |--------------------------------------------------------------------------
                */

                $user["full_name"] = $full_name;
                $user["email"] = $email;

                $success = "Profile updated successfully.";
            }
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Change Password
    |--------------------------------------------------------------------------
    */

    if ($action === "change_password") {

        $current_password = $_POST["current_password"] ?? "";
        $new_password = $_POST["new_password"] ?? "";
        $confirm_password = $_POST["confirm_password"] ?? "";


        if (
            $current_password === "" ||
            $new_password === "" ||
            $confirm_password === ""
        ) {

            $error = "Please fill in all password fields.";

        } elseif ($new_password !== $confirm_password) {

            $error = "New passwords do not match.";

        } elseif (strlen($new_password) < 8) {

            $error = "New password must contain at least 8 characters.";

        } else {

            /*
            |--------------------------------------------------------------------------
            | Get Password Hash
            |--------------------------------------------------------------------------
            */

            $passwordStmt = $pdo->prepare("
                SELECT password
                FROM users
                WHERE id = ?
                LIMIT 1
            ");

            $passwordStmt->execute([$user_id]);

            $account = $passwordStmt->fetch();


            if (!$account || !password_verify(
                $current_password,
                $account["password"]
            )) {

                $error = "Current password is incorrect.";

            } else {

                /*
                |--------------------------------------------------------------------------
                | Hash New Password
                |--------------------------------------------------------------------------
                */

                $new_password_hash = password_hash(
                    $new_password,
                    PASSWORD_DEFAULT
                );


                /*
                |--------------------------------------------------------------------------
                | Update Password
                |--------------------------------------------------------------------------
                */

                $updatePassword = $pdo->prepare("
                    UPDATE users
                    SET password = ?
                    WHERE id = ?
                ");

                $updatePassword->execute([
                    $new_password_hash,
                    $user_id
                ]);

                $success = "Password changed successfully.";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>My Profile - SpeakFlow</title>


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


        <!-- Brand -->

        <a
            class="navbar-brand fw-bold"
            href="../dashboard/index.php">

            🎙️ SpeakFlow

        </a>


        <!-- User -->

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

                <a
                    href="../dashboard/index.php"
                    class="sidebar-link">

                    <span class="menu-icon">🏠</span>

                    <span>Dashboard</span>

                </a>


                <!-- Text to Speech -->

                <a
                    href="../converter/index.php"
                    class="sidebar-link">

                    <span class="menu-icon">🔊</span>

                    <span>Text to Speech</span>

                </a>


                <!-- Documents -->

                <a
                    href="../documents/index.php"
                    class="sidebar-link">

                    <span class="menu-icon">📄</span>

                    <span>My Documents</span>

                </a>


                <!-- History -->

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


                <!-- Profile -->

                <a
                    href="index.php"
                    class="sidebar-link active">

                    <span class="menu-icon">👤</span>

                    <span>My Profile</span>

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

        <main class="col-md-9 col-lg-10 p-4">


            <!-- Page Header -->

            <div class="mb-4">

                <h2 class="fw-bold">

                    My Profile

                </h2>


                <p class="text-muted">

                    Manage your account information and password.

                </p>

            </div>



            <!-- =================================================
                 ALERTS
            ================================================== -->

            <?php if ($success !== ""): ?>

                <div
                    class="alert alert-success
                           alert-dismissible fade show">

                    <?= htmlspecialchars($success) ?>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                    </button>

                </div>

            <?php endif; ?>


            <?php if ($error !== ""): ?>

                <div
                    class="alert alert-danger
                           alert-dismissible fade show">

                    <?= htmlspecialchars($error) ?>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                    </button>

                </div>

            <?php endif; ?>



            <div class="row g-4">


                <!-- =================================================
                     PROFILE INFORMATION
                ================================================== -->

                <div class="col-lg-7">

                    <div class="card border-0 shadow-sm h-100">

                        <div class="card-body p-4">


                            <h5 class="fw-bold mb-1">

                                Personal Information

                            </h5>


                            <p class="text-muted small mb-4">

                                Update your name and email address.

                            </p>


                            <form
                                method="POST"
                                action="">


                                <input
                                    type="hidden"
                                    name="action"
                                    value="update_profile">


                                <!-- Full Name -->

                                <div class="mb-3">

                                    <label
                                        for="full_name"
                                        class="form-label fw-semibold">

                                        Full Name

                                    </label>


                                    <input
                                        type="text"
                                        id="full_name"
                                        name="full_name"
                                        class="form-control"
                                        value="<?= htmlspecialchars(
                                            $user["full_name"] ?? ""
                                        ) ?>"
                                        required>

                                </div>


                                <!-- Email -->

                                <div class="mb-3">

                                    <label
                                        for="email"
                                        class="form-label fw-semibold">

                                        Email Address

                                    </label>


                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        class="form-control"
                                        value="<?= htmlspecialchars(
                                            $user["email"] ?? ""
                                        ) ?>"
                                        required>

                                </div>


                                <!-- Account Created -->

                                <div class="mb-4">

                                    <label
                                        class="form-label fw-semibold">

                                        Account Created

                                    </label>


                                    <input
                                        type="text"
                                        class="form-control bg-light"
                                        value="<?= htmlspecialchars(
                                            date(
                                                "d M Y",
                                                strtotime(
                                                    $user["created_at"]
                                                )
                                            )
                                        ) ?>"
                                        readonly>

                                </div>


                                <!-- Save -->

                                <button
                                    type="submit"
                                    class="btn btn-primary">

                                    Save Changes

                                </button>

                            </form>

                        </div>

                    </div>

                </div>



                <!-- =================================================
                     ACCOUNT SUMMARY
                ================================================== -->

                <div class="col-lg-5">

                    <div class="card border-0 shadow-sm">

                        <div class="card-body p-4 text-center">


                            <!-- Profile Icon -->

                            <div
                                class="rounded-circle
                                       bg-primary text-white
                                       d-flex align-items-center
                                       justify-content-center
                                       mx-auto mb-3"
                                style="
                                    width: 80px;
                                    height: 80px;
                                    font-size: 32px;
                                ">

                                👤

                            </div>


                            <h4 class="fw-bold">

                                <?= htmlspecialchars(
                                    $user["full_name"] ?? "User"
                                ) ?>

                            </h4>


                            <p class="text-muted">

                                <?= htmlspecialchars(
                                    $user["email"] ?? ""
                                ) ?>

                            </p>


                            <hr>


                            <div
                                class="text-start">

                                <p class="mb-2">

                                    <strong>Account Status:</strong>

                                    <span
                                        class="badge bg-success ms-2">

                                        Active

                                    </span>

                                </p>


                                <p class="mb-0">

                                    <strong>Member Since:</strong>

                                    <?= htmlspecialchars(
                                        date(
                                            "d M Y",
                                            strtotime(
                                                $user["created_at"]
                                            )
                                        )
                                    ) ?>

                                </p>

                            </div>

                        </div>

                    </div>

                </div>



                <!-- =================================================
                     CHANGE PASSWORD
                ================================================== -->

                <div class="col-12">

                    <div class="card border-0 shadow-sm">

                        <div class="card-body p-4">


                            <h5 class="fw-bold mb-1">

                                Change Password

                            </h5>


                            <p class="text-muted small mb-4">

                                Use a strong password to keep your
                                SpeakFlow account secure.

                            </p>


                            <form
                                method="POST"
                                action="">


                                <input
                                    type="hidden"
                                    name="action"
                                    value="change_password">


                                <div class="row g-3">


                                    <!-- Current Password -->

                                    <div class="col-md-4">

                                        <label
                                            for="current_password"
                                            class="form-label fw-semibold">

                                            Current Password

                                        </label>


                                        <input
                                            type="password"
                                            id="current_password"
                                            name="current_password"
                                            class="form-control"
                                            required>

                                    </div>


                                    <!-- New Password -->

                                    <div class="col-md-4">

                                        <label
                                            for="new_password"
                                            class="form-label fw-semibold">

                                            New Password

                                        </label>


                                        <input
                                            type="password"
                                            id="new_password"
                                            name="new_password"
                                            class="form-control"
                                            minlength="8"
                                            required>

                                    </div>


                                    <!-- Confirm Password -->

                                    <div class="col-md-4">

                                        <label
                                            for="confirm_password"
                                            class="form-label fw-semibold">

                                            Confirm New Password

                                        </label>


                                        <input
                                            type="password"
                                            id="confirm_password"
                                            name="confirm_password"
                                            class="form-control"
                                            minlength="8"
                                            required>

                                    </div>

                                </div>


                                <button
                                    type="submit"
                                    class="btn btn-primary mt-4">

                                    Change Password

                                </button>

                            </form>

                        </div>

                    </div>

                </div>


            </div>

        </main>

    </div>

</div>



<!-- =========================================================
     BOOTSTRAP JAVASCRIPT
========================================================= -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


</body>

</html>
```

One important thing: **the password section assumes your registration system stores passwords using `password_hash()`**. If your existing `register.php` already does that, this will work correctly.

With this page added, the main user-side SpeakFlow module is now structurally complete. The next step should be making **Edit Document (`documents/edit.php`) and Delete Document (`documents/delete.php`)** fully functional and secure.

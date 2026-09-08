<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>SpeakFlow - Text to Speech Converter</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link
        rel="stylesheet"
        href="assets/css/style.css">

</head>

<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">

    <div class="container">

        <a class="navbar-brand fw-bold" href="index.php">
            🎙️ SpeakFlow
        </a>

        <div class="ms-auto">

            <?php if (isset($_SESSION['user_id'])): ?>

                <a href="dashboard/index.php"
                   class="btn btn-primary">
                    Dashboard
                </a>

            <?php else: ?>

                <a href="auth/login.php"
                   class="btn btn-outline-primary me-2">
                    Login
                </a>

                <a href="auth/register.php"
                   class="btn btn-primary">
                    Get Started
                </a>

            <?php endif; ?>

        </div>

    </div>

</nav>


<section class="hero">

    <div class="container">

        <div class="row align-items-center min-vh-75">

            <div class="col-lg-7">

                <span class="badge bg-primary mb-3">
                    TEXT TO SPEECH PLATFORM
                </span>

                <h1 class="display-4 fw-bold">

                    Turn Your Text Into
                    <span class="text-primary">
                        Speech
                    </span>

                </h1>

                <p class="lead text-muted mt-3">

                    SpeakFlow transforms written text into
                    natural-sounding speech quickly and easily.

                </p>

                <div class="mt-4">

                    <a href="auth/register.php"
                       class="btn btn-primary btn-lg me-2">

                        Start Converting

                    </a>

                    <a href="#features"
                       class="btn btn-outline-secondary btn-lg">

                        Explore Features

                    </a>

                </div>

            </div>


            <div class="col-lg-5">

                <div class="converter-preview shadow">

                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Enter your text
                        </label>

                        <textarea
                            class="form-control"
                            rows="6"
                            placeholder="Type or paste your text here..."></textarea>

                    </div>

                    <button class="btn btn-primary w-100">

                        🔊 Speak

                    </button>

                </div>

            </div>

        </div>

    </div>

</section>


<section id="features"
         class="py-5 bg-light">

    <div class="container">

        <div class="text-center mb-5">

            <h2 class="fw-bold">
                Powerful Features
            </h2>

            <p class="text-muted">
                Everything you need for a modern
                text-to-speech experience.
            </p>

        </div>


        <div class="row g-4">

            <div class="col-md-4">

                <div class="feature-card">

                    <div class="feature-icon">
                        🔊
                    </div>

                    <h5>
                        Text to Speech
                    </h5>

                    <p>
                        Convert written content into
                        spoken words instantly.
                    </p>

                </div>

            </div>


            <div class="col-md-4">

                <div class="feature-card">

                    <div class="feature-icon">
                        🎙️
                    </div>

                    <h5>
                        Voice Controls
                    </h5>

                    <p>
                        Customize voice, speed,
                        pitch and volume.
                    </p>

                </div>

            </div>


            <div class="col-md-4">

                <div class="feature-card">

                    <div class="feature-icon">
                        📚
                    </div>

                    <h5>
                        Save Documents
                    </h5>

                    <p>
                        Save your texts and access
                        them whenever you need them.
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>


<footer class="py-4 bg-dark text-white">

    <div class="container text-center">

        <p class="mb-0">
            © <?php echo date("Y"); ?> SpeakFlow.
            All rights reserved.
        </p>

    </div>

</footer>

</body>

</html>
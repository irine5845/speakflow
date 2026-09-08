<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

$user_id = $_SESSION["user_id"];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Text to Speech - SpeakFlow</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link
        rel="stylesheet"
        href="../assets/css/style.css">

</head>

<body class="bg-light">

<!-- NAVBAR -->

<nav class="navbar navbar-expand-lg bg-white shadow-sm">

    <div class="container-fluid px-4">

        <a class="navbar-brand fw-bold"
           href="../dashboard/index.php">

            🎙️ SpeakFlow

        </a>

        <div class="d-flex align-items-center gap-3">

            <span class="text-muted">

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

                <a href="../dashboard/index.php"
                   class="list-group-item list-group-item-action">

                    🏠 Dashboard

                </a>

                <a href="index.php"
                   class="list-group-item list-group-item-action active">

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
                    Text to Speech
                </h2>

                <p class="text-muted">
                    Enter your text and convert it into speech.
                </p>

            </div>


            <div class="row g-4">

                <!-- TEXT EDITOR -->

                <div class="col-lg-8">

                    <div class="card border-0 shadow-sm">

                        <div class="card-body p-4">

                            <div class="d-flex justify-content-between
                                        align-items-center mb-3">

                                <label
                                    for="textInput"
                                    class="fw-bold">

                                    Your Text

                                </label>

                                <span
                                    id="characterCount"
                                    class="text-muted">

                                    0 characters

                                </span>

                            </div>


                            <textarea
                                id="textInput"
                                class="form-control"
                                rows="14"
                                maxlength="5000"
                                placeholder="Type or paste your text here..."></textarea>


                            <div class="d-flex justify-content-between
                                        mt-2">

                                <small class="text-muted">

                                    Maximum 5,000 characters

                                </small>

                                <small
                                    id="wordCount"
                                    class="text-muted">

                                    0 words

                                </small>

                            </div>


                            <!-- CONTROLS -->

                            <div class="mt-4">

                                <div class="row g-3">

                                    <div class="col-md-6">

                                        <label class="form-label fw-semibold">

                                            Language

                                        </label>

                                        <select
                                            id="language"
                                            class="form-select">

                                            <option value="en-US">
                                                English (US)
                                            </option>

                                            <option value="en-GB">
                                                English (UK)
                                            </option>

                                            <option value="en-AU">
                                                English (Australia)
                                            </option>

                                            <option value="fr-FR">
                                                French
                                            </option>

                                            <option value="de-DE">
                                                German
                                            </option>

                                            <option value="es-ES">
                                                Spanish
                                            </option>

                                            <option value="it-IT">
                                                Italian
                                            </option>

                                            <option value="pt-BR">
                                                Portuguese
                                            </option>

                                            <option value="sw-KE">
                                                Swahili
                                            </option>

                                        </select>

                                    </div>


                                    <div class="col-md-6">

                                        <label class="form-label fw-semibold">

                                            Voice

                                        </label>

                                        <select
                                            id="voice"
                                            class="form-select">

                                            <option>
                                                Loading voices...
                                            </option>

                                        </select>

                                    </div>

                                </div>


                                <div class="row g-3 mt-2">

                                    <div class="col-md-4">

                                        <label class="form-label fw-semibold">

                                            Speed

                                        </label>

                                        <input
                                            type="range"
                                            id="speed"
                                            class="form-range"
                                            min="0.5"
                                            max="2"
                                            step="0.1"
                                            value="1">

                                        <div
                                            class="text-center"
                                            id="speedValue">

                                            1.0x

                                        </div>

                                    </div>


                                    <div class="col-md-4">

                                        <label class="form-label fw-semibold">

                                            Pitch

                                        </label>

                                        <input
                                            type="range"
                                            id="pitch"
                                            class="form-range"
                                            min="0"
                                            max="2"
                                            step="0.1"
                                            value="1">

                                        <div
                                            class="text-center"
                                            id="pitchValue">

                                            1.0

                                        </div>

                                    </div>


                                    <div class="col-md-4">

                                        <label class="form-label fw-semibold">

                                            Volume

                                        </label>

                                        <input
                                            type="range"
                                            id="volume"
                                            class="form-range"
                                            min="0"
                                            max="1"
                                            step="0.1"
                                            value="1">

                                        <div
                                            class="text-center"
                                            id="volumeValue">

                                            100%

                                        </div>

                                    </div>

                                </div>

                            </div>


                            <!-- BUTTONS -->

                            <div class="d-flex flex-wrap gap-2 mt-4">

                                <button
                                    id="speakBtn"
                                    class="btn btn-primary">

                                    ▶️ Speak

                                </button>

                                <button
                                    id="pauseBtn"
                                    class="btn btn-warning">

                                    ⏸️ Pause

                                </button>

                                <button
                                    id="resumeBtn"
                                    class="btn btn-success">

                                    ▶️ Resume

                                </button>

                                <button
                                    id="stopBtn"
                                    class="btn btn-danger">

                                    ⏹️ Stop

                                </button>

                                <button
                                    id="clearBtn"
                                    class="btn btn-outline-secondary">

                                    🗑️ Clear

                                </button>

                                <button
    type="button"
    id="saveBtn"
    class="btn btn-success">

    💾 Save Document

</button>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- INFORMATION PANEL -->

                <div class="col-lg-4">

                    <div class="card border-0 shadow-sm">

                        <div class="card-body p-4">

                            <h5 class="fw-bold mb-3">

                                🎙️ Speech Settings

                            </h5>

                            <p class="text-muted">

                                Customize how SpeakFlow reads
                                your text.

                            </p>


                            <hr>


                            <div class="mb-3">

                                <strong>
                                    Language
                                </strong>

                                <p class="text-muted small mb-0">

                                    Choose the language that
                                    matches your text.

                                </p>

                            </div>


                            <div class="mb-3">

                                <strong>
                                    Voice
                                </strong>

                                <p class="text-muted small mb-0">

                                    Select an available voice
                                    installed in your browser.

                                </p>

                            </div>


                            <div class="mb-3">

                                <strong>
                                    Speed
                                </strong>

                                <p class="text-muted small mb-0">

                                    Adjust how quickly the text
                                    is spoken.

                                </p>

                            </div>


                            <div>

                                <strong>
                                    Pitch
                                </strong>

                                <p class="text-muted small mb-0">

                                    Change the tone of the voice.

                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="card border-0 shadow-sm mt-4">

                        <div class="card-body p-4">

                            <h5 class="fw-bold">
                                💡 Tip
                            </h5>

                            <p class="text-muted mb-0">

                                For the best results, use punctuation
                                such as commas and full stops to help
                                the speech engine determine natural
                                pauses.

                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </main>

    </div>

</div>

<!-- SAVE DOCUMENT MODAL -->

<div class="modal fade"
     id="saveDocumentModal"
     tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Save Document
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <form
                method="POST"
                action="../documents/save.php">

                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Document Title
                        </label>

                        <input
                            type="text"
                            name="title"
                            class="form-control"
                            placeholder="Enter document title"
                            required>

                    </div>

                    <input
                        type="hidden"
                        name="content"
                        id="saveContent">

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary">

                        Save Document

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

<script src="../assets/js/app.js"></script>


<script src="../assets/js/app.js"></script>

</body>

</html>
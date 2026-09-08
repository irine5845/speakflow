document.addEventListener("DOMContentLoaded", function () {

    const textInput = document.getElementById("textInput");

    const characterCount =
        document.getElementById("characterCount");

    const wordCount =
        document.getElementById("wordCount");

    const language =
        document.getElementById("language");

    const voiceSelect =
        document.getElementById("voice");

    const speed =
        document.getElementById("speed");

    const pitch =
        document.getElementById("pitch");

    const volume =
        document.getElementById("volume");

    const speedValue =
        document.getElementById("speedValue");

    const pitchValue =
        document.getElementById("pitchValue");

    const volumeValue =
        document.getElementById("volumeValue");

    const speakBtn =
        document.getElementById("speakBtn");

    const pauseBtn =
        document.getElementById("pauseBtn");

    const resumeBtn =
        document.getElementById("resumeBtn");

    const stopBtn =
        document.getElementById("stopBtn");

    const clearBtn =
        document.getElementById("clearBtn");


    let voices = [];

    let speech = window.speechSynthesis;


    // ==========================
    // LOAD VOICES
    // ==========================

    function loadVoices() {

        voices = speech.getVoices();

        voiceSelect.innerHTML = "";

        const selectedLanguage = language.value;

        const filteredVoices = voices.filter(function (voice) {

            return voice.lang
                .toLowerCase()
                .startsWith(
                    selectedLanguage.substring(0, 2)
                        .toLowerCase()
                );

        });


        const availableVoices =
            filteredVoices.length > 0
                ? filteredVoices
                : voices;


        availableVoices.forEach(function (voice, index) {

            const option =
                document.createElement("option");

            option.value = voices.indexOf(voice);

            option.textContent =
                `${voice.name} (${voice.lang})`;

            voiceSelect.appendChild(option);

        });

    }


    loadVoices();


    if (speech.onvoiceschanged !== undefined) {

        speech.onvoiceschanged = loadVoices;

    }


    // ==========================
    // TEXT COUNTER
    // ==========================

    function updateCounts() {

        const text = textInput.value;

        const characters = text.length;

        const words =
            text.trim() === ""
                ? 0
                : text.trim().split(/\s+/).length;


        characterCount.textContent =
            `${characters} characters`;

        wordCount.textContent =
            `${words} words`;

    }


    textInput.addEventListener(
        "input",
        updateCounts
    );


    // ==========================
    // LANGUAGE CHANGE
    // ==========================

    language.addEventListener(
        "change",
        loadVoices
    );


    // ==========================
    // SPEED
    // ==========================

    speed.addEventListener(
        "input",
        function () {

            speedValue.textContent =
                `${parseFloat(speed.value).toFixed(1)}x`;

        }
    );


    // ==========================
    // PITCH
    // ==========================

    pitch.addEventListener(
        "input",
        function () {

            pitchValue.textContent =
                parseFloat(pitch.value).toFixed(1);

        }
    );


    // ==========================
    // VOLUME
    // ==========================

    volume.addEventListener(
        "input",
        function () {

            volumeValue.textContent =
                `${Math.round(volume.value * 100)}%`;

        }
    );


    // ==========================
    // SPEAK
    // ==========================

    speakBtn.addEventListener(
        "click",
        function () {

            const text = textInput.value.trim();

            if (text === "") {

                alert("Please enter some text first.");

                return;

            }


            speech.cancel();


            const utterance =
                new SpeechSynthesisUtterance(text);


            utterance.rate =
                parseFloat(speed.value);

            utterance.pitch =
                parseFloat(pitch.value);

            utterance.volume =
                parseFloat(volume.value);

            utterance.lang =
                language.value;


            const selectedVoiceIndex =
                parseInt(voiceSelect.value);


            if (
                !isNaN(selectedVoiceIndex) &&
                voices[selectedVoiceIndex]
            ) {

                utterance.voice =
                    voices[selectedVoiceIndex];

            }


            speech.speak(utterance);

        }
    );


    // ==========================
    // PAUSE
    // ==========================

    pauseBtn.addEventListener(
        "click",
        function () {

            if (speech.speaking) {

                speech.pause();

            }

        }
    );


    // ==========================
    // RESUME
    // ==========================

    resumeBtn.addEventListener(
        "click",
        function () {

            if (speech.paused) {

                speech.resume();

            }

        }
    );


    // ==========================
    // STOP
    // ==========================

    stopBtn.addEventListener(
        "click",
        function () {

            speech.cancel();

        }
    );


    // ==========================
    // CLEAR
    // ==========================

    clearBtn.addEventListener(
        "click",
        function () {

            speech.cancel();

            textInput.value = "";

            updateCounts();

        }
    );

});
<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    http_response_code(405);

    echo json_encode([
        "success" => false
    ]);

    exit;
}

$user_id = $_SESSION["user_id"];

$data = json_decode(
    file_get_contents("php://input"),
    true
);

$text = trim($data["text"] ?? "");

$language = trim(
    $data["language"] ?? "en-US"
);

$voice = trim(
    $data["voice"] ?? ""
);

$speed = floatval(
    $data["speed"] ?? 1
);

$pitch = floatval(
    $data["pitch"] ?? 1
);

$volume = floatval(
    $data["volume"] ?? 1
);


if ($text === "") {

    echo json_encode([
        "success" => false,
        "message" => "Text cannot be empty."
    ]);

    exit;
}


$character_count = mb_strlen($text);


$stmt = $pdo->prepare("
    INSERT INTO conversions
    (
        user_id,
        language,
        voice,
        speed,
        pitch,
        volume,
        character_count,
        status
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, 'completed')
");


$stmt->execute([
    $user_id,
    $language,
    $voice,
    $speed,
    $pitch,
    $volume,
    $character_count
]);


echo json_encode([
    "success" => true
])
;


require_once "../includes/auth.php";
require_once "../config/database.php";


/*
|--------------------------------------------------------------------------
| Response Configuration
|--------------------------------------------------------------------------
*/

header("Content-Type: application/json");



/*
|--------------------------------------------------------------------------
| Request Method
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    http_response_code(405);

    echo json_encode([
        "success" => false,
        "message" => "Only POST requests are allowed."
    ]);

    exit;
}



/*
|--------------------------------------------------------------------------
| Current User
|--------------------------------------------------------------------------
*/

$user_id = $_SESSION["user_id"];



/*
|--------------------------------------------------------------------------
| Get Request Data
|--------------------------------------------------------------------------
*/

$input = file_get_contents("php://input");

$data = json_decode($input, true);

if (!is_array($data)) {

    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => "Invalid request data."
    ]);

    exit;
}



/*
|--------------------------------------------------------------------------
| Conversion Data
|--------------------------------------------------------------------------
*/

$text = trim($data["text"] ?? "");

$language = trim(
    $data["language"] ?? "en-US"
);

$voice = trim(
    $data["voice"] ?? ""
);

$speed = floatval(
    $data["speed"] ?? 1
);

$pitch = floatval(
    $data["pitch"] ?? 1
);

$volume = floatval(
    $data["volume"] ?? 1
);



/*
|--------------------------------------------------------------------------
| Validate Text
|--------------------------------------------------------------------------
*/

if ($text === "") {

    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => "Text cannot be empty."
    ]);

    exit;
}



/*
|--------------------------------------------------------------------------
| Validate Conversion Settings
|--------------------------------------------------------------------------
*/

if ($speed <= 0) {
    $speed = 1;
}

if ($pitch < 0) {
    $pitch = 1;
}

if ($volume < 0) {
    $volume = 1;
}



/*
|--------------------------------------------------------------------------
| Character Count
|--------------------------------------------------------------------------
*/

$character_count = mb_strlen($text);



/*
|--------------------------------------------------------------------------
| Save Conversion
|--------------------------------------------------------------------------
*/

try {

    $stmt = $pdo->prepare("
        INSERT INTO conversions (
            user_id,
            language,
            voice,
            speed,
            pitch,
            volume,
            character_count,
            status
        )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $user_id,
        $language,
        $voice,
        $speed,
        $pitch,
        $volume,
        $character_count,
        "completed"
    ]);


    /*
    |--------------------------------------------------------------------------
    | Successful Response
    |--------------------------------------------------------------------------
    */

    echo json_encode([
        "success" => true,
        "message" => "Conversion saved successfully.",
        "conversion_id" => $pdo->lastInsertId(),
        "character_count" => $character_count
    ]);

} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Unable to save conversion."
    ]);

    exit;
}
?>

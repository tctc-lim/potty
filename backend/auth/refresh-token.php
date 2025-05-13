<?php

// Handle preflight (OPTIONS) request early
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    header("Access-Control-Allow-Origin: http://127.0.0.1:5500");
    header("Access-Control-Allow-Methods: POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-Control-Max-Age: 86400"); // Cache preflight for 1 day
    http_response_code(204); // No Content
    exit();
}

// CORS headers for actual request
header("Access-Control-Allow-Origin: http://127.0.0.1:5500");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

require "../../vendor/autoload.php";
require "db.php"; // Ensure this file contains a valid `$conn` (PDO connection)
use Firebase\JWT\JWT;

$secretKey = "admin@poeintl1224";

// ✅ Get JSON data securely
$input = file_get_contents("php://input");
$data = json_decode($input, true);

// ✅ Validate input
if (!isset($data["refreshToken"])) {
    echo json_encode(["error" => "Refresh token is required"]);
    exit();
}

$refreshToken = $data["refreshToken"];

try {
    // Decode the refresh token
    $decoded = JWT::decode($refreshToken, new Firebase\JWT\Key($secretKey, "HS256"));
    
    // Check if the refresh token is expired
    if ($decoded->exp < time()) {
        http_response_code(401); // Unauthorized
        echo json_encode(["error" => "Refresh token expired"]);
        exit();
    }

    // Generate a new access token using the data from the refresh token
    $user = (array) $decoded; // Convert the decoded object to an array
    $newAccessToken = generateJWT($user);

    // Return the new access token
    echo json_encode([
        "success" => true,
        "accessToken" => $newAccessToken
    ]);

} catch (Exception $e) {
    http_response_code(401); // Unauthorized
    echo json_encode(["error" => "Invalid refresh token"]);
    exit();
}

// Function to generate the new JWT (Access Token)
function generateJWT($user) {
    global $secretKey;

    // JWT Payload
    $payload = [
        "id" => $user["id"],
        "name" => $user["name"],
        "email" => $user["email"],
        "role" => $user["role"],
        "iat" => time(),
        "exp" => time() + 3600 // Token expires in 1 hour
    ];

    return JWT::encode($payload, $secretKey, "HS256");
}

?>

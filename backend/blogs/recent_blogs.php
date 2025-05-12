<?php
require "../auth/db.php";

<<<<<<< HEAD
header("Access-Control-Allow-Origin: http://127.0.0.1:5500");
=======
header("Access-Control-Allow-Origin: https://mylovesense.online");
>>>>>>> ff70d7d (first commit from local)
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(204);
    exit();
}

// Fetch the last 5 blogs (most recent first)
try {
    $stmt = $conn->prepare("SELECT id, title, created_at, status FROM blogs ORDER BY created_at DESC LIMIT 5");
    $stmt->execute();
    $recentBlogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["success" => true, "recent_blogs" => $recentBlogs]);
} catch (Exception $e) {
    echo json_encode(["error" => "Failed to fetch recent blogs", "details" => $e->getMessage()]);
}
?>

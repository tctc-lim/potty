<?php
require "../auth/db.php";  // Your DB connection
header("Access-Control-Allow-Origin: *"); // Allow cross-origin requests
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Handle GET requests for random blogs
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    if (isset($_GET["id"])) {
        $currentBlogId = $_GET["id"];

        try {
            // Fetch 3 random blogs excluding the current one
            $stmt = $conn->prepare("
                SELECT id, title, image1, content1, created_at
                FROM blogs
                WHERE id != :id
                ORDER BY RANDOM()  -- Randomize the selection
                LIMIT 3
            ");
            $stmt->bindParam(":id", $currentBlogId, PDO::PARAM_INT);
            $stmt->execute();
            $blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Return the fetched random blogs
            echo json_encode([
                "success" => true,
                "related_blogs" => $blogs
            ]);
        } catch (Exception $e) {
            echo json_encode([
                "success" => false,
                "error" => "Failed to fetch random blogs.",
                "details" => $e->getMessage()
            ]);
        }
    } else {
        echo json_encode([
            "success" => false,
            "error" => "Blog ID is required to fetch random related blogs."
        ]);
    }
}

<?php
header("Access-Control-Allow-Origin: http://127.0.0.1:5500");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Handle preflight CORS requests
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
	http_response_code(204);
	exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$to = "asamavictor225@gmail.com";

	// Sanitize and assign POST data
	$name = isset($_POST['Name']) ? htmlspecialchars(trim($_POST['Name'])) : '';
	$email = isset($_POST['Email']) ? htmlspecialchars(trim($_POST['Email'])) : '';
	$mainService = isset($_POST['mainService']) ? htmlspecialchars(trim($_POST['mainService'])) : '';
	$subService = isset($_POST['subService']) ? htmlspecialchars(trim($_POST['subService'])) : '';
	$message = isset($_POST['Message']) ? htmlspecialchars(trim($_POST['Message'])) : '';

	$subject = 'Poe International Appoinntment Request';
	$from = 'no-reply@poeintl.com';

	// To send HTML mail, the Content-type header must be set
	$headers = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	// Create email headers
	$headers .= 'From: ' . $from . "\r\n" . 'Reply-To: ' . $from . "\r\n" . 'X-Mailer: PHP/' . phpversion();

	$msg = "
		<strong>Name:</strong> $name<br>
		<strong>Email:</strong> $email<br>
		<strong>Main Service:</strong> $mainService<br>
		<strong>Sub-Service:</strong> $subService<br>
		<strong>Message:</strong><br>$message
	";

	// Sending email
	if (mail($to, $subject, $msg, $headers)) {
		$arr['status'] = 200;
		$arr['statusText'] = "Contact message received.";
		echo json_encode($arr);
	} else {
		$arr['status'] = 400;
		$arr['statusText'] = "An error occurred while trying to send your contact form.";
		echo json_encode($arr);
	}
} else {
	http_response_code(405);
	echo json_encode(['status' => 405, 'statusText' => 'Method Not Allowed']);
	exit;
}

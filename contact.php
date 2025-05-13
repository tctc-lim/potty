<?php
// Allow CORS for your frontend
header("Access-Control-Allow-Origin: http://127.0.0.1:5500");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Preflight request
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
	http_response_code(204);
	exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$to = "info@poeinternational.com";
	$from = "info@poeinternational.com";

	// Check if all fields are provided
	if (
		empty($_POST['Name']) || empty($_POST['Email']) ||
		empty($_POST['mainService']) || empty($_POST['subService']) ||
		empty($_POST['Message'])
	) {
		http_response_code(400);
		echo json_encode([
			'status' => 400,
			'statusText' => 'Missing required fields.'
		]);
		exit;
	}

	// Sanitize input
	$name = htmlspecialchars(trim($_POST['Name']));
	$email = htmlspecialchars(trim($_POST['Email']));
	$mainService = htmlspecialchars(trim($_POST['mainService']));
	$subService = htmlspecialchars(trim($_POST['subService']));
	$message = htmlspecialchars(trim($_POST['Message']));

	$subject = "POE International Appointment Request";

	// Email message
	$msg = "
		<strong>Name:</strong> {$name}<br>
		<strong>Email:</strong> {$email}<br>
		<strong>Main Service:</strong> {$mainService}<br>
		<strong>Sub-Service:</strong> {$subService}<br>
		<strong>Message:</strong> {$message}
	";

	// Email headers
	$headers = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=UTF-8\r\n";
	$headers .= "From: POE International <{$from}>\r\n";
	$headers .= "Reply-To: {$from}\r\n";

	if (mail($to, $subject, $msg, $headers)) {
		echo json_encode([
			'status' => 200,
			'statusText' => 'Contact message received.'
		]);
	} else {
		http_response_code(500);
		echo json_encode([
			'status' => 500,
			'statusText' => 'Server failed to send the email.'
		]);
	}
} else {
	http_response_code(405);
	echo json_encode([
		'status' => 405,
		'statusText' => 'Method Not Allowed'
	]);
}

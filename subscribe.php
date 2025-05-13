<?php
header("Access-Control-Allow-Origin: http://127.0.0.1:5500");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['email'])) {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address.";
        exit;
    }

    $adminEmail = "info@poeinternational.com";
    $adminSubject = "New Subscriber to POE International";
    $adminMessage = "
    <html>
    <head><title>New Subscriber</title></head>
    <body>
      <p>A new user has subscribed:</p>
      <p><strong>$email</strong></p>
    </body>
    </html>
    ";
    $adminHeaders = "MIME-Version: 1.0\r\n";
    $adminHeaders .= "Content-type: text/html; charset=UTF-8\r\n";
    $adminHeaders .= "From: POE International <info@poeinternational.com>\r\n";

    mail($adminEmail, $adminSubject, $adminMessage, $adminHeaders);

    $userSubject = "Thank You for Subscribing to POE International";
    $userMessage = "
    <html>
    <head>
      <title>Thank You for Subscribing</title>
      <style>
        body { font-family: Arial, sans-serif; background-color: #f8f8f8; color: #333; padding: 20px; }
        .container { background-color: #fff; border: 1px solid #ddd; padding: 30px; max-width: 600px; margin: auto; border-radius: 10px; }
        h2 { color: #4e1f5a; }
        p { font-size: 16px; line-height: 1.5; }
        ul { padding-left: 20px; }
        .signature { color: #4e1f5a; font-weight: bold; }
        .footer { font-size: 12px; color: #777; margin-top: 20px; }
      </style>
    </head>
    <body>
      <div class='container'>
        <h2>Welcome to POE International!</h2>
        <p>Hi <strong>$email</strong>,</p>
        <p>Thank you for subscribing. We’re excited to have you on board!</p>
        <p>Here’s what we offer:</p>
        <ul>
          <li><strong>Branding and Digital Communication</strong></li>
          <li><strong>Event Branding and Management</strong></li>
          <li><strong>Multimedia Production</strong></li>
          <li><strong>Digital Marketing and SEO</strong></li>
          <li><strong>Print and Environmental Branding</strong></li>
          <li><strong>Application Development</strong></li>
          <li><strong>Professional Training and Brand Auditing</strong></li>
        </ul>
        <p class='signature'>— The POE International Team</p>
        <div class='footer'>
          <p>Need help? <a href='mailto:info@poeinternational.com'>Contact us</a>.</p>
          <p>&copy; 2025 POE International</p>
        </div>
      </div>
    </body>
    </html>
    ";
    $userHeaders = "MIME-Version: 1.0\r\n";
    $userHeaders .= "Content-type: text/html; charset=UTF-8\r\n";
    $userHeaders .= "From: POE International <info@poeinternational.com>\r\n";

    if (mail($email, $userSubject, $userMessage, $userHeaders)) {
        echo "Thank you for subscribing! Please check your email.";
    } else {
        echo "Subscription successful, but failed to send welcome email.";
    }
} else {
    echo "Invalid request.";
}
?>

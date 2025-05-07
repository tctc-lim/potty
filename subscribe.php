<?php
// Allow from any origin
header("Access-Control-Allow-Origin: *"); // Replace * with specific origin if needed for security
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['email'])) {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address.";
        exit;
    }

    $to = $email;
    $subject = "Thank You for Subscribing to POE International";
    $message = "
    <html>
    <head>
      <title>Thank You for Subscribing</title>
      <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            color: #333;
            padding: 20px;
            margin: 0;
        }
        .container {
            background-color: #ffffff;
            border: 1px solid #ddd;
            padding: 30px;
            max-width: 600px;
            margin: 20px auto;
            border-radius: 10px;
        }
        h2 {
            color: #4e1f5a;
            font-size: 24px;
        }
        p {
            color: #333;
            font-size: 16px;
            line-height: 1.5;
        }
        ul {
            color: #333;
            font-size: 16px;
            margin: 10px 0;
            padding-left: 20px;
        }
        li {
            margin-bottom: 10px;
        }
        .signature {
            color: #4e1f5a;
            font-weight: bold;
        }
        .footer {
            color: #777;
            font-size: 12px;
            margin-top: 20px;
        }
        .footer a {
            color: #4e1f5a;
            text-decoration: none;
        }
      </style>
    </head>
    <body>
      <div class='container'>
        <h2>Welcome to POE International!</h2>
        <p>Thank you for subscribing to our newsletter. We are excited to have you as part of our community!</p>

        <p>At POE International, we offer a wide range of services to help brands grow and succeed. Here’s a look at what we specialize in:</p>
        
        <ul>
          <li><strong>Branding and Digital Communication</strong>: From corporate branding to employee branding, we help you develop a strong digital presence.</li>
          <li><strong>Event Branding and Management</strong>: We take care of everything from strategy, logistics, to post-event evaluation to make your event stand out.</li>
          <li><strong>Multimedia Production</strong>: High-quality video production, live streaming, and audience engagement to ensure your content resonates.</li>
          <li><strong>Digital Marketing and SEO</strong>: Boost your online presence with expert SEO strategies, PPC campaigns, and social media marketing.</li>
          <li><strong>Print and Environmental Branding</strong>: We design impactful printed materials and branded spaces that reflect your brand’s essence.</li>
          <li><strong>Application Development</strong>: From website design to custom applications, we build platforms that meet your business needs.</li>
          <li><strong>Professional Training and Brand Auditing</strong>: Develop your team's brand skills and audit your brand’s identity to ensure alignment with your goals.</li>
        </ul>

        <p>You’ll be the first to know about our latest updates, services, and insights, so stay tuned!</p>

        <p class='signature'>— The POE International Team</p>

        <div class='footer'>
          <p>If you have any questions or need assistance, feel free to <a href='mailto:support@poeinternational.com'>contact us</a>.</p>
          <p>POE International &copy; 2025</p>
        </div>
      </div>
    </body>
    </html>
    ";

    // Email headers
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
    $headers .= "From: POE International info@poeinternational.com>" . "\r\n";

    if (mail($to, $subject, $message, $headers)) {
        echo "Thank you for subscribing! Please check your email.";
    } else {
        echo "Failed to send email. Please try again later.";
    }
} else {
    echo "Invalid request.";
}
?>

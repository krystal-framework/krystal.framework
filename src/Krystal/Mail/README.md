Mailer
=====

The `Mailer` class provides a convenient wrapper around the **PHPMailer** library to send emails from within the Krystal Framework.  

It supports both standard mail and SMTP transports, with optional file attachments.

Key features:

-   Built on top of PHPMailer
-   Supports SMTP with authentication and encryption
-   Handles multiple recipients
-   Allows attachments (via file paths or `FileEntityInterface` instances)
-   Supports HTML content

## Configuration (SMTP)

    <?php
    
    use Krystal\Mail\Mailer;
    use PHPMailer\PHPMailer\PHPMailer;
    
    $config = [
        'from' => 'noreply@example.com',
        'smtp' => [
            'enabled'  => true,
            'host'     => 'smtp.example.com',
            'username' => 'your_username',
            'password' => 'your_password',
            'protocol' => PHPMailer::ENCRYPTION_STARTTLS, // or PHPMailer::ENCRYPTION_SMTPS
            'port'     => 587
        ]
    ];

## Configuration (Without SMTP)

    <?php
    
    use Krystal\Mail\Mailer;

    $config = [
        'from' => 'noreply@example.com',
        'smtp' => [
            'enabled' => false
        ]
    ];

## Basic usage

    $mailer = new Mailer($config);
    
    $to = 'user@example.com';
    $subject = 'Welcome to Krystal Framework!';
    $body = 'Your account has been created successfully';
    
    if ($mailer->send($to, $subject, $body)) {
        echo 'Email sent successfully!';
    } else {
        echo 'Failed to send email.';
    }


## Sending to multiple recipients

    $recipients = [
    	'user1@example.com',
    	'user2@example.com'
    ];
    
    $mailer->send($recipients, 'Weekly Newsletter', 'Here’s our weekly update!');


## Sending with attachments

    $attachments = [
        '/path/to/report.pdf',
        '/path/to/image.png'
    ];

    $mailer->send('team@example.com', 'Monthly Report', '<p>See attached report.</p>', $attachments);

You can also retrieve uploaded file entities directly from the request object in your controller: `$attachments = $this->request->getFiles()`
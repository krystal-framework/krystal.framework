Mailer
=====

The `Mailer` class provides a convenient wrapper around the **PHPMailer** library to send emails from within the Krystal Framework.  

It supports both standard mail and SMTP transports, HTML content with automatic plain-text generation, CC/BCC, reply-to addresses, and file attachments.

Key features:

-   Built on top of PHPMailer
-   Supports SMTP with authentication, encryption, and custom options
-   Automatic plain-text alternative generation from HTML body
-   Handles multiple recipients (with optional names), CC, BCC, and Reply-To
-   Allows attachments (via file paths or `FileEntityInterface` instances)
-   Supports HTML content

## Configuration Parameters

### Mandatory Parameters
-   `from` (string): The sender's email address.

### Optional Parameters
-   `from_name` (string): The display name of the sender.
-   `reply_to` (string|array): Email address or associative array of addresses for replies.
-   `cc` (string|array): Carbon copy recipient(s).
-   `bcc` (string|array): Blind carbon copy recipient(s).
-   `smtp` (array): SMTP transport settings (if disabled or omitted, PHP's native `mail()` function is used).
    -   `enabled` (bool): Set to `true` to use SMTP.
    -   `host` (string): SMTP server host.
    -   `username` (string): SMTP authentication username.
    -   `password` (string): SMTP authentication password.
    -   `protocol` (string): Encryption protocol (e.g., `PHPMailer::ENCRYPTION_STARTTLS` or `PHPMailer::ENCRYPTION_SMTPS`).
    -   `port` (int): SMTP port (e.g., `587` or `465`).
    -   `options` (array): Stream context options (useful for bypassing self-signed SSL certificates in local/development environments).

---

## Configuration Example (SMTP)

    <?php
    
    use Krystal\Mail\Mailer;
    use PHPMailer\PHPMailer\PHPMailer;
    
    $config = [
        // Mandatory
        'from' => 'noreply@example.com',

        // Optional
        'from_name' => 'Krystal App',
        'reply_to' => [
            'support@example.com' => 'Support Team'
        ],
        'cc' => [
            'manager@example.com' => 'Manager'
        ],
        'bcc' => [
            'archive@example.com'
        ],

        // Optional SMTP Settings
        'smtp' => [
            'enabled'  => true,
            'host'     => 'smtp.example.com',
            'username' => 'your_username',
            'password' => 'your_password',
            'protocol' => PHPMailer::ENCRYPTION_STARTTLS,
            'port'     => 587,
            'options'  => [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ]
        ]
    ];

## Configuration Example (Without SMTP)

    <?php
    
    use Krystal\Mail\Mailer;

    $config = [
        // Mandatory
        'from' => 'noreply@example.com',

        // Optional
        'from_name' => 'Krystal App',

        // SMTP disabled (uses native mail() function)
        'smtp' => [
            'enabled' => false
        ]
    ];

## Basic usage

    $mailer = new Mailer($config);
    $to = 'user@example.com';
    $subject = 'Welcome to Krystal Framework!';
    $body = '<p>Your account has been created successfully</p>';
    
    if ($mailer->send($to, $subject, $body)) {
        echo 'Email sent successfully!';
    } else {
        echo 'Failed to send email.';
    }


## Sending to multiple recipients

You can pass an indexed list of email addresses, or an associative array mapping email addresses to recipient names:

    // Indexed array
    $recipients = [
        'user1@example.com',
        'user2@example.com'
    ];

    // Or associative array with names
    $recipientsWithNames = [
        'john@example.com' => 'John Doe',
        'jane@example.com' => 'Jane Doe'
    ];
    
    $mailer->send($recipientsWithNames, 'Weekly Newsletter', '<p>Here’s our weekly update!</p>');


## Sending with attachments

    $attachments = [
        '/path/to/report.pdf',
        '/path/to/image.png'
    ];

    $mailer->send('team@example.com', 'Monthly Report', '<p>See attached report.</p>', $attachments);

You can also retrieve uploaded file entities directly from the request object in your controller: `$attachments = $this->request->getFiles()`
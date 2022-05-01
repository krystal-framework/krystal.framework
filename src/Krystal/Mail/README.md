Mailer
=====

This component provides abstraction over [PHPMailer library](https://github.com/PHPMailer/PHPMailer).

# Usage

As a rule of thumb, sending mails should be down from within controllers.

    <?php
    
    use Krystal\Mail\Mailer;
    
    $mailer = new Mailer([
        'from' => 'no-reply@domain.ltd',
        'smtp' => [
            'enabled' => true, // Whether SMTP is enabled
            'host' => 'smtp.host.com',
            'username' => 'user',
            'password' => 'password',
            'encryption' => 'tls', // tls or ssl
            'port' => 587
        ]
    ]);
    
    $to = 'friend@domain.ltd';
    $subject = 'Thank you for using our product';
    $body = 'Body goes here';
    
    return $mailer->send($to, $subject, $body);

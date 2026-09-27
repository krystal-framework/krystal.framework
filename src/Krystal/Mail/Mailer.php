<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Mail;

use Krystal\Http\FileTransfer\FileEntityInterface;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

final class Mailer
{
    /**
     * Email configuration
     * 
     * @var array
     */
    private $configuration = [];

    /**
     * State initialization
     * 
     * @param array $configuration
     * @return void
     */
    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * Set configuration at runtime
     * 
     * @param array $configuration
     * @return \Krystal\Mail\Mailer
     */
    public function setConfiguration(array $configuration)
    {
        $this->configuration = $configuration;
        return $this;
    }

    /**
     * Sends an email message using the configured transport (SMTP or PHP mail).
     * 
     * This method builds and dispatches an email message with support for:
     * 
     * - Multiple recipients (indexed lists or ['email' => 'Name'] pairs)
     * - CC, BCC, and Reply-To addresses from configuration
     * - File attachments (paths or FileEntityInterface instances)
     * - HTML body with automatic plain-text alternative
     *
     * @param string|array $to One or more recipient email addresses.
     * @param string $subject  The subject line of the email message.
     * @param string $body     The HTML body content of the email message.
     * @param array $files     Optional attachments.
     *
     * @throws \PHPMailer\PHPMailer\Exception If the mailer encounters an error.
     *
     * @return boolean Returns TRUE on successful send, or FALSE on failure.
     */
    public function send($to, $subject, $body, array $files = [])
    {
        $mail = new PHPMailer(true);
        $mail->Encoding = 'base64';
        $mail->CharSet = 'UTF-8';

        // SMTP transport configuration
        if (isset($this->configuration['smtp']['enabled']) && $this->configuration['smtp']['enabled'] === true) {
            $mail->isSMTP();
            $mail->Host = $this->configuration['smtp']['host'] ?? 'localhost';

            if (isset($this->configuration['smtp']['username'], $this->configuration['smtp']['password'])) {
                $mail->SMTPAuth = true;
                $mail->Username = $this->configuration['smtp']['username'];
                $mail->Password = $this->configuration['smtp']['password'];
            }

            if (isset($this->configuration['smtp']['protocol'])) {
                $mail->SMTPSecure = $this->configuration['smtp']['protocol'];
            }

            if (isset($this->configuration['smtp']['port'])) {
                $mail->Port = $this->configuration['smtp']['port'];
            }

            if (isset($this->configuration['smtp']['options'])) {
                $mail->SMTPOptions = $this->configuration['smtp']['options'];
            }
        }

        // Sender details
        $fromEmail = $this->configuration['from'] ?? '';
        $fromName = $this->configuration['from_name'] ?? '';
        $mail->setFrom($fromEmail, $fromName);

        // Reply-To configuration
        if (isset($this->configuration['reply_to'])) {
            if (is_array($this->configuration['reply_to'])) {
                foreach ($this->configuration['reply_to'] as $email => $name) {
                    if (is_string($email)) {
                        $mail->addReplyTo($email, $name);
                    } else {
                        $mail->addReplyTo($name);
                    }
                }
            } else {
                $mail->addReplyTo($this->configuration['reply_to']);
            }
        }

        // CC configuration
        if (isset($this->configuration['cc'])) {
            $ccList = (array) $this->configuration['cc'];
            foreach ($ccList as $key => $value) {
                if (is_string($key)) {
                    $mail->addCC($key, $value);
                } else {
                    $mail->addCC($value);
                }
            }
        }

        // BCC configuration
        if (isset($this->configuration['bcc'])) {
            $bccList = (array) $this->configuration['bcc'];
            foreach ($bccList as $key => $value) {
                if (is_string($key)) {
                    $mail->addBCC($key, $value);
                } else {
                    $mail->addBCC($value);
                }
            }
        }

        // File attachments
        if (!empty($files)) {
            foreach ($files as $file) {
                if ($file instanceof FileEntityInterface) {
                    $mail->addAttachment($file->getTmpName(), $file->getName());
                } elseif (is_string($file) && file_exists($file)) {
                    $mail->addAttachment($file);
                }
            }
        }

        $mail->isHTML(true);

        // Recipients
        if (is_array($to)) {
            foreach ($to as $key => $value) {
                if (is_string($key)) {
                    $mail->addAddress($key, $value);
                } else {
                    $mail->addAddress($value);
                }
            }
        } else {
            $mail->addAddress($to);
        }

        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AltBody = strip_tags($body);

        return $mail->send();
    }
}

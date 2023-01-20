<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Mail;

use Closure;
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
     * Sends e-mail
     * 
     * @param string|array Email of receiver
     * @param string $subject
     * @param string $body
     * @param array $files
     * @return boolean
     */
    public function send($to, $subject, $body, array $files = [])
    {
        $mail = new PHPMailer(true);
        $mail->Encoding = 'base64';
        $mail->CharSet = 'UTF-8';

        // If we have SMTP transport turned on, then we'd use appropriate transport
        if (isset($this->configuration['smtp']) && isset($this->configuration['smtp']['enabled']) && $this->configuration['smtp']['enabled'] == true) {
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER; // Enable verbose debug output
            $mail->isSMTP(); //Send using SMTP

            $mail->Host = $this->configuration['smtp']['host']; //Set the SMTP server to send through

            // Enable SMTP authentication, if required
            if (isset($this->configuration['smtp']['username'], $this->configuration['smtp']['password'])) {
                $mail->SMTPAuth = true;
                $mail->Username = $this->configuration['smtp']['username']; // SMTP username
                $mail->Password = $this->configuration['smtp']['password']; // SMTP password
            }

            $mail->SMTPSecure = $this->configuration['smtp']['protocol']; // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port = $this->configuration['smtp']['port']; // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
        }

        $mail->setFrom($this->configuration['from'], $this->configuration['from']);

        // if files provided, then attach them
        if (!empty($files)) {
            foreach ($files as $name => $file) {
                if ($file instanceof FileEntityInterface) {
                    $mail->addAttachment($file->getTmpName(), $file->getName());
                } else {
                    $mail->addAttachment($file);
                }
            }
        }

        $mail->isHTML(true);

        if (is_array($to)) {
            foreach ($to as $receiver) {
                $mail->addAddress($receiver);
            }
        } else {
            $mail->addAddress($to);
        }

        $mail->Subject = $subject;
        $mail->Body = $body;

        return $mail->send();
    }
}

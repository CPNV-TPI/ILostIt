<?php

namespace ILostIt\Model;

use League\OAuth2\Client\Provider\Google;
use PHPMailer\PHPMailer\OAuth;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Emails
{
    private string $host = "";
    private string $port = "";
    private string $username = "";
    private string $password = "";

    public function __construct()
    {
        $env = parse_ini_file(".env.local");
        $this->host = $env['MAIL_SMTP_HOST'];
        $this->port = $env['MAIL_SMTP_PORT'];
        $this->username = $env['MAIL_USER'];
        $this->password = $env['MAIL_PASSWORD'];
    }

    /**
     * This method is designed to send an email.
     * @param string $email : Recipient's email
     * @param string $message
     * @param string $subject
     * @return bool
     */
    public function send(string $email, string $message, string $subject = "I Lost It"): bool
    {
        try {
            $mail = $this->mailInstantiation();

            $mail->addAddress($email);
            $mail->Subject = $subject;
            $mail->Body = $message;

            $mail->send();

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * This method is designed to instantiate the mail.
     * @return PHPMailer
     * @throws \PHPMailer\PHPMailer\Exception
     */
    private function mailInstantiation(): PHPMailer
    {
        $mail = new PHPMailer(true);

        $mail->SMTPDebug = SMTP::DEBUG_OFF;
        $mail->isSMTP();
        $mail->Host = $this->host;
        $mail->SMTPAuth = true;
        $mail->Username = $this->username;
        $mail->Password = $this->password;
        $mail->Port = $this->port;
        $mail->setFrom($this->username, "I Lost It");
        $mail->isHTML(true);
        $mail->CharSet = "UTF-8";

        return $mail;
    }
}

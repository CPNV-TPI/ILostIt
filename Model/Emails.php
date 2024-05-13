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
    private string $clientId = "";
    private string $clientSecret = "";
    private string $refreshToken = "";

    public function __construct()
    {
        $env = parse_ini_file(".env.local");
        $this->host = $env['MAIL_SMTP_HOST'];
        $this->port = $env['MAIL_SMTP_PORT'];
        $this->username = $env['MAIL_USER'];
        $this->password = $env['MAIL_PASSWORD'];
        $this->clientId = $env['MAIL_GOOGLE_CLIENT_ID'];
        $this->clientSecret = $env['MAIL_GOOGLE_CLIENT_SECRET'];
        $this->refreshToken = $env['MAIL_GOOGLE_REFRESH_TOKEN'];
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
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = $this->port;
        $mail->setFrom($this->username, "I Lost It");
        $mail->isHTML(true);
        $mail->CharSet = "UTF-8";

        /* Google's SMTP */

        /* Set Auth type */
        $mail->AuthType = 'XOAUTH2';

        /* Create new provider */
        $provider = new Google([
            'clientId' => $this->clientId,
            'clientSecret' => $this->clientSecret,
        ]);

        /* Pass the provider */
        $mail->setOAuth(
            new OAuth(
                [
                    'provider' => $provider,
                    'clientId' => $this->clientId,
                    'clientSecret' => $this->clientSecret,
                    'refreshToken' => $this->refreshToken,
                    'userName' => $this->username,
                ]
            )
        );

        return $mail;
    }
}

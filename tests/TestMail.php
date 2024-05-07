<?php


use ILostIt\Model\Mail;
use PHPUnit\Framework\TestCase;

class TestMail extends TestCase
{
    public function testCanSendEmail(): void
    {
        $email = "diogo.dasilva2@eduvaud.ch";
        $subject = "TestMail Mail Send";
        $message = "This a test mail sent by a unit test.";

        $mailer = new Mail();

        $response = $mailer->send($email, $message, $subject);

        $this->assertTrue($response);
    }
}

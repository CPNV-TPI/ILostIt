<?php

namespace ILostIt\tests;

use ILostIt\Model\Emails;
use PHPUnit\Framework\TestCase;

class TestEmails extends TestCase
{
    public function testCanSendEmail(): void
    {
        $email = "diogo.dasilva2@eduvaud.ch";
        $subject = "TestEmails Emails Send";
        $message = "This a test mail sent by a unit test.";

        $mailer = new Emails();

        $response = $mailer->send($email, $message, $subject);

        $this->assertTrue($response);
    }
}

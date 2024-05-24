<?php

namespace ILostIt\tests;

use ILostIt\Model\Members;
use ILostIt\Model\Database;
use PHPUnit\Framework\TestCase;

class TestMembers extends TestCase
{
    protected array $values;

    public function setUp(): void
    {
        $this->values = [
            "firstName" => "John",
            "lastName" => "Doe",
            "email" => "john@doe.com",
            "password" => "password",
        ];
    }

    public function testCanGetMembers()
    {
        $members = new Members();
        $insertMember = $members->registerNewMember($this->values);
        $results = $members->getMembers();

        $this->assertIsArray($results);
        $this->assertGreaterThanOrEqual(1, count($results));
    }

    public function testCanRegisterNewMember()
    {
        $values = [
            "firstName" => "John",
            "lastName" => "Blower",
            "email" => "john@blower.com",
            "password" => "password",
        ];

        $members = new Members();

        $expectedMembers = count($members->getMembers()) + 1;
        $insertMember = $members->registerNewMember($values);

        $this->assertTrue($insertMember);
        $this->assertCount($expectedMembers, $members->getMembers());
    }

    public function testCannotRegisterNewMemberWithMissingValues()
    {
        $values = [
            "firstName" => "John",
            "lastName" => "Blower",
            "password" => "password",
        ];

        $members = new Members();

        $insertMember = $members->registerNewMember($values);

        $this->assertIsString($insertMember);
    }

    public function testCannotLoginWithNonExistingUser()
    {
        $members = new Members();

        $email = "joe@blow.com";
        $password = "password";

        $loginResult = $members->checkLogin($email, $password);

        $this->assertIsString($loginResult);
    }

    public function testCannotLoginWithValidCredentialsAndAccountNotVerified()
    {
        $members = new Members();

        $insertMember = $members->registerNewMember($this->values);
        $loginResult = $members->checkLogin($this->values['email'], $this->values['password']);

        $this->assertIsString($loginResult);
    }

    public function testCanLoginWithValidCredentialsAndAccountVerified()
    {
        $members = new Members();

        $insertMember = $members->registerNewMember($this->values);
        $membersList = $members->getMembers();

        foreach ($membersList as $member) {
            if ($member["email"] == $this->values["email"]) {
                $verified = $members->verifyUser($member["id"]);
            }
        }

        $loginResult = $members->checkLogin($this->values['email'], $this->values['password']);

        $this->assertIsArray($loginResult);
    }

    public function testCannotLoginWithInvalidEmail()
    {
        $members = new Members();

        $badEmail = "john@blow.com";

        $insertMember = $members->registerNewMember($this->values);
        $loginResult = $members->checkLogin($badEmail, $this->values['password']);

        $this->assertIsString($loginResult);
    }

    public function testCannotLoginWithInvalidPassword()
    {
        $members = new Members();

        $password = "ThisIsNotTheRealPassword";

        $insertMember = $members->registerNewMember($this->values);
        $loginResult = $members->checkLogin($this->values['email'], $password);

        $this->assertIsString($loginResult);
    }

    public function testCanVerifyUser()
    {
        $members = new Members();
        $insertMember = $members->registerNewMember($this->values);
        $membersList = $members->getMembers();

        foreach ($membersList as $member) {
            if ($member["email"] == $this->values["email"]) {
                $verified = $members->verifyUser($member["id"]);
            }
        }

        $membersList = $members->getMembers();
        foreach ($membersList as $member) {
            if ($member["email"] == $this->values["email"]) {
                $this->assertEquals(1, $member['isVerified']);
            }
        }
    }
}

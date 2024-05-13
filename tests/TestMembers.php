<?php

use ILostIt\Model\Members;
use ILostIt\Model\Database;
use PHPUnit\Framework\TestCase;

class TestMembers extends TestCase
{
    public function testCanGetMembers()
    {
        $values = [
            "lastname" => "Doe",
            "firstname" => "John",
            "email" => "john@doe.com",
            "password" => "password"
        ];

        $members = new Members();
        $insertMember = $members->registerNewMember($values);
        $results = $members->getMembers();

        $this->assertIsArray($results);
        $this->assertGreaterThanOrEqual(1, count($results));
    }

    public function testCanRegisterNewMember()
    {
        $members = new Members();

        $values = [
            "lastname" => "Puolos",
            "firstname" => "Katrin",
            "email" => "katrin@puolos.com",
            "password" => "password"
        ];

        $expectedMembers = count($members->getMembers()) + 1;
        $insertMember = $members->registerNewMember($values);

        $this->assertTrue($insertMember);
        $this->assertCount($expectedMembers, $members->getMembers());
    }

    public function testCanVerifyUser()
    {
        $values = [
            "lastName" => "Doe",
            "firstName" => "John",
            "email" => "john@doe.com",
            "password" => "password"
        ];

        $members = new Members();
        $insertMember = $members->registerNewMember($values);
        $membersList = $members->getMembers();

        foreach ($membersList as $member) {
            if ($member["email"] == $values["email"]) {
                $verified = $members->verifyUser($member["id"]);
            }
        }

        $membersList = $members->getMembers();
        foreach ($membersList as $member) {
            if ($member["email"] == $values["email"]) {
                $this->assertEquals(1, $member['isVerified']);
            }
        }
    }
}

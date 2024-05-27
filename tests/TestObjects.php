<?php

namespace ILostIt\tests;

use ILostIt\Model\Members;
use PHPUnit\Framework\TestCase;
use ILostIt\Model\Objects;

class TestObjects extends TestCase
{
    private array $object = [
        "title" => "Test Object",
        "description" => "Test Object",
        "type" => "Perdu",
        "classroom" => "SC-C213",
        "memberOwner_id" => null,
    ];

    private array $images = [
        [
            "image" => "/test1.jpg",
            "type" => "png"
        ],
        [
            "image" => "/test2.jpg",
            "type" => "png"
        ],
    ];

    public function testCanGetObjects()
    {
        $objects = new Objects();
        $getObjects = $objects->getObjects();

        $this->assertIsArray($getObjects);
    }

    public function testCanPublishObject()
    {
        $objects = new Objects();

        $previousObjects = $objects->getObjects();
        $nbPreviousObjects = count($previousObjects) + 1;

        $members = new Members();
        $getFirstMember = $members->getMembers();
        $this->object['memberOwner_id'] = $getFirstMember[0]['id'];

        $result = $objects->publishObject($this->object, $this->images);

        $afterObjects = $objects->getObjects();
        $nbAfterObjects = count($afterObjects);

        $this->assertTrue($result);
        $this->AssertEquals($nbPreviousObjects, $nbAfterObjects);
    }

    public function testCanAcceptObject()
    {
        $objects = new Objects();
        $filters = [];

        foreach ($this->object as $key => $value) {
            if ($key == "memberOwner_id") {
                break;
            }

            $filter = [$key, "=", $value];
            $filters[] = $filter;
        }

        $getObjects = $objects->getObjects($filters);
        $idObject = $getObjects[0]['id'];

        $status = $objects->validateObject($idObject, true);

        $getObjects = $objects->getObjects($filters);
        $objectStatus = $getObjects[0]['status'];

        $this->assertTrue($status);
        $this->assertEquals(1, $objectStatus);
    }

    public function testCanRefuseObject()
    {
        $objects = new Objects();
        $filters = [];

        foreach ($this->object as $key => $value) {
            if ($key == "memberOwner_id") {
                break;
            }

            $filter = [$key, "=", $value];
            $filters[] = $filter;
        }

        $getObjects = $objects->getObjects($filters);
        $idObject = $getObjects[0]['id'];

        $reason = "Testing refuse";
        $status = $objects->validateObject($idObject, false, $reason);

        $getObjects = $objects->getObjects($filters);
        $objectStatus = $getObjects[0]['status'];

        $this->assertTrue($status);
        $this->assertEquals(5, $objectStatus);
    }

    public function testCanDeleteObject()
    {
        $objects = new Objects();
        $filters = [];

        foreach ($this->object as $key => $value) {
            if ($key == "memberOwner_id") {
                break;
            }

            $filter = [$key, "=", $value];
            $filters[] = $filter;
        }

        $getObjects = $objects->getObjects($filters);
        $idObject = $getObjects[0]['id'];

        $status = $objects->cancelObject($idObject);

        $getObjects = $objects->getObjects($filters);
        $objectStatus = $getObjects[0]['status'];

        $this->assertTrue($status);
        $this->assertEquals(4, $objectStatus);
    }

    public function testCanContactOwner()
    {
        $filters = [];

        foreach ($this->object as $key => $value) {
            if ($key == "memberOwner_id") {
                break;
            }

            $filter = [$key, "=", $value];
            $filters[] = $filter;
        }

        $objects = new Objects();
        $object = $objects->getObjects($filters)[0];

        $idObject = $object['id'];
        $finderEmail = "john@doe.com";

        $status = $objects->contactOwner($idObject, $finderEmail);

        $this->assertTrue($status);
    }

    public function testCanSolveObjectAlone()
    {
        $filters = [];

        foreach ($this->object as $key => $value) {
            if ($key == "memberOwner_id") {
                break;
            }

            $filter = [$key, "=", $value];
            $filters[] = $filter;
        }

        $objects = new Objects();
        $object = $objects->getObjects($filters)[0];

        $members = new Members();
        $member = $members->getMembers()[0];

        $idObject = $object['id'];

        $status = $objects->solveObject($idObject, $member['id']);

        $objects = $objects->getObjects($filters);

        $this->assertTrue($status);
        $this->assertEquals(3, $objects[0]['status']);
        $this->assertEquals($objects[0]['memberOwner_id'], $objects[0]['memberFinder_id']);
    }

    public function testCanSolveObjectOtherUser()
    {
        $filters = [];

        foreach ($this->object as $key => $value) {
            if ($key == "memberOwner_id") {
                break;
            }

            $filter = [$key, "=", $value];
            $filters[] = $filter;
        }

        $objects = new Objects();
        $object = $objects->getObjects($filters)[0];

        $filterMembers = [["id", "!=", $object['memberOwner_id']]];
        $members = new Members();
        $member = $members->getMembers($filterMembers)[0];

        $idObject = $object['id'];

        $status = $objects->solveObject($idObject, $member['id'], $member['email']);

        $objects = $objects->getObjects($filters);

        $this->assertTrue($status);
        $this->assertEquals(3, $objects[0]['status']);
        $this->assertNotEquals($objects[0]['memberOwner_id'], $objects[0]['memberFinder_id']);
    }
}

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

        $status = $objects->deleteObject($idObject);

        $getObjects = $objects->getObjects($filters);
        $objectStatus = $getObjects[0]['status'];

        $this->assertTrue($status);
        $this->assertEquals(4, $objectStatus);
    }
}

<?php

use PHPUnit\Framework\TestCase;
use ILostIt\Model\Objects;

class TestObjects extends TestCase
{
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
        $nbPreviousObjects = count($previousObjects);

        $values = [
            "title" => "Test Object",
            "description" => "Test Object",
            "type" => "Perdu",
            "classroom" => "SC-C213",
            "memberOwner_id" => 1,
        ];
        $images = [
            [
                "image" => "/test1.jpg",
                "type" => "png"
            ],
            [
                "image" => "/test2.jpg",
                "type" => "png"
            ],
        ];

        $result = $objects->publishObject($values, $images);

        $afterObjects = $objects->getObjects();
        $nbAfterObjects = count($afterObjects);

        $this->assertTrue($result);
        $this->AssertEquals($nbPreviousObjects + 1, $nbAfterObjects);
    }
}

<?php

namespace ILostIt\Model;


use Exception;

class Objects
{
    private string $objectsTable = "objects";
    private string $imagesTable = "images";

    /**
     * This method is designed to get all posts from database.
     *
     * @param  array $filters
     * @return array | int
     */
    public function getObjects(array $filters = [], int $page = 1, int $count = 0, array $orderBy = []): array | int
    {
        $columnsObject = array("id", "title", "description", "classroom", "brand", "color", "value");
        $columnsImages = array("name");

        $db = new Database();
        $offset = ($page - 1) * $count;
        $objects = $db->select($this->objectsTable, $columnsObject, $filters, $count, $offset, $orderBy);

        if (count($objects) > 0) {
            foreach ($objects as $key => $object) {
                $filterImage = [["object_id", "=", $object["id"]]];
                $images = $db->select($this->imagesTable, $columnsImages, $filterImage);

                if (count($images) > 0) {
                    $objects[$key]["images"] = [];

                    foreach ($images as $image) {
                        $objects[$key]["images"][] = $image["name"];
                    }
                }
            }
        }

        // ends db connection for security
        $db = false;

        return $objects;
    }

    /**
     * This method is designed to insert a post in the database.
     *
     * @param array $values
     * @param array $images
     * @return bool
     */
    public function publishObject(array $values, array $images): bool
    {
        $db = new Database();
        $status = $db->insert($this->objectsTable, $values);

        if (!$status) {
            return false;
        }

        $filters = [];
        foreach ($values as $key => $value) {
            $filters[] = [$key, "=", $value];
        }

        $publishedObject = $this->getObjects($filters);
        if (count($publishedObject) < 1) {
            return false;
        }

        $publishedObject = $publishedObject[0];

        $imageDirectory = dirname(__DIR__, 1) . "/src/img/objects/" . $publishedObject["id"] . "/";
        foreach ($images as $key => $image) {
            $imageName = $publishedObject["id"] . "-img" . ($key + 1) . "." . $image["type"];

            $imageInDatabase = [
                "name" => $imageName,
                "object_id" => $publishedObject["id"],
            ];

            $status = $db->insert($this->imagesTable, $imageInDatabase);

            if (!is_dir($imageDirectory)) {
                mkdir($imageDirectory, 0777, true);
            }

            move_uploaded_file(
                $image["image"],
                $imageDirectory . $imageName
            );
        }

        // ends db connection for security
        $db = false;

        if (!$status) {
            return $status;
        }

        return true;
    }

    /**
     * This method is designed to update a post.
     *
     * @param  string $postId
     * @param  array $values
     * @return bool
     */
    public function updateObject(string $postId, array $values): bool
    {
        $conditions = array(["id", "=", $postId]);

        $db = new Database();
        $status = $db->update($this->objectsTable, $values, $conditions);

        // ends db connection for security
        $db = false;

        if (!$status) {
            return false;
        }

        return true;
    }


    /**
     * This method is designed to deactivate a post.
     *
     * @param  string $postId
     * @return bool
     */
    public function deleteObject(string $postId): bool
    {
        $conditions = array(["id", "=", $postId]);

        $db = new Database();

        $status = $db->delete($this->objectsTable, $conditions);

        // ends db connection for security
        $db = false;

        if (!$status) {
            return false;
        }

        return true;
    }
}

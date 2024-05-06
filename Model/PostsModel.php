<?php

namespace ILostIt\Model;

class PostsModel
{
    private string $dbTable = "Posts";

    /**
     * This method is designed to get all posts from database.
     *
     * @param  array $filters
     * @return array
     */
    public function getPosts(array $filters): array
    {
        $columns = array("id", "title", "description", "classroomNumber", "images");

        $db = new Database();
        $posts = $db->select($this->dbTable, $columns, $filters);

        // ends db connection for security
        $db = false;

        return $posts;
    }

    /**
     * This method is designed to insert a post in the database.
     *
     * @param  array $values
     * @return bool
     */
    public function publishPost(array $values): bool
    {
        $db = new Database();
        $status = $db->insert($this->dbTable, $values);

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
    public function updatePost(string $postId, array $values): bool
    {
        $conditions = array(["id", "=", $postId]);

        $db = new Database();
        $status = $db->update($this->dbTable, $values, $conditions);

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
    public function deletePost(string $postId): bool
    {
        $conditions = array(["id", "=", $postId]);

        $db = new Database();

        $status = $db->delete($this->dbTable, $conditions);

        // ends db connection for security
        $db = false;

        if (!$status) {
            return false;
        }

        return true;
    }
}

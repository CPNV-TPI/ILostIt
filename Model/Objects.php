<?php

namespace ILostIt\Model;

class Objects
{
    private string $objectsTable = "objects";
    private string $imagesTable = "images";

    /**
     * This method is designed to get all objects from database.
     *
     * @param  array $filters
     * @param int $page
     * @param int $count
     * @param array $orderBy
     * @return array
     */
    public function getObjects(
        array $filters = [],
        int $page = 1,
        int $count = 0,
        array $orderBy = []
    ): array {
        $columnsObject = array(
            "id",
            "title",
            "description",
            "classroom",
            "brand",
            "color",
            "value",
            "status",
            "memberOwner_id",
            "memberFinder_id"
        );
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
     * This method is designed to insert an object in the database.
     *
     * @param array $values
     * @param array $images
     * @return bool
     */
    public function publishObject(
        array $values,
        array $images
    ): bool {
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

            if (!$status) {
                return $status;
            }

            if (!is_dir($imageDirectory)) {
                mkdir($imageDirectory, 0755, true);
            }

            move_uploaded_file(
                $image["image"],
                $imageDirectory . $imageName
            );
        }

        $membersModel = new Members();
        $filtersMembers = [["id", "=", $publishedObject["memberOwner_id"]]];
        $members = $membersModel->getMembers($filtersMembers);

        if (count($members) == 0) {
            return false;
        }

        // ends db connection for security
        $db = false;

        $userEmail = $members[0]["email"];
        $subject = "Votre nouvelle publication !";
        $message = "Bonjour!<br><br>";
        $message .= "Tout d'abord, nous apprécions la confiance que vous fournissez en notre plateforme.<br><br>";
        $message .= "Pour la maintenir en bonne forme et sécuritaire pour tous, ";
        $message .= "chaque publication est soumise à vérification par notre équipe de modération.<br>";
        $message .= "Votre nouvelle publication nous est bien parvenue et ça vérification est en cours. ";
        $message .= "Une fois celle-ci vérifiée, vous recevrez par email une confirmation.<br><br>";
        $message .= "Merci!<br><br>Meilleures salutations.<br>L'équipe I Lost It";

        $email = new Emails();

        return $email->send($userEmail, $message, $subject);
    }

    /**
     * This method is designed to update a post.
     *
     * @param  string $postId
     * @param  array $values
     * @return bool
     */
    public function updateObject(
        string $postId,
        array $values
    ): bool {
        $conditions = array(["id", "=", $postId]);

        $db = new Database();
        $status = $db->update($this->objectsTable, $values, $conditions);

        // ends db connection for security
        $db = false;

        return $status;
    }

    /**
     * This method is designed to deactivate a post.
     *
     * @param  string $postId
     * @return bool
     */
    public function cancelObject(
        string $postId
    ): bool {
        $values = ["status" => "4"];

        return $this->updateObject($postId, $values);
    }

    /**
     * This method is designed to validate an object
     *
     * @param string $postId
     * @param bool $accepted
     * @param string $reason
     * @return bool
     */
    public function validateObject(
        string $postId,
        bool $accepted = true,
        string $reason = ""
    ): bool {
        $values = [
            "status" => $accepted ? 1 : 5
        ];
        $status = $this->updateObject($postId, $values);

        if (!$status) {
            return false;
        }

        $filtersObjects = [["id", "=", $postId]];
        $object = $this->getObjects($filtersObjects);

        if (count($object) == 0) {
            return false;
        }

        $membersModel = new Members();
        $filtersMembers = [["id", "=", $object[0]["memberOwner_id"]]];
        $members = $membersModel->getMembers($filtersMembers);

        if (count($members) == 0) {
            return false;
        }

        $message = "Bonjour!<br><br>";
        $message .= "Votre publication '" . $object[0]["title"] . "' ";
        $message .= $accepted ? "a été acceptée.<br><br>" : "a été refusée.<br><br>";
        $message .= $accepted ?
            "Elle est maintenant dispnobile et visible par tous.<br><br>" :
            "La raison :<br>" . $reason . "<br><br>";
        $message .= "Meilleures salutations.<br><br>L'équipe I Lost It";
        $email = new Emails();

        return $email->send($members[0]['email'], $message);
    }

    /**
     * This method is designed to contact the owner of an object
     *
     * @param string $postId
     * @param string $finderEmail
     * @param string|null $message
     * @return bool
     */
    public function contactOwner(
        string $postId,
        string $finderEmail,
        string $message = null
    ): bool {
        $filers = [["id", "=", $postId]];
        $objects = $this->getObjects($filers);

        if (count($objects) == 0) {
            return false;
        }

        $object = $objects[0];
        $ownerId = $object["memberOwner_id"];

        $filters = [["id", "=", $ownerId]];
        $membersModel = new Members();
        $members = $membersModel->getMembers($filters);

        if (count($members) == 0) {
            return false;
        }

        $owner = $members[0];
        $ownerEmail = $owner["email"];

        $email = new Emails();

        $emailMessage = "Bonjour!<br><br>";
        $emailMessage .= "Quelqu'un a peut-être trouvé l'objet de votre publication : '" .
            $object["title"] .
            "' !<br><br>"
        ;
        $emailMessage .= "Contactez la personne par email -> " . $finderEmail . "<br><br>";

        $emailMessage .= $message != null ? "Voici ce qu'il vous a écrit :<br>" . $message : "";

        $emailMessage .= "<br><br>Espérons que ce soit la bonne personne !<br><br>";
        $emailMessage .= "Meilleures salutations.<br>";
        $emailMessage .= "L'équipe I Lost It";

        $status = $email->send($ownerEmail, $emailMessage);

        if (!$status) {
            return false;
        }

        return true;
    }

    /**
     * This method is designed to solve an object
     *
     * @param string $postId
     * @param int|null $userId
     * @param string|null $finderEmail
     * @param bool $confirmSolve
     * @return bool
     */
    public function solveObject(
        string $postId,
        int $userId = null,
        string $finderEmail = null,
        bool $confirmSolve = false
    ): bool {
        $finderId = $finderEmail == null ? $userId : null;
        $values = [];

        if ($finderEmail != null) {
            $filers = [
                ["email", "=", $finderEmail]
            ];

            $membersModel = new Members();
            $members = $membersModel->getMembers($filers);

            if (empty($members)) {
                return false;
            }

            $member = $members[0];
            $finderId = $member["id"];

            $env = parse_ini_file('.env.local');
            $hostname = $env["HOSTNAME"];

            $message = "Bonjour!<br><br>";
            $message .= "Vous avez aidé un utilisateur à retrouver son objet, merci à vous !<br><br>";
            $message .= "Une dernière petite étape et ce sera tout bon :<br>";
            $message .= "-> Veuillez appuyer sur ce lien pour confirmer votre aide : ";
            $message .= "https://" . $hostname . "/objects/" . $postId . "/solve?confirm=true <br><br>";
            $message .= "Nous vous remercions beaucoup pour votre aide !<br><br>";
            $message .= "Meilleures salutations.<br>";
            $message .= "L'équipe I Lost It";

            $email = new Emails();
            $email->send($finderEmail, $message);
        }

        if (!$confirmSolve) {
            $values['memberFinder_id'] = $finderId;
        }

        $values["status"] = $confirmSolve || $finderId == $userId ? 3 : 2;

        return $this->updateObject($postId, $values);
    }
}

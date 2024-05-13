<?php

namespace ILostIt\Model;

class Members
{
    /**
     * This method is designed to get the members
     * @param array $filters Example -> array(
     *   ["column1", "sql_operator", "%value1%"],
     *   ["column1", "sql_operator", "%value1%"]
     *  ) /!\ FILTERS ARE EXCLUSIVELY ANDs !
     * @return array
     */
    public function getMembers(array $filters = []): array
    {
        $db = new Database();

        $table = "members";
        $columns = ["id", "firstname", "lastname", "email", "password", "isVerified", "isActive", "isMod"];

        return $db->select($table, $columns, $filters);
    }

    /**
     * This method is designed to handle the register of a new member
     * @param array $memberInformations Example -> array("column1" => "value1", "column2" => "value2")
     * @return bool|string
     */
    public function registerNewMember(array $memberInformations): bool | string
    {
        $filters = [ ["email", "=", $memberInformations["email"]] ];

        $user = $this->getMembers($filters);

        if (!empty($user)) {
            return "L'email est déjà utilisé !";
        }

        $memberInformations["password"] = hash('sha256', $memberInformations["password"]);

        $db = new Database();
        $response = $db->insert("members", $memberInformations);
        $db = null;

        if (!$response) {
            return "Une erreur s'est produite";
        }

        $user = $this->getMembers($filters)[0];

        $mail = new Emails();

        $subject = "Vérifier votre email !";
        $message = "Bonjour!<br/><br/>Tout d'abord, bienvenue !<br/><br/>";
        $message .= "Pour accéder à notre site, vous devez tout d'abord vérifier votre email !<br/><br/>";
        $message .= "Pour cela, veuillez appuier sur le lien suivant : ";
        $message .= "http://localhost:8080/auth/register/verify/" . $user["id"];

        return $mail->send($memberInformations["email"], $message, $subject);
    }

    /**
     * This method is designed to verify a newly created user
     * @param string $id
     * @return bool
     */
    public function verifyUser(string $id): bool
    {
        $db = new Database();

        $table = "members";
        $response = $db->update($table, ["isVerified" => 1], [["id", "=", $id]]);

        if (!$response) {
            return false;
        }

        return true;
    }
}

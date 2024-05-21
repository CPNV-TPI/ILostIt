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

    public function checkLogin(string $email, string $password): array | string
    {
        $errorBadCredentials = "Email ou mot de passe incorrect !";
        $errorNotVerified = "Votre compte n'est pas vérifié. Veuillez vérifier votre email avant de vous connecter !";

        $filter = [ ["email", "=", $email] ];
        $member = $this->getMembers($filter);

        // Check if a user exists with this email
        if (empty($member)) {
            return $errorBadCredentials;
        }

        // Checks if both passwords are equal
        $arePasswordEquals = hash_equals($member[0]["password"], hash('sha256', $password));

        if (!$arePasswordEquals) {
            return $errorBadCredentials;
        }

        // Checks if user is verified or not
        $isUserVerified = $member[0]["isVerified"];

        if ($isUserVerified != 1) {
            return $errorNotVerified;
        }

        // Prepare SESSION informations
        return [
            "id" => $member[0]["id"],
            "firstname" => $member[0]["firstname"],
            "lastname" => $member[0]["lastname"],
            "email" => $member[0]["email"],
            "isMod" => $member[0]["isMod"]
        ];
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

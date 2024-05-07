<?php

namespace ILostIt\Model;

class Members
{
    public function getMembers(array $filters = []): array
    {
        $db = new Database();

        $table = "members";
        $columns = ["firstname", "lastname", "email", "password", "isVerified", "isActive", "isMod"];

        return $db->select($table, $columns, $filters);
    }

    public function register(array $values): bool
    {
        $filters = [ ["email", "=", $values["email"]] ];

        $user = $this->getMembers($filters);

        if (!empty($user)) {
            return false;
        }

        $values["password"] = hash('sha256', $values["password"]);

        $db = new Database();
        $response = $db->insert("members", $values);
        $db = null;

        if (!$response) {
            return false;
        }

        $mail = new Emails();

        $subject = "Vérifier votre email !";
        $message = "Bonjour!\n\nTout d'abord, bienvenue !\n\n";
        $message .= "Pour accéder à notre site, vous devez tout d'abord vérifier votre email !\n\n";
        $message .= "Pour cela, veuillez appuier sur le lien suivant :";
        $message .= "http://localhost:8080/auth/register/validate?email=" . $values["email"];

        return $mail->send($values["email"], $message, $subject);
    }
}

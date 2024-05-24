<?php

namespace ILostIt\Model;

use Exception;
use PDO;

class Database
{
    private string $host = "";
    private string $port = "";
    private string $dbName = "";
    private string $username = "";
    private string $password = "";

    public function __construct()
    {
        $env = parse_ini_file(".env.local");
        $this->host = $env['DB_HOST'];
        $this->port = $env['DB_PORT'];
        $this->dbName = $env['DB_NAME'];
        $this->username = $env['DB_USER'];
        $this->password = $env['DB_PASSWORD'];
    }

    /**
     * Method that handles the SELECT queries for the database
     *
     * @param  string $table
     * @param  array $columns Example -> array("column1", "column2")
     * @param  array $filters Example -> array(
     *  ["column1", "sql_operator", "%value1%"],
     *  ["column1", "sql_operator", "%value1%"]
     * ) /!\ FILTERS ARE EXCLUSIVELY ANDs !
     * @return array
     */
    public function select(
        string $table,
        array $columns,
        array $filters = array(),
        int $count = 0,
        int $offset = 0,
        array $orderBy = []
    ): array {
        $sql = "SELECT ";

        // Defines the columns
        foreach ($columns as $key => $column) {
            if ($key != 0) {
                $sql .= ", ";
            }

            $sql .= $table . "." . $column;
        }

        // Defines the table
        $sql .= " FROM " . $table;

        // Defines the filters if there is some
        if (count($filters) != 0) {
            $sql .= " WHERE ";

            foreach ($filters as $key => $filter) {
                if ($key != 0) {
                    $sql .= " AND ";
                }

                $sql .= $filter[0] . " " . $filter[1] . " \"" . $filter[2] . "\"";
            }
        }

        if (count($orderBy) != 0) {
            $sql .= " ORDER BY ";

            foreach ($orderBy as $value) {
                $sql .= $value[0] . " " . $value[1];
            }
        }

        if ($offset != 0) {
            $sql .= " OFFSET " . $offset . " ROWS";
        }

        if ($count != 0) {
            $sql .= " FETCH FIRST " . $count . " ROWS ONLY";
        }

        $sql .= ";";

        $db = $this->dbConnection();

        $object = $db->query($sql);
        return $object->fetchAll();
    }

    /**
     * Method that handles the INSERT queries for the database
     *
     * @param  string $table
     * @param  array $values Example -> array("column1" => "value1", "column2" => "value2")
     * @return bool
     */
    public function insert(string $table, array $values): bool
    {
        $sql = "INSERT INTO " . $table . " (";

        // Defines the columns
        $count = 0;
        foreach ($values as $key => $value) {
            if ($count != 0) {
                $sql .= ", ";
            }

            $sql .= $table . "." . $key;
            $count++;
        }

        $sql .= ") VALUES (";

        // Define the positions for the values using the columns names
        $count = 0;
        foreach ($values as $key => $value) {
            if ($count != 0) {
                $sql .= ", ";
            }

            $sql .= ":" . $key;
            $count++;
        }

        $sql .= ");";

        $db = $this->dbConnection();

        try {
            $db->prepare($sql)->execute($values);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * Method that handles the INSERT queries for the database
     *
     * @param  string $table
     * @param  array $values Example -> array("column1" => "value1", "column2" => "value2")
     * @param  array $conditions Example -> array(
     *  ["column1", "sql_operator", "%value1%"],
     *  ["column1", "sql_operator", "%value1%"]
     * ) /!\ FILTERS ARE EXCLUSIVELY ANDs !
     * @return bool
     */
    public function update(string $table, array $values, array $conditions): bool
    {
        $sql = "UPDATE " . $table . " SET ";

        // Defines the columns and positions for the values
        $count = 0;
        foreach ($values as $key => $value) {
            if ($count != 0) {
                $sql .= ", ";
            }

            $sql .= $table . "." . $key . "=:" . $key;
            $count++;
        }

        $sql .= " WHERE ";

        // Define the positions for the values using the columns names
        foreach ($conditions as $key => $condition) {
            if ($key != 0) {
                $sql .= " AND ";
            }

            $sql .= $condition[0] . " " . $condition[1] . " \"" . $condition[2] . "\"";
        }

        $sql .= ";";

        $db = $this->dbConnection();

        try {
            $prepare = $db->prepare($sql);
            $prepare->execute($values);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * Method that creates the connection to the database using PDO
     *
     * @return PDO
     */
    private function dbConnection(): PDO
    {
        return new PDO(
            "mysql:host=" . $this->host . ";dbname=" . $this->dbName . ";port=" . $this->port . ";charset=utf8",
            $this->username,
            $this->password
        );
    }
}

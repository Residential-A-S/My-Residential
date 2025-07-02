<?php

namespace core;

use PDO;

class Database
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = new PDO("mysql:dbname=" . DB_NAME . ";host=" . DB_HOST, DB_USER, DB_PASSWORD);
    }

    public static function getInstance(): ?Database
    {
        static $instance = null;
        if ($instance === null) {
            $instance = new Database();
        }

        return $instance;
    }

    public function query(string $sql): bool
    {
        return $this->connection->prepare($sql)->execute();
    }

    /**
     * @param string $table
     * @param string $columns
     * @param array $conditions
     * @param int|null $limit
     *
     * @return array|false
     * Method to select multiple rows of data from the database
     */
    public function selectAll(
        string $table,
        string $columns = "*",
        array $conditions = [],
        ?int $limit = null
    ): array|false {
        $sql    = "SELECT $columns FROM $table";
        $values = [];
        if (! empty($conditions)) {
            $sql .= " WHERE " . $this->buildConditions($conditions, $values);
        }
        if ($limit !== null) {
            $sql .= " LIMIT $limit";
        }
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($values);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param string $table
     * @param string $columns
     * @param array $conditions
     *
     * @return array|false
     * Method to select a single row of data from the database
     */
    public function selectSingle(string $table, string $columns = "*", array $conditions = []): array|false
    {
        $sql    = "SELECT $columns FROM $table";
        $values = [];
        if (! empty($conditions)) {
            $sql .= " WHERE " . $this->buildConditions($conditions, $values);
        }
        //$sql .= " LIMIT 1";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($values);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * @param string $table
     * @param array $data
     *
     * @return int|false
     *
     * Method to insert data into the database
     */
    public function insert(string $table, array $data): int|false
    {
        $sql = "INSERT INTO $table (";
        foreach ($data as $key => $value) {
            $sql .= "$key, ";
        }
        $sql = rtrim($sql, ", ") . ") VALUES (";
        foreach ($data as $key => $value) {
            $sql .= ":$key, ";
        }
        $sql = rtrim($sql, ", ") . ")";
        try {
            if ($this->connection->prepare($sql)->execute($data)) {
                return $this->connection->lastInsertId();
            }
        } catch (Exception) {
            return false;
        }

        return false;
    }

    /**
     * @param string $table
     * @param array $data
     * @param array $conditions
     *
     * @return bool
     * Method to update data in the database
     */
    public function update(string $table, array $data, array $conditions): bool
    {
        $values = [];
        $set    = $this->buildSet($data, $values);
        $cond   = $this->buildConditions($conditions, $values);

        return $this->connection->prepare("UPDATE $table SET $set WHERE $cond")->execute($values);
    }

    /**
     * @param string $table
     * @param array $conditions
     *
     * @return bool
     * Method to delete data from the database
     */
    public function delete(string $table, array $conditions): bool
    {
        $values = [];
        $sql    = "DELETE FROM $table WHERE " . $this->buildConditions($conditions, $values);

        return $this->connection->prepare($sql)->execute($values);
    }

    /**
     * @param array $conditions
     * @param array $values
     *
     * @return string
     * Method to convert conditions array to string with AND relation
     */
    private function buildConditions(array $conditions, array &$values): string
    {
        $cond = [];
        foreach ($conditions as $key => $value) {
            $cond[]               = "$key = :c$key";
            $values[ "c" . $key ] = $value;
        }

        return implode(" AND ", $cond);
    }

    /**
     * @param array $data
     * @param array $values
     *
     * @return string
     * Method to convert data array to string for SET clause
     */
    private function buildSet(array $data, array &$values): string
    {
        $set = [];
        foreach ($data as $key => $value) {
            $set[]                = "$key = :s$key";
            $values[ "s" . $key ] = $value;
        }

        return implode(", ", $set);
    }
}

<?php

namespace Plugo\Manager;

use PDO;
use PDOStatement;

require dirname(__DIR__, 2) . '/config/database.php';

abstract class AbstractManager {
    /**
     * @return PDO
     */
    private function connect(): PDO
    {
        $db = new PDO(
        'mysql:host=' . DB_INFOS['host'] . ';port=' . DB_INFOS['port'] . ';dbname=' . DB_INFOS['dbname'],
            DB_INFOS['username'],
            DB_INFOS['password']
        );

        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->exec("SET NAMES utf8");

        return $db;
    }

    /**
     * @param string $query
     * @param array $data
     * @return PDOStatement
     */
    private function executeQuery(string $query, array $data = []): PDOStatement
    {
        $db = $this->connect();
        $stmt = $db->prepare($query);

        foreach ($data as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();

        return $stmt;
    }

    /**
     * @param string $class
     * @return string
     */
    private function classToTable(string $class): string
    {
        $tmp = explode('\\', $class);

        return strtolower(end($tmp) . 's');
    }

    /**
     * @param string $class
     * @param array $filters
     * @return mixed
     */
    protected function readOne(string $class, array $filters): mixed
    {
        $query = 'SELECT * FROM ' . $this->classToTable($class) . ' WHERE ';

        foreach (array_keys($filters) as $filter) {
            $query .= $filter . " = :" . $filter;

            if ($filter != array_key_last($filters)) {
                $query .= 'AND ';
            }
        }

        $stmt = $this->executeQuery($query, $filters);

        $stmt->setFetchMode(PDO::FETCH_CLASS, $class);

        return $stmt->fetch();
    }

    /**
     * @param string $class
     * @param array $filters
     * @param array $order
     * @param int|null $limit
     * @param int|null $offset
     * @return array|false
     */
    protected function readMany(string $class, array $filters = [], array $order = [], int $limit = null, int $offset = null): array|false
    {
        $query = 'SELECT * FROM ' . $this->classToTable($class);

        if (!empty($filters)) {
            $query .= ' WHERE ';

            foreach (array_keys($filters) as $filter) {
                $query .= $filter . " = :" . $filter;

                if ($filter != array_key_last($filters)) {
                    $query .= 'AND ';
                }
            }
        }

        if (!empty($order)) {
            $query .= ' ORDER BY ';

            foreach ($order as $key => $val) {
                $query .= $key . ' ' . $val;

                if ($key != array_key_last($order)) {
                    $query .= ', ';
                }
            }
        }

        if (isset($limit)) {
            $query .= ' LIMIT ' . $limit;

            if (isset($offset)) {
                $query .= ' OFFSET ' . $offset;
            }
        }

        $stmt = $this->executeQuery($query, $filters);

        $stmt->setFetchMode(PDO::FETCH_CLASS, $class);

        return $stmt->fetchAll();
    }

    /**
     * @param string $class
     * @param array $fields
     * @return PDOStatement
     */
    protected function create(string $class, array $fields): PDOStatement
    {
        $query = "INSERT INTO " . $this->classToTable($class) . " (";

        foreach (array_keys($fields) as $field) {
            $query .= $field;

            if ($field != array_key_last($fields)) {
                $query .= ', ';
            }
        }

        $query .= ') VALUES (';

        foreach (array_keys($fields) as $field) {
            $query .= ':' . $field;

            if ($field != array_key_last($fields)) {
                $query .= ', ';
            }
        }

        $query .= ')';

        return $this->executeQuery($query, $fields);
    }

    /**
     * @param string $class
     * @param array $fields
     * @param int $id
     * @return PDOStatement
     */
    protected function update(string $class, int $id, array $fields): PDOStatement
    {
        $query = "UPDATE " . $this->classToTable($class) . " SET ";

        foreach (array_keys($fields) as $field) {
            $query .= $field . " = :" . $field;

            if ($field != array_key_last($fields)) {
                $query .= ', ';
            }
        }

        $query .= ' WHERE id = :id';
        $fields['id'] = $id;

        return $this->executeQuery($query, $fields);
    }

    /**
     * @param string $class
     * @param int $id
     * @return PDOStatement
     */
    protected function delete(string $class, int $id): PDOStatement
    {
        $query = "DELETE FROM " . $this->classToTable($class) . " WHERE id = :id";

        return $this->executeQuery($query, ['id' => $id]);
    }
}

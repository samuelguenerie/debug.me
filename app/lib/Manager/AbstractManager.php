<?php

namespace Plugo\Manager;

use DateTime;
use DateTimeInterface;
use PDO;
use PDOStatement;
use Plugo\Services\Mapper\Mapper;
use Plugo\Services\Security\Security;
use ReflectionException;

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
    protected function executeQuery(string $query, array $data = []): PDOStatement
    {
        foreach ($data as $key => $value) {
            if ($value instanceof DateTime) {
                $data[$key] = $value->format(DateTimeInterface::W3C);
            }
        }

        $db = $this->connect();
        $stmt = $db->prepare($query);

        foreach ($data as $key => $value) {
            if (!is_null($value)) {
                $stmt->bindValue($key, $value);
            }
        }

        $stmt->execute();

        return $stmt;
    }

    /**
     * @param string $class
     * @return string
     */
    protected function classToTable(string $class): string
    {
        $tmp = explode('\\', $class);

        return strtolower(end($tmp) . 's');
    }

    /**
     * @param string $class
     * @param array $filters
     * @return mixed
     * @throws ReflectionException
     */
    protected function readOne(string $class, array $filters): mixed
    {
        $query = 'SELECT * FROM ' . $this->classToTable($class);

        $this->buildWhereClause($query, $filters);

        $stmt = $this->executeQuery($query, $filters);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $result = $stmt->fetch();

        if (!$result) {
            return $result;
        }

        $security = new Security();
        $mapper = new Mapper();

        return $mapper->arrayToObject($security->secureXssVulnerabilities($result), $class);
    }

    /**
     * @param string $class
     * @param array $filters
     * @param array $order
     * @param int|null $limit
     * @param int|null $offset
     * @return array|false
     * @throws ReflectionException
     */
    protected function readMany(string $class, array $filters = [], array $order = [], int $limit = null, int $offset = null): array|false
    {
        $query = 'SELECT * FROM ' . $this->classToTable($class);

        $this->buildWhereClause($query, $filters);
        $this->buildOrderClause($query, $order);
        $this->buildLimitClause($query, $limit, $offset);

        $stmt = $this->executeQuery($query, $filters);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $result = $stmt->fetchAll();

        $security = new Security();
        $mapper = new Mapper();

        foreach ($result as $key => $item) {
            $result[$key] = $mapper->arrayToObject($security->secureXssVulnerabilities($item), $class);
        }

        return $result;
    }

    /**
     * @param string $query
     * @param array $filters
     * @return void
     */
    protected function buildWhereClause(string &$query, array $filters = []): void
    {
        if (!empty($filters)) {
            $query .= ' WHERE ';

            foreach ($filters as $key => $filter) {
                if (is_null($filter)) {
                    $query .= $key . " IS NULL";
                } else {
                    $query .= $key . " = :" . $key;
                }

                if ($key != array_key_last($filters)) {
                    $query .= ' AND ';
                }
            }
        }
    }

    /**
     * @param string $query
     * @param array $order
     * @return void
     */
    protected function buildOrderClause(string &$query, array $order = []): void
    {
        if (!empty($order)) {
            $query .= ' ORDER BY ';

            foreach ($order as $key => $val) {
                $query .= $key . ' ' . $val;

                if ($key != array_key_last($order)) {
                    $query .= ', ';
                }
            }
        }
    }

    /**
     * @param string $query
     * @param int|null $limit
     * @param int|null $offset
     * @return void
     */
    protected function buildLimitClause(string &$query, int $limit = null, int $offset = null): void
    {
        if (isset($limit)) {
            $query .= ' LIMIT ' . $limit;

            if (isset($offset)) {
                $query .= ' OFFSET ' . $offset;
            }
        }
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

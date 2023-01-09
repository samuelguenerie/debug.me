<?php

namespace App\Manager;

use App\Entity\Ticket;
use Exception;
use PDO;
use PDOStatement;
use Plugo\Manager\AbstractManager;
use Plugo\Services\Mapper\Mapper;
use Plugo\Services\Security\Security;
use ReflectionException;

class TicketManager extends AbstractManager {
    /**
     * @param int $id
     * @return mixed
     * @throws ReflectionException
     */
    public function find(int $id): mixed
    {
        return $this->readOne(Ticket::class, ['id' => $id]);
    }

    /**
     * @param array $filters
     * @return mixed
     * @throws ReflectionException
     */
    public function findOneBy(array $filters): mixed
    {
        return $this->readOne(Ticket::class, $filters);
    }

    /**
     * @return false|array
     * @throws ReflectionException
     */
    public function findAll(): false|array
    {
        return $this->readMany(Ticket::class);
    }

    /**
     * @param array $filters
     * @param array $order
     * @param int|null $limit
     * @param int|null $offset
     * @return array|false
     * @throws ReflectionException
     */
    public function findBy(array $filters, array $order = [], int $limit = null, int $offset = null): false|array
    {
        return $this->readMany(Ticket::class, $filters, $order, $limit, $offset);
    }

    /**
     * @param array $filters
     * @param string|null $search
     * @param array $order
     * @param int|null $limit
     * @param int|null $offset
     * @return array|false
     * @throws ReflectionException
     */
    public function search(array $filters, ?string $search = null, array $order = [], int $limit = null, int $offset = null): false|array
    {
        $class = Ticket::class;

        $query = 'SELECT * FROM ' . $this->classToTable($class);

        $this->buildWhereClause($query, $filters);

        if (!empty($search)) {
            $query .= " AND (title LIKE '$search' OR content LIKE '$search')";
        }

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
     * @param Ticket $ticket
     * @return PDOStatement
     * @throws Exception
     */
    public function add(Ticket $ticket): PDOStatement
    {
        $fields = [
            'title' => $ticket->getTitle(),
            'content' => $ticket->getContent(),
            'is_open' => $ticket->getIsOpen(),
            'user_id' => $ticket->getUser()->getId()
        ];

        if (!empty($ticket->getImage())) {
            $fields['image'] = $ticket->getImage();
        }

        if (!empty($ticket->getCreatedAt())) {
            $fields['created_at'] = $ticket->getCreatedAt();
        }

        if (!empty($ticket->getUpdatedAt())) {
            $fields['updated_at'] = $ticket->getUpdatedAt();
        }

        return $this->create(Ticket::class, $fields);
    }

    /**
     * @param Ticket $ticket
     * @return PDOStatement
     * @throws Exception
     */
    public function edit(Ticket $ticket): PDOStatement
    {
        $fields = [
            'title' => $ticket->getTitle(),
            'content' => $ticket->getContent(),
            'is_open' => $ticket->getIsOpen(),
            'user_id' => $ticket->getUser()->getId()
        ];

        if ($ticket->getImage()) {
            $fields['image'] = $ticket->getImage();
        }

        if ($ticket->getCreatedAt()) {
            $fields['created_at'] = $ticket->getCreatedAt();
        }

        if ($ticket->getUpdatedAt()) {
            $fields['updated_at'] = $ticket->getUpdatedAt();
        }

        return $this->update(Ticket::class, $ticket->getId(), $fields);
    }

    /**
     * @param Ticket $ticket
     * @return PDOStatement
     */
    public function remove(Ticket $ticket): PDOStatement
    {
        return $this->delete(Ticket::class, $ticket->getId());
    }
}

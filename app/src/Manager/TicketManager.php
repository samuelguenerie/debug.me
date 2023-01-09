<?php

namespace App\Manager;

use App\Entity\Ticket;
use Exception;
use PDOStatement;
use Plugo\Manager\AbstractManager;
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

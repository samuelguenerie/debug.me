<?php

namespace App\Manager;

use App\Entity\Ticket;
use PDOStatement;
use Plugo\Manager\AbstractManager;

class TicketManager extends AbstractManager {
    /**
     * @param int $id
     * @return mixed
     */
    public function find(int $id): mixed
    {
        return $this->readOne(Ticket::class, ['id' => $id]);
    }

    /**
     * @param array $filters
     * @return mixed
     */
    public function findOneBy(array $filters): mixed
    {
        return $this->readOne(Ticket::class, $filters);
    }

    /**
     * @return false|array
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
     */
    public function findBy(array $filters, array $order = [], int $limit = null, int $offset = null): false|array
    {
        return $this->readMany(Ticket::class, $filters, $order, $limit, $offset);
    }

    /**
     * @param Ticket $ticket
     * @return PDOStatement
     */
    public function add(Ticket $ticket): PDOStatement
    {
        return $this->create(Ticket::class, [
            'title' => $ticket->getTitle(),
            'content' => $ticket->getContent(),
            'is_open' => $ticket->getIsOpen(),
            'user_id' => $ticket->getUser()->getId()
        ]);
    }

    /**
     * @param Ticket $ticket
     * @return PDOStatement
     */
    public function edit(Ticket $ticket): PDOStatement
    {
        return $this->update(Ticket::class, $ticket->getId(), [
                'title' => $ticket->getTitle(),
                'content' => $ticket->getContent(),
                'is_open' => $ticket->getIsOpen(),
                'user_id' => $ticket->getUser()->getId()
            ]
        );
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
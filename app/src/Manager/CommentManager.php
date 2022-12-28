<?php

namespace App\Manager;

use App\Entity\Comment;
use PDOStatement;
use Plugo\Manager\AbstractManager;

class CommentManager extends AbstractManager {
    /**
     * @param int $id
     * @return mixed
     */
    public function find(int $id): mixed
    {
        return $this->readOne(Comment::class, ['id' => $id]);
    }

    /**
     * @param array $filters
     * @return mixed
     */
    public function findOneBy(array $filters): mixed
    {
        return $this->readOne(Comment::class, $filters);
    }

    /**
     * @return false|array
     */
    public function findAll(): false|array
    {
        return $this->readMany(Comment::class);
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
        return $this->readMany(Comment::class, $filters, $order, $limit, $offset);
    }

    /**
     * @param Comment $comment
     * @return PDOStatement
     */
    public function add(Comment $comment): PDOStatement
    {
        return $this->create(Comment::class, [
            'content' => $comment->getContent(),
            'ticket_id' => $comment->getTicket()->getId(),
            'user_id' => $comment->getUser()->getId()
        ]);
    }

    /**
     * @param Comment $comment
     * @return PDOStatement
     */
    public function edit(Comment $comment): PDOStatement
    {
        return $this->update(Comment::class, $comment->getId(), [
                'content' => $comment->getContent(),
                'ticket_id' => $comment->getTicket()->getId(),
                'user_id' => $comment->getUser()->getId()
            ]
        );
    }

    /**
     * @param Comment $comment
     * @return PDOStatement
     */
    public function remove(Comment $comment): PDOStatement
    {
        return $this->delete(Comment::class, $comment->getId());
    }
}
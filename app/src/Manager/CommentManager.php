<?php

namespace App\Manager;

use App\Entity\Comment;
use Exception;
use PDOStatement;
use Plugo\Manager\AbstractManager;
use ReflectionException;

class CommentManager extends AbstractManager {
    /**
     * @param int $id
     * @return mixed
     * @throws ReflectionException
     */
    public function find(int $id): mixed
    {
        return $this->readOne(Comment::class, ['id' => $id]);
    }

    /**
     * @param array $filters
     * @return mixed
     * @throws ReflectionException
     */
    public function findOneBy(array $filters): mixed
    {
        return $this->readOne(Comment::class, $filters);
    }

    /**
     * @return false|array
     * @throws ReflectionException
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
     * @throws ReflectionException
     */
    public function findBy(array $filters, array $order = [], int $limit = null, int $offset = null): false|array
    {
        return $this->readMany(Comment::class, $filters, $order, $limit, $offset);
    }

    /**
     * @param Comment $comment
     * @return PDOStatement
     * @throws Exception
     */
    public function add(Comment $comment): PDOStatement
    {
        $fields = [
            'content' => $comment->getContent(),
            'ticket_id' => $comment->getTicket()->getId(),
            'user_id' => $comment->getUser()->getId()
        ];

        if (!empty($comment->getParent())) {
            $fields['comment_id'] = $comment->getParent()->getId();
        }

        if (!empty($comment->getCreatedAt())) {
            $fields['created_at'] = $comment->getCreatedAt();
        }

        if (!empty($comment->getUpdatedAt())) {
            $fields['updated_at'] = $comment->getUpdatedAt();
        }

        return $this->create(Comment::class, $fields);
    }

    /**
     * @param Comment $comment
     * @return PDOStatement
     * @throws Exception
     */
    public function edit(Comment $comment): PDOStatement
    {
        $fields = [
            'content' => $comment->getContent(),
            'ticket_id' => $comment->getTicket()->getId(),
            'user_id' => $comment->getUser()->getId()
        ];

        if ($comment->getParent()) {
            $fields['comment_id'] = $comment->getParent()->getId();
        }

        if ($comment->getCreatedAt()) {
            $fields['created_at'] = $comment->getCreatedAt();
        }

        if ($comment->getUpdatedAt()) {
            $fields['updated_at'] = $comment->getUpdatedAt();
        }

        return $this->update(Comment::class, $comment->getId(), $fields);
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

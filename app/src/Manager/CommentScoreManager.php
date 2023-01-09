<?php

namespace App\Manager;

use App\Entity\CommentScore;
use Exception;
use PDOStatement;
use Plugo\Manager\AbstractManager;
use ReflectionException;

class CommentScoreManager extends AbstractManager {
    /**
     * @param int $id
     * @return mixed
     * @throws ReflectionException
     */
    public function find(int $id): mixed
    {
        return $this->readOne(CommentScore::class, ['id' => $id]);
    }

    /**
     * @param array $filters
     * @return mixed
     * @throws ReflectionException
     */
    public function findOneBy(array $filters): mixed
    {
        return $this->readOne(CommentScore::class, $filters);
    }

    /**
     * @return false|array
     * @throws ReflectionException
     */
    public function findAll(): false|array
    {
        return $this->readMany(CommentScore::class);
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
        return $this->readMany(CommentScore::class, $filters, $order, $limit, $offset);
    }

    /**
     * @param CommentScore $commentScore
     * @return PDOStatement
     * @throws Exception
     */
    public function add(CommentScore $commentScore): PDOStatement
    {
        $fields = [
            'comment_id' => $commentScore->getComment()->getId(),
            'user_id' => $commentScore->getUser()->getId(),
            'score' => $commentScore->getScore()
        ];

        if (!empty($commentScore->getCreatedAt())) {
            $fields['created_at'] = $commentScore->getCreatedAt();
        }

        if (!empty($commentScore->getUpdatedAt())) {
            $fields['updated_at'] = $commentScore->getUpdatedAt();
        }

        return $this->create(CommentScore::class, $fields);
    }

    /**
     * @param CommentScore $commentScore
     * @return PDOStatement
     * @throws Exception
     */
    public function edit(CommentScore $commentScore): PDOStatement
    {
        $fields = [
            'comment_id' => $commentScore->getComment()->getId(),
            'user_id' => $commentScore->getUser()->getId(),
            'score' => $commentScore->getScore()
        ];

        if ($commentScore->getCreatedAt()) {
            $fields['created_at'] = $commentScore->getCreatedAt();
        }

        if ($commentScore->getUpdatedAt()) {
            $fields['updated_at'] = $commentScore->getUpdatedAt();
        }

        return $this->update(CommentScore::class, $commentScore->getId(), $fields);
    }

    /**
     * @param CommentScore $commentScore
     * @return PDOStatement
     */
    public function remove(CommentScore $commentScore): PDOStatement
    {
        return $this->delete(CommentScore::class, $commentScore->getId());
    }
}

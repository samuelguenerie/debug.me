<?php

namespace App\Manager;

use App\Entity\Tag;
use PDOStatement;
use Plugo\Manager\AbstractManager;

class TagManager extends AbstractManager {
    /**
     * @param int $id
     * @return mixed
     */
    public function find(int $id): mixed
    {
        return $this->readOne(Tag::class, ['id' => $id]);
    }

    /**
     * @param array $filters
     * @return mixed
     */
    public function findOneBy(array $filters): mixed
    {
        return $this->readOne(Tag::class, $filters);
    }

    /**
     * @return false|array
     */
    public function findAll(): false|array
    {
        return $this->readMany(Tag::class);
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
        return $this->readMany(Tag::class, $filters, $order, $limit, $offset);
    }

    /**
     * @param Tag $tag
     * @return PDOStatement
     */
    public function add(Tag $tag): PDOStatement
    {
        return $this->create(Tag::class, [
            'title' => $tag->getTitle()
        ]);
    }

    /**
     * @param Tag $tag
     * @return PDOStatement
     */
    public function edit(Tag $tag): PDOStatement
    {
        return $this->update(Tag::class, $tag->getId(), [
                'title' => $tag->getTitle()
            ]
        );
    }

    /**
     * @param Tag $tag
     * @return PDOStatement
     */
    public function remove(Tag $tag): PDOStatement
    {
        return $this->delete(Tag::class, $tag->getId());
    }
}
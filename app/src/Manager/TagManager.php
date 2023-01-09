<?php

namespace App\Manager;

use App\Entity\Tag;
use Exception;
use PDOStatement;
use Plugo\Manager\AbstractManager;
use ReflectionException;

class TagManager extends AbstractManager {
    /**
     * @param int $id
     * @return mixed
     * @throws ReflectionException
     */
    public function find(int $id): mixed
    {
        return $this->readOne(Tag::class, ['id' => $id]);
    }

    /**
     * @param array $filters
     * @return mixed
     * @throws ReflectionException
     */
    public function findOneBy(array $filters): mixed
    {
        return $this->readOne(Tag::class, $filters);
    }

    /**
     * @return false|array
     * @throws ReflectionException
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
     * @throws ReflectionException
     */
    public function findBy(array $filters, array $order = [], int $limit = null, int $offset = null): false|array
    {
        return $this->readMany(Tag::class, $filters, $order, $limit, $offset);
    }

    /**
     * @param Tag $tag
     * @return PDOStatement
     * @throws Exception
     */
    public function add(Tag $tag): PDOStatement
    {
        $fields = [
            'title' => $tag->getTitle()
        ];

        if (!empty($tag->getCreatedAt())) {
            $fields['created_at'] = $tag->getCreatedAt();
        }

        if (!empty($tag->getUpdatedAt())) {
            $fields['updated_at'] = $tag->getUpdatedAt();
        }

        return $this->create(Tag::class, $fields);
    }

    /**
     * @param Tag $tag
     * @return PDOStatement
     * @throws Exception
     */
    public function edit(Tag $tag): PDOStatement
    {
        $fields = [
            'title' => $tag->getTitle()
        ];

        if ($tag->getCreatedAt()) {
            $fields['created_at'] = $tag->getCreatedAt();
        }

        if ($tag->getUpdatedAt()) {
            $fields['updated_at'] = $tag->getUpdatedAt();
        }

        return $this->update(Tag::class, $tag->getId(), $fields);
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

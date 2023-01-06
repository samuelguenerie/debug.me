<?php

namespace App\Manager;

use App\Entity\User;
use DateTimeInterface;
use Exception;
use PDOStatement;
use Plugo\Manager\AbstractManager;

class UserManager extends AbstractManager {
    /**
     * @param int $id
     * @return mixed
     */
    public function find(int $id): mixed
    {
        return $this->readOne(User::class, ['id' => $id]);
    }

    /**
     * @param array $filters
     * @return mixed
     */
    public function findOneBy(array $filters): mixed
    {
        return $this->readOne(User::class, $filters);
    }

    /**
     * @return false|array
     */
    public function findAll(): false|array
    {
        return $this->readMany(User::class);
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
        return $this->readMany(User::class, $filters, $order, $limit, $offset);
    }

    /**
     * @param User $user
     * @return PDOStatement
     * @throws Exception
     */
    public function add(User $user): PDOStatement
    {
        $fields = [
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'username' => $user->getUsername(),
            'points' => $user->getPoints(),
            'is_moderator' => $user->getIsModerator(),
            'is_blocked' => $user->getIsBlocked()
        ];

        if (!empty($user->getCreatedAt())) {
            $fields['created_at'] = $user->getCreatedAt();
        }

        if (!empty($user->getUpdatedAt())) {
            $fields['updated_at'] = $user->getUpdatedAt();
        }

        return $this->create(User::class, $fields);
    }

    /**
     * @param User $user
     * @return PDOStatement
     * @throws Exception
     */
    public function edit(User $user): PDOStatement
    {
        $fields = [
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'username' => $user->getUsername(),
            'points' => $user->getPoints(),
            'is_moderator' => $user->getIsModerator(),
            'is_blocked' => $user->getIsBlocked()
        ];

        if ($user->getCreatedAt()) {
            $fields['created_at'] = $user->getCreatedAt();
        }

        if ($user->getUpdatedAt()) {
            $fields['updated_at'] = $user->getUpdatedAt();
        }

        return $this->update(User::class, $user->getId(), $fields);
    }

    /**
     * @param User $user
     * @return PDOStatement
     */
    public function remove(User $user): PDOStatement
    {
        return $this->delete(User::class, $user->getId());
    }
}

<?php

namespace App\Manager;

use App\Entity\User;
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
     */
    public function add(User $user): PDOStatement
    {
        return $this->create(User::class, [
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'username' => $user->getUsername(),
            'points' => $user->getPoints(),
            'is_moderator' => $user->getIsModerator(),
            'is_blocked' => $user->getIsBlocked()
        ]);
    }

    /**
     * @param User $user
     * @return PDOStatement
     */
    public function edit(User $user): PDOStatement
    {
        return $this->update(User::class, $user->getId(), [
                'email' => $user->getEmail(),
                'password' => $user->getPassword(),
                'username' => $user->getUsername(),
                'points' => $user->getPoints(),
                'is_moderator' => $user->getIsModerator(),
                'is_blocked' => $user->getIsBlocked()
            ]
        );
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
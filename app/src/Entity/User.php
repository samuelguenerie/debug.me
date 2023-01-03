<?php

namespace App\Entity;

class User
{
    private ?int $id;
    private ?string $email;
    private ?string $password;
    private ?string $username;
    private ?int $points;
    private ?int $is_moderator;
    private ?int $is_blocked;
    private ?string $created_at;
    private ?string $updated_at;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @return void
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     * @return void
     */
    public function setPassword(?string $password): void
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string|null $username
     * @return void
     */
    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return int|null
     */
    public function getPoints(): ?int
    {
        return $this->points;
    }

    /**
     * @param int|null $points
     * @return void
     */
    public function setPoints(?int $points): void
    {
        $this->points = $points;
    }

    /**
     * @return void
     */
    public function incrementPoint(): void
    {
        $this->points++;
    }

    /**
     * @return void
     */
    public function decrementPoint(): void
    {
        $this->points--;
    }

    /**
     * @return int|null
     */
    public function getIsModerator(): ?int
    {
        return $this->is_moderator;
    }

    /**
     * @param bool|null $is_moderator
     * @return void
     */
    public function setIsModerator(?bool $is_moderator): void
    {
        $this->is_moderator = $is_moderator;
    }

    /**
     * @return int|null
     */
    public function getIsBlocked(): ?int
    {
        return $this->is_blocked;
    }

    /**
     * @param bool|null $is_blocked
     * @return void
     */
    public function setIsBlocked(?bool $is_blocked): void
    {
        $this->is_blocked = $is_blocked;
    }

    /**
     * @return string|null
     */
    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    /**
     * @param string|null $created_at
     * @return void
     */
    public function setCreatedAt(?string $created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @return string|null
     */
    public function getUpdatedAt(): ?string
    {
        return $this->updated_at;
    }

    /**
     * @param string|null $updated_at
     * @return void
     */
    public function setUpdatedAt(?string $updated_at): void
    {
        $this->updated_at = $updated_at;
    }
}

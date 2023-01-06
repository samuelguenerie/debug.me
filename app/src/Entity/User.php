<?php

namespace App\Entity;

use App\Manager\CommentManager;
use App\Manager\TicketManager;
use DateTime;
use DateTimeInterface;
use Exception;

class User
{
    private ?int $id;
    private ?string $email;
    private ?string $password;
    private ?string $username;
    private ?int $points = 0;
    private ?int $is_moderator = 0;
    private ?int $is_blocked = 0;
    private ?string $created_at = null;
    private ?string $updated_at = null;

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
     * @return DateTime|null
     * @throws Exception
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->created_at ? new DateTime($this->created_at) : null;
    }

    /**
     * @param DateTime|null $created_at
     * @return void
     */
    public function setCreatedAt(?DateTime $created_at): void
    {
        $this->created_at = $created_at->format(DateTimeInterface::W3C);
    }

    /**
     * @return DateTime|null
     * @throws Exception
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updated_at ? new DateTime($this->updated_at) : null;
    }

    /**
     * @param DateTime|null $updated_at
     * @return void
     */
    public function setUpdatedAt(?DateTime $updated_at): void
    {
        $this->updated_at = $updated_at->format(DateTimeInterface::W3C);
    }

    /**
     * @return false|array
     */
    public function getTickets(): false|array
    {
        $ticketManager = new TicketManager();

        return $ticketManager->findBy(['user_id' => $this->id], ['created_at' => 'DESC']);
    }

    /**
     * @return false|array
     */
    public function getComments(): false|array
    {
        $commentManager = new CommentManager();

        return $commentManager->findBy(['user_id' => $this->id], ['created_at' => 'DESC']);
    }
}

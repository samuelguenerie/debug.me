<?php

namespace App\Entity;

use App\Manager\CommentManager;
use App\Manager\UserManager;
use DateTime;
use DateTimeInterface;
use Exception;
use ReflectionException;

class CommentScore
{
    /**
     * @var int|null
     */
    private ?int $id;

    /**
     * @var int|null
     */
    private ?int $comment_id;

    /**
     * @var int|null
     */
    private ?int $user_id;

    /**
     * @var int|null
     */
    private ?int $score;

    /**
     * @var string|null
     */
    private ?string $created_at = null;

    /**
     * @var string|null
     */
    private ?string $updated_at = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Comment|null
     * @throws ReflectionException
     */
    public function getComment(): ?Comment
    {
        $commentManager = new CommentManager();

        return $commentManager->find($this->comment_id);
    }

    /**
     * @param Comment|null $comment
     * @return void
     */
    public function setComment(?Comment $comment): void
    {
        $this->comment_id = $comment->getId();
    }

    /**
     * @return User|null
     * @throws ReflectionException
     */
    public function getUser(): ?User
    {
        $userManager = new UserManager();

        return $userManager->find($this->user_id);
    }

    /**
     * @param User|null $user
     * @return void
     */
    public function setUser(?User $user): void
    {
        $this->user_id = $user->getId();
    }

    /**
     * @return int|null
     */
    public function getScore(): ?int
    {
        return $this->score;
    }

    /**
     * @param int|null $score
     * @return void
     */
    public function setScore(?int $score): void
    {
        $this->score = $score;
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
}

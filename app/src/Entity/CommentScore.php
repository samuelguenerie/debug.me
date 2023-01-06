<?php

namespace App\Entity;

use App\Manager\CommentManager;
use App\Manager\UserManager;

class CommentScore
{
    private ?int $id;
    private ?int $comment_id;
    private ?int $user_id;
    private ?int $score;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Comment|null
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
}

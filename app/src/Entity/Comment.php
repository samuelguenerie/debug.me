<?php

namespace App\Entity;

use App\Manager\CommentManager;
use App\Manager\CommentScoreManager;
use App\Manager\TicketManager;
use App\Manager\UserManager;
use DateTime;
use DateTimeInterface;
use Exception;

class Comment
{
    private ?int $id;
    private ?string $content;
    private ?int $ticket_id;
    private ?int $user_id;
    private ?int $comment_id = null;
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
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string|null $content
     * @return void
     */
    public function setContent(?string $content): void
    {
        $this->content = nl2br($content);
    }

    /**
     * @return Ticket|null
     */
    public function getTicket(): ?Ticket
    {
        $ticketManager = new TicketManager();

        return $ticketManager->find($this->ticket_id);
    }

    /**
     * @param Ticket|null $ticket
     * @return void
     */
    public function setTicket(?Ticket $ticket): void
    {
        $this->ticket_id = $ticket->getId();
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
     * @return Comment|null
     */
    public function getComment(): ?Comment
    {
        return !empty($this->comment_id) ? (new CommentManager())->find($this->comment_id) : null;
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
     * @return int
     */
    public function getScore(): int
    {
        $commentScoreManager = new CommentScoreManager();

        $comments = $commentScoreManager->findBy(['comment_id' => $this->id]);

        $score = 0;

        foreach ($comments as $comment) {
            $score += $comment->getScore();
        }

        return $score;
    }

    /**
     * @return false|array
     */
    public function getScores(): false|array
    {
        $commentScoreManager = new CommentScoreManager();

        return $commentScoreManager->findBy(['comment_id' => $this->id]);
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function getScoreFromUser(User $user): mixed
    {
        $commentScoreManager = new CommentScoreManager();

        return $commentScoreManager->findOneBy(['comment_id' => $this->id, 'user_id' => $user->getId()]);
    }
}

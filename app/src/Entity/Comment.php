<?php

namespace App\Entity;

use App\Manager\TicketManager;
use App\Manager\UserManager;

class Comment
{
    private ?int $id;
    private ?string $content;
    private ?int $ticket_id;
    private ?int $user_id;
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
     * @return string|null
     */
    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    /**
     * @return string|null
     */
    public function getUpdatedAt(): ?string
    {
        return $this->updated_at;
    }
}

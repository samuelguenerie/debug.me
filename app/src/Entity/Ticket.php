<?php

namespace App\Entity;

use App\Manager\CommentManager;
use App\Manager\UserManager;
use DateTime;
use DateTimeInterface;
use Exception;
use Plugo\Services\Upload\Upload;
use ReflectionException;

class Ticket
{
    /**
     * @var int|null
     */
    private ?int $id;

    /**
     * @var string|null
     */
    private ?string $title;

    /**
     * @var string|null
     */
    private ?string $content;

    /**
     * @var int|null
     */
    private ?int $is_open = 1;

    /**
     * @var string|null
     */
    private ?string $image = null;

    /**
     * @var int|null
     */
    private ?int $user_id;

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
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     * @return void
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
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
     * @return int|null
     */
    public function getIsOpen(): ?int
    {
        return $this->is_open;
    }

    /**
     * @param bool|null $is_open
     * @return void
     */
    public function setIsOpen(?bool $is_open): void
    {
        $this->is_open = $is_open;
    }

    /**
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param string|null $image
     * @return void
     */
    public function setImage(?string $image): void
    {
        $upload = new Upload();

        $this->image = $upload->getUploadDir() . '/' . $image;
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
     * @throws ReflectionException
     */
    public function getComments(): false|array
    {
        $commentManager = new CommentManager();

        return $commentManager->findBy(['ticket_id' => $this->id, 'comment_id' => null], ['created_at' => 'DESC']);
    }

    /**
     * @return Comment|null
     * @throws ReflectionException
     */
    public function getLastReply(): ?Comment
    {
        $commentManager = new CommentManager();

        $comments = $commentManager->findBy(['ticket_id' => $this->id, 'comment_id' => null], ['created_at' => 'DESC'], 1);

        return $comments[0] ?? null;
    }
}

<?php

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Exception;

class Tag
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

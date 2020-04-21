<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use DateTimeZone;
use Doctrine\ORM\Mapping as ORM;
use Exception;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 */
class Post
{
    /**
     * @var string
     */
    private const DEFAULT_TIMEZONE = 'America/Sao_Paulo';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     * @var string
     */
    private string $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private string $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string|null
     */
    private ?string $description = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @var string|null
     */
    private ?string $content = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $updatedAt;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     *
     * @return $this
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description ? $description : null;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return $this
     */
    public function setContent(?string $content): self
    {
        $this->content = $content ? $content : null;

        return $this;
    }

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeInterface $createdAt
     *
     * @return $this
     */
    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return DateTimeInterface
     */
    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTimeInterface $updatedAt
     *
     * @return $this
     */
    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }


    /**
     * @return $this
     * @throws Exception
     */
    public function setCreatedAtValue(): self
    {
        $this->createdAt = new DateTime('now', new DateTimeZone(self::DEFAULT_TIMEZONE));

        return $this;
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function setUpdatedAtValue(): self
    {
        $this->updatedAt = new DateTime('now', new DateTimeZone(self::DEFAULT_TIMEZONE));

        return $this;
    }
}

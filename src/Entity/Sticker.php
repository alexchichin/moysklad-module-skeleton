<?php

namespace App\Entity;

use App\Repository\StickerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StickerRepository::class)]
class Sticker
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $trackNumber = null;

    #[ORM\Column(length: 255)]
    private ?string $moyskladOrderId = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column(length: 255)]
    private ?string $filename = null;

    #[ORM\ManyToOne(inversedBy: 'stickers')]
    private ?Connection $connection = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTrackNumber(): ?string
    {
        return $this->trackNumber;
    }

    public function setTrackNumber(string $trackNumber): static
    {
        $this->trackNumber = $trackNumber;

        return $this;
    }

    public function getMoyskladOrderId(): ?string
    {
        return $this->moyskladOrderId;
    }

    public function setMoyskladOrderId(string $moyskladOrderId): static
    {
        $this->moyskladOrderId = $moyskladOrderId;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): static
    {
        $this->filename = $filename;

        return $this;
    }

    public function getConnection(): ?Connection
    {
        return $this->connection;
    }

    public function setConnection(?Connection $connection): static
    {
        $this->connection = $connection;

        return $this;
    }
}

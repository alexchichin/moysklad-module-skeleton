<?php

namespace App\Entity;

use App\Repository\ConnectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConnectionRepository::class)]
class Connection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(nullable: true)]
    private ?array $credentials = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'connections')]
    private ?AccountApplication $accountApplication = null;

    /**
     * @var Collection<int, Sticker>
     */
    #[ORM\OneToMany(targetEntity: Sticker::class, mappedBy: 'connection')]
    private Collection $stickers;

    public function __construct()
    {
        $this->stickers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getCredentials(): ?array
    {
        return $this->credentials;
    }

    public function setCredentials(?array $credentials): static
    {
        $this->credentials = $credentials;

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

    public function getAccountApplication(): ?AccountApplication
    {
        return $this->accountApplication;
    }

    public function setAccountApplication(?AccountApplication $accountApplication): static
    {
        $this->accountApplication = $accountApplication;

        return $this;
    }

    /**
     * @return Collection<int, Sticker>
     */
    public function getStickers(): Collection
    {
        return $this->stickers;
    }

    public function addSticker(Sticker $sticker): static
    {
        if (!$this->stickers->contains($sticker)) {
            $this->stickers->add($sticker);
            $sticker->setConnection($this);
        }

        return $this;
    }

    public function removeSticker(Sticker $sticker): static
    {
        if ($this->stickers->removeElement($sticker)) {
            // set the owning side to null (unless already changed)
            if ($sticker->getConnection() === $this) {
                $sticker->setConnection(null);
            }
        }

        return $this;
    }
}

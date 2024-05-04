<?php

namespace App\Entity;

use App\Repository\AccountApplicationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AccountApplicationRepository::class)]
class AccountApplication
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $appId = null;

    #[ORM\Column(length: 255)]
    private ?string $appUid = null;

    #[ORM\Column(length: 255)]
    private ?string $accountId = null;

    #[ORM\Column(length: 255)]
    private ?string $accountName = null;

    #[ORM\Column(length: 255)]
    private ?string $cause = null;

    #[ORM\Column(nullable: true)]
    private ?array $access = null;

    #[ORM\Column]
    private array $subscription = [];

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    /**
     * @var Collection<int, Connection>
     */
    #[ORM\OneToMany(targetEntity: Connection::class, mappedBy: 'accountApplication')]
    private Collection $connections;

    public function __construct()
    {
        $this->connections = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAppUid(): ?string
    {
        return $this->appUid;
    }

    public function setAppUid(string $appUid): static
    {
        $this->appUid = $appUid;

        return $this;
    }

    public function getAccountName(): ?string
    {
        return $this->accountName;
    }

    public function setAccountName(string $accountName): static
    {
        $this->accountName = $accountName;

        return $this;
    }

    public function getCause(): ?string
    {
        return $this->cause;
    }

    public function setCause(string $cause): static
    {
        $this->cause = $cause;

        return $this;
    }

    public function getAccess(): ?array
    {
        return $this->access;
    }

    public function setAccess(?array $access): static
    {
        $this->access = $access;

        return $this;
    }

    public function getSubscription(): array
    {
        return $this->subscription;
    }

    public function setSubscription(array $subscription): static
    {
        $this->subscription = $subscription;

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

    /**
     * @return Collection<int, Connection>
     */
    public function getConnections(): Collection
    {
        return $this->connections;
    }

    public function addConnection(Connection $connection): static
    {
        if (!$this->connections->contains($connection)) {
            $this->connections->add($connection);
            $connection->setAccountApplication($this);
        }

        return $this;
    }

    public function removeConnection(Connection $connection): static
    {
        if ($this->connections->removeElement($connection)) {
            // set the owning side to null (unless already changed)
            if ($connection->getAccountApplication() === $this) {
                $connection->setAccountApplication(null);
            }
        }

        return $this;
    }

    public function getAppId(): ?string
    {
        return $this->appId;
    }

    public function setAppId(string $appId): static
    {
        $this->appId = $appId;

        return $this;
    }

    public function getAccountId(): ?string
    {
        return $this->accountId;
    }

    public function setAccountId(string $accountId): static
    {
        $this->accountId = $accountId;

        return $this;
    }
}

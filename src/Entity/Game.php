<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $finishedAt = null;

    #[ORM\Column]
    private ?int $Player1Id = null;

    #[ORM\Column(nullable: true)]
    private ?int $Player2Id = null;

    #[ORM\Column(nullable: true)]
    private ?int $winnerId = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\OneToMany(mappedBy: 'Game', targetEntity: Tile::class, orphanRemoval: true)]
    private Collection $Tiles;

    public function __construct()
    {
        $this->Tiles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getFinishedAt(): ?\DateTimeImmutable
    {
        return $this->finishedAt;
    }

    public function setFinishedAt(?\DateTimeImmutable $finishedAt): self
    {
        $this->finishedAt = $finishedAt;

        return $this;
    }

    public function getPlayer1Id(): ?int
    {
        return $this->Player1Id;
    }

    public function setPlayer1Id(int $Player1Id): self
    {
        $this->Player1Id = $Player1Id;

        return $this;
    }

    public function getPlayer2Id(): ?int
    {
        return $this->Player2Id;
    }

    public function setPlayer2Id(?int $Player2Id): self
    {
        $this->Player2Id = $Player2Id;

        return $this;
    }

    public function getWinnerId(): ?int
    {
        return $this->winnerId;
    }

    public function setWinnerId(?int $winnerId): self
    {
        $this->winnerId = $winnerId;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, Tile>
     */
    public function getTiles(): Collection
    {
        return $this->Tiles;
    }

    public function addTile(Tile $tile): self
    {
        if (!$this->Tiles->contains($tile)) {
            $this->Tiles->add($tile);
            $tile->setGame($this);
        }

        return $this;
    }

    public function removeTile(Tile $tile): self
    {
        if ($this->Tiles->removeElement($tile)) {
            // set the owning side to null (unless already changed)
            if ($tile->getGame() === $this) {
                $tile->setGame(null);
            }
        }

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\GameRepository;
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
}

<?php

namespace App\Entity;

use App\Repository\DefineRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DefineRepository::class)]
class Define
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $capaMax = null;

    #[ORM\Column]
    private ?int $prix = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCapaMax(): ?int
    {
        return $this->capaMax;
    }

    public function setCapaMax(int $capaMax): static
    {
        $this->capaMax = $capaMax;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): static
    {
        $this->prix = $prix;

        return $this;
    }
}

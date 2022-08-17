<?php

namespace App\Entity;

use App\Repository\MedicoRepository;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: MedicoRepository::class)]
class Medico implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $crm = null;

    #[ORM\Column(length: 255)]
    private ?string $nomeMedico = null;

    #[ORM\ManyToOne(inversedBy: 'medicos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Especialidade $especialidade = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCrm(): ?int
    {
        return $this->crm;
    }

    public function getNomeMedico(): ?string
    {
        return $this->nomeMedico;
    }

    public function getEspecialidade(): ?Especialidade
    {
        return $this->especialidade;
    }

    public function setCrm(int $crm): self
    {
        $this->crm = $crm;
        return $this;
    }

    public function setNomeMedico(string $nome): self
    {
        $this->nomeMedico = $nome;
        return $this;
    }

    public function setEspecialidade(?Especialidade $especialidade): self
    {
        $this->especialidade = $especialidade;

        return $this;
    }

    public function jsonSerialize(): mixed
    {
        return [
            "id" => $this->getId(),
            "crm" => $this->getCrm(),
            "nome" => $this->getNomeMedico(),
            "especialidadeId" => $this->getEspecialidade()->getId(),
            "especialidade" => $this->getEspecialidade()->getDescricao()
        ];
    }
}

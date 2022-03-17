<?php

namespace App\Entity;

use App\Repository\EmpresaRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EmpresaRepository::class)]
#[UniqueEntity(fields: ['name'], message: 'Ya existe una Empresa con ese nombre')]
class Empresa
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    /**
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="El nombre no puede contener número"
     * )
     */
    private $name;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function __toString() {
        return $this->name;
    }
}

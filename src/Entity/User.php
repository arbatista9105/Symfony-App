<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource]
#[UniqueEntity(fields: ['email'], message: 'Ya existe una cuenta de usuario con dicho correo')]
#[UniqueEntity(fields: ['dni'], message: 'Ya existe una cuenta de usuario con dicho carnet de identidad')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{

   const SUCCESS = 'Se ha registrado exitosamente en el sistema';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    /**
     * @Assert\Regex(
     *     pattern="/^(?=.{7,}$)(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?\W).*$/",
     *     message="La contraseña debe al menos 1 mayúscula, 1 minúscula, 1 dígito, 1 carácter especial y tiene una longitud de al menos 7"
     * )
     */
    private $password;

    #[ORM\Column(type: 'string')]
    /**
     * @Assert\Regex(
     *     pattern="/^(?=.{7,}$)(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?\W).*$/",
     *     message="La contraseña debe al menos 1 mayúscula, 1 minúscula, 1 dígito, 1 carácter especial y tiene una longitud de al menos 7"
     * )
     */
    private $password_confirm;

    /**
     * @return mixed
     */
    public function getPasswordConfirm()
    {
        return $this->password_confirm;
    }

    /**
     * @param mixed $password_confirm
     */
    public function setPasswordConfirm($password_confirm)
    {
        $this->password_confirm = $password_confirm;
    }

    //mias
    #[ORM\Column(type: 'string')]
    /**
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="El nombre no puede contener número"
     * )
     */
    private $name;

    #[ORM\Column(type: 'string')]
    /**
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="Los apellidos no puede contener número"
     * )
     */
    private $lastname;

    #[ORM\Column(type: 'string',length: 11, unique: true)]
    private $dni;

    #[ORM\Column(type: 'string')]
    /**
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="El país no puede contener número"
     * )
     */
    private $country;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;


    #[ORM\ManyToOne(targetEntity: Banco::class)]
    #[ORM\JoinColumn(nullable: true,referencedColumnName: 'id')]
    private $banco;

    #[ORM\ManyToOne(targetEntity: Empresa::class)]
    #[ORM\JoinColumn(nullable: true,referencedColumnName: 'id')]
    private $empresa;
    /**
     * @return mixed
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * @param mixed $empresa
     */
    public function setEmpresa($empresa)
    {
        $this->empresa = $empresa;
    }

    /**
     * @return mixed
     */
    public function getBanco()
    {
        return $this->banco;
    }

    /**
     * @param mixed $banco
     */
    public function setBanco($banco)
    {
        $this->banco = $banco;
    }


    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return mixed
     */
    public function getDni()
    {
        return $this->dni;
    }

    /**
     * @param mixed $dni
     */
    public function setDni($dni)
    {
        $this->dni = $dni;
    }

    /**
     * @return array
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param array $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getUserName(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }
}

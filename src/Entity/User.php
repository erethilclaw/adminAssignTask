<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"pickCode"}, message="codi pick en ùs")
 */
class User implements UserInterface
{
    const ROLE_ADMIN = "ROLE_ADMIN";
    const ROLE_USER = "ROLE_USER";
    const ROLE_INACTIVE = 'ROLE_INACTIVE';

    const DEFAULT_ROLES = [self::ROLE_USER];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message="no pot estar buit")
     * @Assert\Regex(
     *     pattern="/\d+/",
     *     message="pickCode només pot ser nùmeric")
     */
    private $pickCode;

    /**
     * @ORM\Column(type="json", length=200)
     * @Assert\NotBlank(message="Escull un rol")
     *
     */
    private $roles;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\Regex(
     *     pattern="/(?=.*[A-Z]|[!^s])(?=.*[0-9]|[!^s]).{7,}/",
     *     message="min: 7 caràcters, 1 nùmeric, 1 majùscula"
     * )
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $surenames;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Ordre", inversedBy="users")
     */
    private $ordres;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Butlleti", mappedBy="user")
     */
    private $butlletins;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\LiniaButlleti", mappedBy="user")
     */
    private $liniesButlleti;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPickCode(): ?string
    {
        return $this->pickCode;
    }

    public function setPickCode(string $pickCode): self
    {
        $this->pickCode = $pickCode;

        return $this;
    }

    public function __construct() {
        $this->roles = self::DEFAULT_ROLES;
        $this->ordres = new ArrayCollection();
        $this->butlletins = new ArrayCollection();
        $this->liniesButlleti = new ArrayCollection();
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->pickCode;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getSurenames(): ?string
    {
        return $this->surenames;
    }

    public function setSurenames(string $surenames): self
    {
        $this->surenames = $surenames;

        return $this;
    }

    /**
     * @return Collection|Ordre[]
     */
    public function getOrdres(): Collection
    {
        return $this->ordres;
    }

    public function addOrdre(Ordre $ordre): self
    {
        if (!$this->ordres->contains($ordre)) {
            $this->ordres[] = $ordre;
        }

        return $this;
    }

    public function removeOrdre(Ordre $ordre): self
    {
        if ($this->ordres->contains($ordre)) {
            $this->ordres->removeElement($ordre);
        }

        return $this;
    }

    /**
     * @return Collection|Butlleti[]
     */
    public function getButlletins(): Collection
    {
        return $this->butlletins;
    }

    public function addButlletin(Butlleti $butlletin): self
    {
        if (!$this->butlletins->contains($butlletin)) {
            $this->butlletins[] = $butlletin;
            $butlletin->setUser($this);
        }

        return $this;
    }

    public function removeButlletin(Butlleti $butlletin): self
    {
        if ($this->butlletins->contains($butlletin)) {
            $this->butlletins->removeElement($butlletin);
            // set the owning side to null (unless already changed)
            if ($butlletin->getUser() === $this) {
                $butlletin->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|LiniaButlleti[]
     */
    public function getLiniesButlleti(): Collection
    {
        return $this->liniesButlleti;
    }

    public function addLiniesButlleti(LiniaButlleti $liniesButlleti): self
    {
        if (!$this->liniesButlleti->contains($liniesButlleti)) {
            $this->liniesButlleti[] = $liniesButlleti;
            $liniesButlleti->setUser($this);
        }

        return $this;
    }

    public function removeLiniesButlleti(LiniaButlleti $liniesButlleti): self
    {
        if ($this->liniesButlleti->contains($liniesButlleti)) {
            $this->liniesButlleti->removeElement($liniesButlleti);
            // set the owning side to null (unless already changed)
            if ($liniesButlleti->getUser() === $this) {
                $liniesButlleti->setUser(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getPickCode();
    }
}

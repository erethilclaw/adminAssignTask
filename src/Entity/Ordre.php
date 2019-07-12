<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrdreRepository")
 */
class Ordre
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $codiOrdre;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $estat;

    /**
     * @ORM\Column(type="date")
     */
    private $obertura;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $descripcio;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="ordres")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\LiniaButlleti", mappedBy="ordre")
     */
    private $liniesButlleti;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->liniesButlleti = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodiOrdre(): ?string
    {
        return $this->codiOrdre;
    }

    public function setCodiOrdre(string $codiOrdre): self
    {
        $this->codiOrdre = $codiOrdre;

        return $this;
    }

    public function getEstat(): ?string
    {
        return $this->estat;
    }

    public function setEstat(?string $estat): self
    {
        $this->estat = $estat;

        return $this;
    }

    public function getObertura(): ?\DateTimeInterface
    {
        return $this->obertura;
    }

    public function setObertura(\DateTimeInterface $obertura): self
    {
        $this->obertura = $obertura;

        return $this;
    }

    public function getDescripcio(): ?string
    {
        return $this->descripcio;
    }

    public function setDescripcio(?string $descripcio): self
    {
        $this->descripcio = $descripcio;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addOrdre($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeOrdre($this);
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
            $liniesButlleti->setOrdre($this);
        }

        return $this;
    }

    public function removeLiniesButlleti(LiniaButlleti $liniesButlleti): self
    {
        if ($this->liniesButlleti->contains($liniesButlleti)) {
            $this->liniesButlleti->removeElement($liniesButlleti);
            // set the owning side to null (unless already changed)
            if ($liniesButlleti->getOrdre() === $this) {
                $liniesButlleti->setOrdre(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getCodiOrdre();
    }


}

<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LiniaButlletiRepository")
 */
class LiniaButlleti
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $hores;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $observacions;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creat;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modificat;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="liniesButlleti")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ordre", inversedBy="liniesButlleti")
     */
    private $ordre;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Butlleti", inversedBy="liniesButlleti")
     */
    private $butlleti;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHores(): ?float
    {
        return $this->hores;
    }

    public function setHores(float $hores): self
    {
        $this->hores = $hores;

        return $this;
    }

    public function getObservacions(): ?string
    {
        return $this->observacions;
    }

    public function setObservacions(?string $observacions): self
    {
        $this->observacions = $observacions;

        return $this;
    }

    public function getCreat(): ?\DateTimeInterface
    {
        return $this->creat;
    }

    public function setCreat(\DateTimeInterface $creat): self
    {
        $this->creat = $creat;

        return $this;
    }

    public function getModificat(): ?\DateTimeInterface
    {
        return $this->modificat;
    }

    public function setModificat(?\DateTimeInterface $modificat): self
    {
        $this->modificat = $modificat;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getOrdre(): ?Ordre
    {
        return $this->ordre;
    }

    public function setOrdre(?Ordre $ordre): self
    {
        $this->ordre = $ordre;

        return $this;
    }

    public function getButlleti(): ?Butlleti
    {
        return $this->butlleti;
    }

    public function setButlleti(?Butlleti $butlleti): self
    {
        $this->butlleti = $butlleti;

        return $this;
    }

    public function __toString()
    {
        return $this->getObservacions();
    }
}

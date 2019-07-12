<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ButlletiRepository")
 * @UniqueEntity(fields={"dataButlleti","user"},
 *     errorPath="dataButlleti",
 *     message="Ja has creat un butlleti amb aquesta data"
 * )
 */
class Butlleti
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dataButlleti;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creat;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modificat;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="butlletins")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\LiniaButlleti", mappedBy="butlleti", cascade={"persist"})
     */
    private $liniesButlleti;

    public function __construct()
    {
        $this->liniesButlleti = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDataButlleti(): ?\DateTimeInterface
    {
        return $this->dataButlleti;
    }

    public function setDataButlleti(\DateTimeInterface $dataButlleti): self
    {
        $this->dataButlleti = $dataButlleti;

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

    /**
     * @return Collection|LiniaButlleti[]
     */
    public function getLiniesButlleti(): Collection
    {
        return $this->liniesButlleti;
    }

    public function addLiniesButlletus(LiniaButlleti $liniesButlleti): self
    {
        if (!$this->liniesButlleti->contains($liniesButlleti)) {
            $this->liniesButlleti[] = $liniesButlleti;
            $liniesButlleti->setButlleti($this);
        }

        return $this;
    }

    public function removeLiniesButlletus(LiniaButlleti $liniesButlleti): self
    {
        if ($this->liniesButlleti->contains($liniesButlleti)) {
            $this->liniesButlleti->removeElement($liniesButlleti);
            // set the owning side to null (unless already changed)
            if ($liniesButlleti->getButlleti() === $this) {
                $liniesButlleti->setButlleti(null);
            }
        }

        return $this;
    }

    /**
     * @Assert\Callback()
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        $created = $this->getDataButlleti();
        $today = new \DateTime();
        $timeInterval = $today->diff($created);
        $days = $timeInterval->d;
        if (intval($days) >= 7 || intval($days) < 0){
            $context->buildViolation('no es poden crear/modificar butlletins de fa una setmana o futurs')
                ->atPath('dataButlleti')
                ->addViolation();
        }
    }
}

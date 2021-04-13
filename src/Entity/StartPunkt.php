<?php

namespace App\Entity;

use App\Repository\StartPunktRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StartPunktRepository::class)
 */
class StartPunkt
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $iframe;

    /**
     * @ORM\OneToMany(targetEntity=Person::class, mappedBy="startPunkt", orphanRemoval=true)
     */
    private $personen;

    public function __construct()
    {
        $this->personen = new ArrayCollection();
    }

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

    public function getIframe(): ?string
    {
        return $this->iframe;
    }

    public function setIframe(string $iframe): self
    {
        $this->iframe = $iframe;

        return $this;
    }

    /**
     * @return Collection|Person[]
     */
    public function getPersonen(): Collection
    {
        return $this->personen;
    }

    public function addPersonen(Person $personen): self
    {
        if (!$this->personen->contains($personen)) {
            $this->personen[] = $personen;
            $personen->setStartPunkt($this);
        }

        return $this;
    }

    public function removePersonen(Person $personen): self
    {
        if ($this->personen->removeElement($personen)) {
            // set the owning side to null (unless already changed)
            if ($personen->getStartPunkt() === $this) {
                $personen->setStartPunkt(null);
            }
        }

        return $this;
    }
}

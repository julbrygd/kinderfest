<?php

namespace App\Entity;

use App\Repository\RegistrationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RegistrationRepository::class)
 */
class Registration
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="uuid")
     */
    private $uuid;

    /**
     * @ORM\OneToMany(targetEntity=Person::class, mappedBy="registration", orphanRemoval=true)
     */
    private $persons;

    /**
     * @ORM\ManyToOne(targetEntity=StartZeit::class, inversedBy="registrations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $startZeit;

    /**
     * @ORM\ManyToOne(targetEntity=StartPunkt::class, inversedBy="registrations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $startPunk;

    public function __construct()
    {
        $this->persons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    public function setUuid($uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * @return Collection|Person[]
     */
    public function getPersons(): Collection
    {
        return $this->persons;
    }

    public function addPerson(Person $person): self
    {
        if (!$this->persons->contains($person)) {
            $this->persons[] = $person;
            $person->setRegistration($this);
        }

        return $this;
    }

    public function removePerson(Person $person): self
    {
        if ($this->persons->removeElement($person)) {
            // set the owning side to null (unless already changed)
            if ($person->getRegistration() === $this) {
                $person->setRegistration(null);
            }
        }

        return $this;
    }

    public function getStartZeit(): ?StartZeit
    {
        return $this->startZeit;
    }

    public function setStartZeit(?StartZeit $startZeit): self
    {
        $this->startZeit = $startZeit;

        return $this;
    }

    public function getStartPunk(): ?StartPunkt
    {
        return $this->startPunk;
    }

    public function setStartPunk(?StartPunkt $startPunk): self
    {
        $this->startPunk = $startPunk;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\StartZeitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StartZeitRepository::class)
 */
class StartZeit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="time")
     */
    private $zeit;

    /**
     * @ORM\Column(type="integer")
     */
    private $max_personen;

    /**
     * @ORM\OneToMany(targetEntity=Person::class, mappedBy="start_zeit")
     */
    private $people;

    /**
     * @ORM\OneToMany(targetEntity=Registration::class, mappedBy="startZeit")
     */
    private $registrations;

    public function __construct()
    {
        $this->people = new ArrayCollection();
        $this->registrations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getZeit(): ?\DateTimeInterface
    {
        return $this->zeit;
    }

    public function setZeit(\DateTimeInterface $zeit): self
    {
        $this->zeit = $zeit;

        return $this;
    }

    public function getMaxPersonen(): ?int
    {
        return $this->max_personen;
    }

    public function setMaxPersonen(int $max_personen): self
    {
        $this->max_personen = $max_personen;

        return $this;
    }

    /**
     * @return Collection|Person[]
     */
    public function getPeople(): Collection
    {
        return $this->people;
    }

    public function addPerson(Person $person): self
    {
        if (!$this->people->contains($person)) {
            $this->people[] = $person;
            $person->setStartZeit($this);
        }

        return $this;
    }

    public function removePerson(Person $person): self
    {
        if ($this->people->removeElement($person)) {
            // set the owning side to null (unless already changed)
            if ($person->getStartZeit() === $this) {
                $person->setStartZeit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Registration[]
     */
    public function getRegistrations(): Collection
    {
        return $this->registrations;
    }

    public function addRegistration(Registration $registration): self
    {
        if (!$this->registrations->contains($registration)) {
            $this->registrations[] = $registration;
            $registration->setStartZeit($this);
        }

        return $this;
    }

    public function removeRegistration(Registration $registration): self
    {
        if ($this->registrations->removeElement($registration)) {
            // set the owning side to null (unless already changed)
            if ($registration->getStartZeit() === $this) {
                $registration->setStartZeit(null);
            }
        }

        return $this;
    }
}

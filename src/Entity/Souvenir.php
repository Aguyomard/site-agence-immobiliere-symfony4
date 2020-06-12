<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SouvenirRepository")
 */
class Souvenir
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $resume;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $anecdote;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="souvenirs")
     */
    private $partenaire;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Booking", inversedBy="souvenir", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $booking;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Photo", mappedBy="ad", orphanRemoval=true)
     * @Assert\Valid()
     */
    private $photos;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
        $this->partenaire = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getResume(): ?string
    {
        return $this->resume;
    }

    public function setResume(string $resume): self
    {
        $this->resume = $resume;

        return $this;
    }

    public function getAnecdote(): ?string
    {
        return $this->anecdote;
    }

    public function setAnecdote(?string $anecdote): self
    {
        $this->anecdote = $anecdote;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getPartenaire(): Collection
    {
        return $this->partenaire;
    }

    public function addPartenaire(User $partenaire): self
    {
        if (!$this->partenaire->contains($partenaire)) {
            $this->partenaire[] = $partenaire;
        }

        return $this;
    }

    public function removePartenaire(User $partenaire): self
    {
        if ($this->partenaire->contains($partenaire)) {
            $this->partenaire->removeElement($partenaire);
        }

        return $this;
    }

    public function getBooking(): ?Booking
    {
        return $this->booking;
    }

    public function setBooking(Booking $booking): self
    {
        $this->booking = $booking;

        return $this;
    }

    /**
     * @return Collection|Photo[]
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
            $photo->setSouvenirId($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): self
    {
        if ($this->photos->contains($photo)) {
            $this->photos->removeElement($photo);
            // set the owning side to null (unless already changed)
            if ($photo->getSouvenirId() === $this) {
                $photo->setSouvenirId(null);
            }
        }

        return $this;
    }

}

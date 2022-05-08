<?php

namespace App\Entity;

use App\Repository\CarParksRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CarParksRepository::class)
 */
class CarParks
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
     * @ORM\Column(type="string", length=255)
     */
    private $telephone;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * @ORM\OneToMany(targetEntity=Spaces::class, mappedBy="CarParkId", orphanRemoval=true)
     */
    private $Spaces;

    /**
     * @ORM\OneToMany(targetEntity=Bookings::class, mappedBy="spaceId", orphanRemoval=true)
     */
    private $Bookings;

    /**
     * @ORM\OneToMany(targetEntity=Prices::class, mappedBy="CarParkId", orphanRemoval=true)
     */
    private $Prices;

    public function __construct()
    {
        $this->Spaces = new ArrayCollection();
        $this->Prices = new ArrayCollection();
        $this->Bookings = new ArrayCollection();
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

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @return Collection<int, Spaces>
     */
    public function getSpaces(): Collection
    {
        return $this->Spaces;
    }

    public function addSpace(Spaces $space): self
    {
        if (!$this->Spaces->contains($space)) {
            $this->Spaces[] = $space;
            $space->setCarParkId($this);
        }

        return $this;
    }

    public function removeSpace(Spaces $space): self
    {
        if ($this->Spaces->removeElement($space)) {
            // set the owning side to null (unless already changed)
            if ($space->getCarParkId() === $this) {
                $space->setCarParkId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Prices>
     */
    public function getPrices(): Collection
    {
        return $this->Prices;
    }

    public function addPrice(Prices $price): self
    {
        if (!$this->Prices->contains($price)) {
            $this->Prices[] = $price;
            $price->setCarParkId($this);
        }

        return $this;
    }

    public function removePrice(Prices $price): self
    {
        if ($this->Prices->removeElement($price)) {
            // set the owning side to null (unless already changed)
            if ($price->getCarParkId() === $this) {
                $price->setCarParkId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Bookings>
     */
    public function getBookings(): Collection
    {
        return $this->Bookings;
    }

    public function addBooking(Bookings $booking): self
    {
        if (!$this->Bookings->contains($booking)) {
            $this->Bookings[] = $booking;
            $booking->setCarParkId($this);
        }

        return $this;
    }

    public function removeBooking(Bookings $booking): self
    {
        if ($this->Bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getCarParkId() === $this) {
                $booking->setCarParkId(null);
            }
        }

        return $this;
    }
}

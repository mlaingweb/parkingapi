<?php

namespace App\Entity;

use App\Repository\SpacesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SpacesRepository::class)
 */
class Spaces
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=CarParks::class, inversedBy="Spaces")
     * @ORM\JoinColumn(nullable=false)
     */
    private $CarParkId;

    public function __construct()
    {
        $this->Bookings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCarParkId(): ?CarParks
    {
        return $this->CarParkId;
    }

    public function setCarParkId(?CarParks $CarParkId): self
    {
        $this->CarParkId = $CarParkId;

        return $this;
    }
}

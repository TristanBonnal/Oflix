<?php

namespace App\Entity;

use App\Repository\SeasonRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SeasonRepository::class)
 */
class Season
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message = "Veuillez renseigner un numéro de saison")
     */
    private $number;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message = "Veuillez renseigner un nomdre d'épisodes")
     */
    private $episodesNumber;

    /**
     * inversedBy="seasons" référence la propriété dans l'autre classe (Movie)
     * 
     * @ORM\ManyToOne(targetEntity=Movie::class, inversedBy="seasons")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull(message = "Veuillez renseigner une série")
     */
    private $movie;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getEpisodesNumber(): ?int
    {
        return $this->episodesNumber;
    }

    public function setEpisodesNumber(int $episodesNumber): self
    {
        $this->episodesNumber = $episodesNumber;

        return $this;
    }

    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    public function setMovie(?Movie $movie): self
    {
        $this->movie = $movie;

        return $this;
    }
}

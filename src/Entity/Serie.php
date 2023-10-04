<?php

namespace App\Entity;

use App\Repository\SerieRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SerieRepository::class)]
class Serie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $overview = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 1, nullable: true)]
    private ?string $popularity = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 3, scale: 1, nullable: true)]
    private ?string $vote = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $genres = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $firstAirDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $lastAirDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $backdrop = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $poster = null;

    #[ORM\Column(nullable: true)]
    private ?int $tmdbId = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getOverview(): ?string
    {
        return $this->overview;
    }

    /**
     * @param string|null $overview
     */
    public function setOverview(?string $overview): void
    {
        $this->overview = $overview;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string|null $status
     */
    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return string|null
     */
    public function getPopularity(): ?string
    {
        return $this->popularity;
    }

    /**
     * @param string|null $popularity
     */
    public function setPopularity(?string $popularity): void
    {
        $this->popularity = $popularity;
    }

    /**
     * @return string|null
     */
    public function getVote(): ?string
    {
        return $this->vote;
    }

    /**
     * @param string|null $vote
     */
    public function setVote(?string $vote): void
    {
        $this->vote = $vote;
    }

    /**
     * @return string|null
     */
    public function getGenres(): ?string
    {
        return $this->genres;
    }

    /**
     * @param string|null $genres
     */
    public function setGenres(?string $genres): void
    {
        $this->genres = $genres;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getFirstAirDate(): ?\DateTimeInterface
    {
        return $this->firstAirDate;
    }

    /**
     * @param \DateTimeInterface|null $firstAirDate
     */
    public function setFirstAirDate(?\DateTimeInterface $firstAirDate): void
    {
        $this->firstAirDate = $firstAirDate;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getLastAirDate(): ?\DateTimeInterface
    {
        return $this->lastAirDate;
    }

    /**
     * @param \DateTimeInterface|null $lastAirDate
     */
    public function setLastAirDate(?\DateTimeInterface $lastAirDate): void
    {
        $this->lastAirDate = $lastAirDate;
    }

    /**
     * @return string|null
     */
    public function getBackdrop(): ?string
    {
        return $this->backdrop;
    }

    /**
     * @param string|null $backdrop
     */
    public function setBackdrop(?string $backdrop): void
    {
        $this->backdrop = $backdrop;
    }

    /**
     * @return string|null
     */
    public function getPoster(): ?string
    {
        return $this->poster;
    }

    /**
     * @param string|null $poster
     */
    public function setPoster(?string $poster): void
    {
        $this->poster = $poster;
    }

    /**
     * @return int|null
     */
    public function getTmdbId(): ?int
    {
        return $this->tmdbId;
    }

    /**
     * @param int|null $tmdbId
     */
    public function setTmdbId(?int $tmdbId): void
    {
        $this->tmdbId = $tmdbId;
    }
}
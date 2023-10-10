<?php

namespace App\Entity;

use App\Repository\SerieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SerieRepository::class)]
#[UniqueEntity('name', 'Ce nom existe déja')]
#[ORM\HasLifecycleCallbacks]
class Serie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank([], 'Ce champs ne peut pas etre vide')]
    #[Assert\Length(min: 3, minMessage: 'Le nom doit avoir au moins {{ limit }} caractères')]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $overview = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 1, nullable: true)]
    private ?string $popularity = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 3, scale: 1, nullable: true)]
    #[Assert\Range(min: 0, max:10, notInRangeMessage: 'Ca doit etre entre {{ min }} et {{ max }}')]
    private ?string $vote = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $genres = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\LessThan(propertyPath: 'lastAirDate', message: 'Postérieur à la fin ?')]
    private ?\DateTimeInterface $firstAirDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Assert\GreaterThan(propertyPath: 'firstAirDate', message: 'Antérieur à la fin, sérieux ???')]
    private ?\DateTimeInterface $lastAirDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $backdrop = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $poster = null;

    #[ORM\Column(nullable: true)]
    private ?int $tmdbId = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCreated = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateModified = null;

    private string $posterPath = 'uploads/posters/series/';

    #[ORM\OneToMany(mappedBy: 'serie', targetEntity: Season::class, orphanRemoval: false)]
    private Collection $seasons;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'series')]
    private Collection $categories;

    public function __construct()
    {
        $this->seasons = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

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

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->dateCreated;
    }


    #[ORM\PrePersist]
    public function setDateCreated(): static
    {
        $this->dateCreated = new \DateTime();

        return $this;
    }

    public function getDateModified(): ?\DateTimeInterface
    {
        return $this->dateModified;
    }

    #[ORM\PreUpdate]
    public function setDateModified(): static
    {
        $this->dateModified = new \DateTime();

        return $this;
    }

    #[ORM\PostRemove]
    public function removePicture(): void
    {
        if ($this->poster && file_exists($this->posterPath . $this->poster)) {
            unlink($this->posterPath . $this->poster);
        }
    }

    /**
     * @return Collection<int, Season>
     */
    public function getSeasons(): Collection
    {
        return $this->seasons;
    }

    public function addSeason(Season $season): static
    {
        if (!$this->seasons->contains($season)) {
            $this->seasons->add($season);
            $season->setSerie($this);
        }

        return $this;
    }

    public function removeSeason(Season $season): static
    {
        if ($this->seasons->removeElement($season)) {
            // set the owning side to null (unless already changed)
            if ($season->getSerie() === $this) {
                $season->setSerie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        $this->categories->removeElement($category);

        return $this;
    }
}
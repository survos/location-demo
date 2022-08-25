<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */
class Article
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=80, nullable=false)
     */
    private ?string $title = null;

    /**
     * @ORM\Column(type="array", nullable=true)
     * @var mixed|mixed[]|null
     */
    private $locations = [];

    /**
     * @ORM\Column(type="array", nullable=true)
     * @var mixed|mixed[]|null
     */
    private $countries = [];

    /**
     * @ORM\Column(type="array", nullable=true)
     * @var mixed|mixed[]|null
     */
    private $states = [];

    /**
     * @ORM\Column(type="array", nullable=true)
     * @var mixed|mixed[]|null
     */
    private $cities = [];

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $locationScope = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $slug=null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getLocations(): ?array
    {
        return $this->locations;
    }

    public function setLocations(?array $locations): self
    {
        $this->locations = $locations;

        return $this;
    }

    public function getCountries(): ?array
    {
        return $this->countries;
    }

    public function setCountries(?array $countries): self
    {
        if ($countries) {
//            dd($countries);
            assert(!is_object($countries[0]), "needs an non-object here");
        }
        $this->countries = $countries;

        return $this;
    }

    public function getStates(): ?array
    {
        return $this->states;
    }

    public function setStates(?array $states): self
    {
        $this->states = $states;

        return $this;
    }

    public function getCities(): ?array
    {
        return $this->cities;
    }

    public function setCities(?array $cities): self
    {
        $this->cities = $cities;

        return $this;
    }

    public function getLocationScope(): ?int
    {
        return $this->locationScope;
    }

    public function setLocationScope(?int $locationScope): self
    {
        $this->locationScope = $locationScope;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}

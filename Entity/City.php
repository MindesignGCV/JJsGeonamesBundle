<?php

namespace JJs\Bundle\GeonamesBundle\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JJs\Bundle\GeonamesBundle\Repository\CityRepository;

/**
 * City
 *
 * A city, town, village or other agglomeration of buildings where people work.
 *
 * @Entity(repositoryClass=CityRepository::class)
 * @Table(name="geo_city", indexes={
 *      @ORM\Index(name="geo_city_geoname_id", columns={"geoname_id"}),
 *      @ORM\Index(name="lat_lng", columns={"latitude", "longitude"}),
 * }))
 *
 * @author Josiah <josiah@jjs.id.au>
 */
class City extends Locality
{
    /**
     * @ManyToOne(targetEntity="State")
     */
    protected ?State $state = null;

    /**
     * @Gedmo\Slug(fields={"nameAscii"})
     * @ORM\Column(length=128, unique=true, nullable=true)
     */
    private ?string $slug = null;

    private ArrayCollection $relation;

    public function __construct() {
        parent::__construct();
        $this->relation = new ArrayCollection();
    }

    public function getState(): ?State
    {
        return $this->state;
    }

    public function setState(State $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function __toString() {
        return $this->getNameUtf8();
    }

    public function getGeopoint() {
        return $this->getLatitude() . ',' . $this->getLongitude();
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getRelation(): ArrayCollection
    {
        return $this->relation;
    }

    public function setRelation(ArrayCollection $relation): self
    {
        $this->relation = $relation;

        return $this;
    }
}

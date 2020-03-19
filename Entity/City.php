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
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JJs\Bundle\GeonamesBundle\Repository\CityRepository;
use Knp\DoctrineBehaviors\Contract\Entity\SluggableInterface;
use Knp\DoctrineBehaviors\Model\Sluggable\SluggableMethodsTrait;
use JMS\Serializer\Annotation as JMS;

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
 * @JMS\ExclusionPolicy("none")
 * @author Josiah <josiah@jjs.id.au>
 */
class City extends Locality implements SluggableInterface
{
    use SluggableMethodsTrait;

    /**
     * @ManyToOne(targetEntity="State")
     * @JMS\MaxDepth(3)
     */
    protected ?State $state = null;

    /**
     * @ORM\Column(length=128, nullable=false)
     */
    private string $slug = '';

    /**
     * @var ArrayCollection
     * @JMS\Type("ArrayCollection<JJs\Bundle\GeonamesBundle\Entity\Locality>")
     * @JMS\MaxDepth(3)
     */
    private $relation;

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

    public function getRelation(): ArrayCollection
    {
        return $this->relation;
    }

    public function setRelation(ArrayCollection $relation): self
    {
        $this->relation = $relation;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getSluggableFields(): array
    {
        return [
            empty($this->nameAscii) ? 'emptySlug': 'nameAscii'
        ];
    }

    public function getEmptySlug(): string
    {
        return '-';
    }
}

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
use JJs\Bundle\GeonamesBundle\Repository\StateRepository;
use Knp\DoctrineBehaviors\Contract\Entity\SluggableInterface;
use Knp\DoctrineBehaviors\Model\Sluggable\SluggableMethodsTrait;
use JMS\Serializer\Annotation as JMS;

/**
 * State
 *
 * The first level administrative division of a country. Refered to as a
 * 'province' for some countries.
 *
 * @JMS\ExclusionPolicy("none")
 * @Entity(repositoryClass=StateRepository::class)
 * @Table(name="geo_state", indexes={@ORM\Index(name="geo_state_geoname_id", columns={"geoname_id"})}))
 * @author Josiah <josiah@jjs.id.au>
 */
class State extends Locality implements SluggableInterface
{
    use SluggableMethodsTrait;

    /**
     * @ORM\Column(length=128, nullable=false)
     */
    private string $slug = '';

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

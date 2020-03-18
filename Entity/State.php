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
use JJs\Bundle\GeonamesBundle\Repository\StateRepository;

/**
 * State
 *
 * The first level administrative division of a country. Refered to as a
 * 'province' for some countries.
 *
 * @Entity(repositoryClass=StateRepository::class)
 * @Table(name="geo_state", indexes={@ORM\Index(name="geo_state_geoname_id", columns={"geoname_id"})}))
 * @author Josiah <josiah@jjs.id.au>
 */
class State extends Locality
{

    /**
     * @Gedmo\Slug(fields={"nameAscii"})
     * @ORM\Column(length=128, unique=true, nullable=true)
     */
    private ?string $slug = null;

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}

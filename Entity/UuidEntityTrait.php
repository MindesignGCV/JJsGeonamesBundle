<?php
declare(strict_types=1);

namespace JJs\Bundle\GeonamesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

trait UuidEntityTrait
{
    /**
     * @ORM\Id
     * @ORM\Column(
     *     type="guid",
     *     unique=true,
     *     nullable=false
     * )
     * @ORM\GeneratedValue(
     *     strategy="NONE"
     * )
     */
    private ?string $uuid = null;

    public function __construct()
    {
        $this->initUuid();
    }

    public function __clone()
    {
        $this->initUuid();
    }

    /**
     * @return static
     */
    public function initUuid()
    {
        $this->uuid = Uuid::uuid4()->toString();

        return $this;
    }

    /**
     * @psalm-ignore-nullable-return
     */
    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    /**
     * @return static
     */
    public function setUuid(string $uuid)
    {
        if (!Uuid::isValid($uuid)) {
            throw new \InvalidArgumentException(
                sprintf('The value "%s" is not valid uuid.', $uuid),
            );
        }

        $this->uuid = $uuid;

        return $this;
    }
}

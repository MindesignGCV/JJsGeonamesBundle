<?php declare(strict_types=1);

namespace JJs\Bundle\GeonamesBundle\Entity;

interface UuidEntity
{
    /**
     * @psalm-ignore-nullable-return
     */
    public function getUuid(): ?string;

    /** @return static */
    public function setUuid(string $uuid);

    /** @return static */
    public function initUuid();
}

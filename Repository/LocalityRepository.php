<?php

/**
 * Copyright (c) 2013 Josiah Truasheim
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace JJs\Bundle\GeonamesBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use JJs\Bundle\GeonamesBundle\Entity\Locality;
use JJs\Bundle\GeonamesBundle\Model\CountryRepositoryInterface;
use JJs\Bundle\GeonamesBundle\Model\LocalityInterface;
use JJs\Bundle\GeonamesBundle\Model\LocalityRepositoryInterface;
use JJs\Bundle\GeonamesBundle\Model\TimezoneRepositoryInterface;

/**
 * Locality Repository
 *
 * @author Josiah <josiah@jjs.id.au>
 */
abstract class LocalityRepository extends ServiceEntityRepository implements LocalityRepositoryInterface
{
    protected CountryRepositoryInterface $countryRepository;
    protected TimezoneRepositoryInterface $timezoneRepository;

    public function __construct(
        ManagerRegistry $registry,
        CountryRepositoryInterface $countryRepository,
        TimezoneRepositoryInterface $timezoneRepository,
        string $entityClass = Locality::class
    ) {
        parent::__construct($registry, $entityClass);
        $this->countryRepository = $countryRepository;
        $this->timezoneRepository = $timezoneRepository;
    }

    /**
     * Returns a locality from this repository which matches the data in the
     * specified locality
     * 
     * @param LocalityInterface $locality Locality instance
     * @return Locality
     */
    public function getLocality(LocalityInterface $locality)
    {
        $entityClass = $this->getClassName();

        if ($locality instanceof $entityClass) return $locality;

        return $this->findOneBy(['geonameIdentifier' => $locality->getGeonameIdentifier()]);
    }

    /**
     * Copies locality data from a locality interface to a locality entity
     * 
     * @param LocalityInterface $source      Source locality
     * @param Locality          $destination Destination locality
     */
    public function copyLocality(LocalityInterface $source, Locality $destination)
    {
        // Copy the geoname identifier
        if ($geonameIdentifier = $source->getGeonameIdentifier()) {
            $destination->setGeonameIdentifier($geonameIdentifier);
        }

        // Copy the country
        if ($country = $this->countryRepository->getCountry($source->getCountry())) {
            $destination->setCountry($country);
        }

        // Copy the UTF-8 encoded name
        if ($nameUtf8 = $source->getNameUtf8()) {
            $destination->setNameUtf8($nameUtf8);
        }

        // Copy the ASCII encoded name
        if ($nameAscii = $source->getNameAscii()) {
            $destination->setNameAscii($nameAscii);
        }

        // Copy the latitude
        $destination->setLatitude($source->getLatitude());

        // Copy the longitude
        $destination->setLongitude($source->getLongitude());


        // Copy the timezone
        if ($timezone = $this->timezoneRepository->getTimezone($source->getTimezone())) {
            $destination->setTimezone($timezone);
        }

        if ($admin1Code = $source->getAdmin1Code()) {
            $destination->setAdmin1Code($admin1Code);
        }
    }
}

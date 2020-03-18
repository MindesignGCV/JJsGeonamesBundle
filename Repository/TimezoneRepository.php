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
use JJs\Bundle\GeonamesBundle\Entity\Timezone;
use JJs\Bundle\GeonamesBundle\Model\CountryRepositoryInterface;
use JJs\Bundle\GeonamesBundle\Model\TimezoneInterface;
use JJs\Bundle\GeonamesBundle\Model\TimezoneRepositoryInterface;

/**
 * Timezone Entity Repository
 *
 * Maintains timezone mapping information in a relational database.
 *
 * @author Josiah <josiah@jjs.id.au>
 */
class TimezoneRepository extends ServiceEntityRepository implements TimezoneRepositoryInterface
{
    protected CountryRepositoryInterface $countryRepository;

    public function __construct(
        ManagerRegistry $registry,
        CountryRepositoryInterface $countryRepository
    ) {
        parent::__construct($registry, Timezone::class);
        $this->countryRepository = $countryRepository;
    }

    /**
     * Returns the country repository
     * 
     * @return CountryRepository
     */
    public function getCountryRepository()
    {
        return $this->countryRepository;
    }

    /**
     * Returns a timezone
     *
     * Note that the returned timezone will be resolved according to the design
     * of the implementing repository. This should include resolution of
     * interfaces to repository compatible classes.
     * 
     * @param string|TimezoneInterface $timezone Timezone Identifier
     * 
     * @return TimezoneInterface
     */
    public function getTimezone($timezone)
    {
        // Pass through an already hydrated
        if ($timezone instanceof Timezone) return $timezone;

        // Extract the geonames.org identifier if it's a timezone interface
        if ($timezone instanceof TimezoneInterface) $timezone = $timezone->getCode();

        // Load the timezone from the database
        return $this->findOneBy(['code' => $timezone]);
    }

    /**
     * Copies data between timezone instances
     * 
     * @param TimezoneInterface $source      Source timezone instance
     * @param Timezone          $destination Destination timezone instance
     */
    public function copyTimezone(TimezoneInterface $source, Timezone $destination)
    {
        $country = $this->countryRepository->getCountry($source->getCountry());
        $code    = $source->getCode();

        // Copy country information
        if ($country !== $destination->getCountry()) {
            $destination->setCountry($country);
        }

        // Copy code
        if ($code !== $destination->getCode()) {
            $destination->setCode($code);
        }
    }

    /**
     * Saves a timezone
     * 
     * @param TimezoneInterface $data Timezone
     * 
     * @return TimezoneInterface
     */
    public function saveTimezone(TimezoneInterface $data)
    {
        // Hydrate the data
        if ($data instanceof Timezone) {
            $timezone = $data;
        } else {
            $timezone = $this->getTimezone($data) ?: new Timezone();
            $this->copyTimezone($data, $timezone);
        }

        // Persist and flush the entity in the entity manager
        $em = $this->getEntityManager();
        $em->persist($timezone);
        $em->flush();
    }
}

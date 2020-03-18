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

namespace JJs\Bundle\GeonamesBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use JJs\Bundle\GeonamesBundle\Data\CountryLoader;
use JJs\Common\Console\OutputLogger;
use Symfony\Component\Console\Command\Command;

/**
 * Load Countries Command
 *
 * Loads countries from a geonames.org data file into the country
 *
 * @author Josiah <josiah@jjs.id.au>
 */
class LoadCountriesCommand extends Command
{
    protected static $defaultName = 'geonames:load:countries';

    private CountryLoader $countryLoader;

    public function __construct(CountryLoader $countryLoader)
    {
        parent::__construct();
        $this->countryLoader = $countryLoader;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Loads countries into the country repository from a data file')
            ->addArgument('file', InputArgument::OPTIONAL, "Data file", CountryLoader::DEFAULT_FILE);
    }

    /**
     * Executes the load countries command
     * 
     * @param InputInterface  $input  Input interface
     * @param OutputInterface $output Output interface
     */
    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $file = $input->getArgument('file');
        $log = new OutputLogger($output);

        $this->countryLoader->load($file, $log);

        return null;
    }
}

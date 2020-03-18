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

use JJs\Common\Console\OutputLogger;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use JJs\Bundle\GeonamesBundle\Import\Filter as Filter;
use JJs\Bundle\GeonamesBundle\Import\LocalityImporter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Load Localities Command
 *
 * Loads localities from a geonames.org data file.
 *
 * @author Josiah <josiah@jjs.id.au>
 */
class LoadLocalitiesCommand extends Command
{
    protected static $defaultName = 'geonames:load:localities';

    private LocalityImporter $localityImporter;

    public function __construct(LocalityImporter $localityImporter)
    {
        parent::__construct();
        $this->localityImporter = $localityImporter;
    }

    /**
     * Configures this command
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Loads localities into the state and city repositories from a geonames.org data file')
            ->addArgument(
                'country',
                InputArgument::OPTIONAL | InputArgument::IS_ARRAY,
                "Country to load the localities defaults to all countries"
            )
            ->addOption(
                'filter',
                null,
                InputOption::VALUE_REQUIRED,
                'Which colors do you like?',
                array()
            )
            ->addOption(
                'info',
                null,
                InputOption::VALUE_NONE,
                "Prints information about the locality importer"
            );
    }

    /**
     * Executes the load localities command
     */
    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $countries = $input->getArgument('country');


        $filter = new Filter();
        if (is_string($input->getOption('filter'))) {

            $filterRules = explode(",",  $input->getOption('filter'));

            if (count($filterRules) > 0) {
                foreach ($filterRules as $rule) {
                    $filter->addRule($rule);
                    $output->writeLn("Added filter rule: " . $rule);
                }
            }
        }


        // Display importer information if requested
        if ($input->getOption('info')) {
            /** @var Table $table */
            $table = $this->getHelper('table');
            $table->setStyle('borderless');
            $table->setHeaders(['Feature', 'Repository']);

            foreach ($this->localityImporter->getLocalityRepositories() as $feature => $repository) {
                $table->addRow([$feature, get_class($repository)]);
            }

            $table->render($output);

            return null;
        }

        // Import the specified countries
        $progress = new ProgressBar($output);
        $this->localityImporter->import(
            $countries,
            $filter,
            new OutputLogger($output),
            $progress,
            $output
        );

        return null;
    }
}

<?php

/**
 * This source file is available under :
 * - GNU General Public License version 3 (GPLv3)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 *  @copyright  Copyright (c) sndsabin
 */

namespace SNDSABIN\ImportBundle\Command;

use Pimcore\Console\AbstractCommand;
use SNDSABIN\ImportBundle\Exception\ConfigValidatorException;
use SNDSABIN\ImportBundle\Helper\Config;
use SNDSABIN\ImportBundle\Helper\ImportProcessor;
use SNDSABIN\ImportBundle\Parser\CsvParser;
use SNDSABIN\ImportBundle\Traits\CommandConfigResolver;
use SNDSABIN\ImportBundle\Traits\CommandConfigValidator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommand extends AbstractCommand
{
    use CommandConfigValidator, CommandConfigResolver;

    /** @var Config */
    protected Config $config;

    /** @var string */
    protected string $class;

    /** @var ?string */
    protected ?string $file;

    /** @var ImportProcessor */
    protected ImportProcessor $importProcessor;

    /**
     * @param string|null $name
     * @param Config $config
     */
    public function __construct(Config $config, string $name = null)
    {
        parent::__construct($name);
        $this->config = $config;
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName('data:import')
            ->setDescription('imports the data from file')
            ->setHelp('bin/console data:import -c customer')
            ->addOption('class', 'c', InputOption::VALUE_REQUIRED, 'DataObject whose data is to be imported')
            ->addOption('file', 'f', InputOption::VALUE_OPTIONAL, 'path of the data file')
            ->addOption('book-keeping', null, InputOption::VALUE_NEGATABLE, 'maintain the records (or do not maintain --no-book-keeping) of imported csv file', true);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     *
     * @throws ConfigValidatorException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->class = strtolower($input->getOption('class'));
        $this->file = $input->getOption('file');

        if (!$this->class) {
            $this->writeError('class option missing');

            return Command::INVALID;
        }

        $this->validate();
        list($file, $importer, $mapper) = $this->resolve();
        $parser = $this->getParser($file);

        if (!$parser) {
            $this->writeError('unsupported file type');

            return Command::INVALID;
        }

        $this->importProcessor = new ImportProcessor($parser, $mapper, $importer);
        $this->importProcessor->setBaseDirectory($this->config->get('base_directory'))
                            ->setBookKeeping($input->getOption('book-keeping'))
                            ->setOutputLogger($output)
                            ->process($file);

        return Command::SUCCESS;
    }

    /**
     * @param string $file
     *
     * @return string
     */
    private function getParser(string $file): string
    {
        $n = strrpos($file, '.');
        $extension = $n === false ? '' : substr($file, $n+1);

        return match ($extension) {
            'csv' => CsvParser::class,
            default => ''
        };
    }
}

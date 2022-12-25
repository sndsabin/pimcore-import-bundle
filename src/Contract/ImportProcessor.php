<?php

/**
 * This source file is available under :
 * - GNU General Public License version 3 (GPLv3)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 *  @copyright  Copyright (c) sndsabin
 */

namespace SNDSABIN\ImportBundle\Contract;

use Symfony\Component\Console\Output\OutputInterface;

abstract class ImportProcessor
{
    /** @var MapperInterface */
    protected MapperInterface $mapper;

    /** @var Importer */
    protected Importer $importer;

    protected Parser $parser;

    /** @var OutputInterface */
    protected OutputInterface $output;

    /** @var string */
    protected ?string $baseDirectory;

    /** @var bool */
    protected bool $bookKeeping;

    /**
     * @param string $parser
     * @param string $mapper
     * @param string $importer
     */
    public function __construct(
        string $parser,
        string $mapper,
        string $importer
    ) {
        $this->parser = new $parser();
        $this->mapper = new $mapper();
        $this->importer = new $importer();
    }

    /**
     * @param OutputInterface $output
     *
     * @return self
     */
    public function setOutputLogger(OutputInterface $output): self
    {
        $this->output = $output;
        $this->importer->setOutputLogger($this->output);

        return $this;
    }

    /**
     * @param string|null $baseDirectory
     *
     * @return $this
     */
    public function setBaseDirectory(?string $baseDirectory): self
    {
        $this->baseDirectory = $baseDirectory;

        return $this;
    }

    /**
     * @param bool $bookKeeping
     *
     * @return $this
     */
    public function setBookKeeping(bool $bookKeeping): self
    {
        $this->bookKeeping = $bookKeeping;

        return $this;
    }

    abstract public function process(string $file): void;
}

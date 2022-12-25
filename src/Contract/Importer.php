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

use Cocur\Slugify\Slugify;
use Pimcore\Cache;
use Pimcore\Model\Version;
use Symfony\Component\Console\Output\OutputInterface;

abstract class Importer
{
    /** @var OutputInterface */
    protected OutputInterface $output;

    /** @var array */
    protected array $validators = [];

    /**
     * @param Slugify $slugify
     */
    public function __construct(
        protected Slugify $slugify = new Slugify()
    ) {
        Cache::disable();
        Version::disable();
    }

    /**
     * @param OutputInterface $output
     *
     * @return $this
     */
    public function setOutputLogger(OutputInterface $output): self
    {
        $this->output = $output;

        return $this;
    }

    abstract public function import(array $data): void;
}

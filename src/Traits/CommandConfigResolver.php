<?php

/**
 * This source file is available under :
 * - GNU General Public License version 3 (GPLv3)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 *  @copyright  Copyright (c) sndsabin
 */

namespace SNDSABIN\ImportBundle\Traits;

trait CommandConfigResolver
{
    /**
     * @return array
     */
    public function resolve(): array
    {
        $class = ucfirst($this->class);
        $mapper = $this->config->get("{$this->class}.mapper") !== '' ?
            $this->config->get("{$this->class}.mapper") :
            "SNDSABIN\ImportBundle\Mapper\\{$class}Mapper";
        $importer = $this->config->get("{$this->class}.importer") !== '' ?
            $this->config->get("{$this->class}.importer") :
            "SNDSABIN\ImportBundle\Importer\BaseImporter";

        if ($this->file) {
            $file = $this->file;
        } else {
            $file = $this->config->get("{$this->class}.file") !== '' ?
                "{$this->config->get('base_directory')}/{$this->config->get("{$this->class}.file")}" :
                '';
        }

        return [$file, $importer, $mapper];
    }
}

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

use SNDSABIN\ImportBundle\Exception\ConfigValidatorException;

trait CommandConfigValidator
{
    /**
     * @return int
     *
     * @throws ConfigValidatorException
     */
    public function validate(): int
    {
        if (!$this->config->get($this->class)) {
            throw new ConfigValidatorException('class not configured correctly in import config');
        }

        if (!$this->file && !$this->config->get('base_directory')) {
            throw new ConfigValidatorException('base_directory not configured correctly in import config');
        }

        if (!$this->file && !$this->config->get("{$this->class}.mapper")) {
            throw new ConfigValidatorException("mapper not configured correctly in import config for {$this->class} class");
        }

        return true;
    }
}

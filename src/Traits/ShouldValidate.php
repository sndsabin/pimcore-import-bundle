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

use SNDSABIN\ImportBundle\Contract\ValidatorInterface;

trait ShouldValidate
{
    /**
     * @param array $data
     *
     * @return $this
     */
    protected function validate(array $data): static
    {
        foreach ($this->validators as $validator) {
            if (isset(class_implements($validator)[ValidatorInterface::class])) {
                $validator::validate($data);
            }
        }

        return $this;
    }
}

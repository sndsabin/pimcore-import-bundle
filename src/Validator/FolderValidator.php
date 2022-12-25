<?php

/**
 * This source file is available under :
 * - GNU General Public License version 3 (GPLv3)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 *  @copyright  Copyright (c) sndsabin
 */

namespace SNDSABIN\ImportBundle\Validator;

use SNDSABIN\ImportBundle\Contract\ValidatorInterface;
use SNDSABIN\ImportBundle\Exception\MapperValidatorException;

class FolderValidator implements ValidatorInterface
{
    /**
     * @param array $data
     *
     * @return bool
     *
     * @throws MapperValidatorException
     */
    public static function validate(array $data): bool
    {
        if (!isset($data['folder']) || !$data['folder']) {
            throw new MapperValidatorException('folder not configured properly in mapper');
        }

        return true;
    }
}

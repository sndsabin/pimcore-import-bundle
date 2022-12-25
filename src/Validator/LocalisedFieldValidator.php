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

class LocalisedFieldValidator implements ValidatorInterface
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
        if (isset($data['attributes']['localisedField']) && is_array($data['attributes']['localisedField'])) {
            foreach ($data['attributes']['localisedField'] as $localisedField) {
                if ($missingKeys = array_diff(['attribute', 'value', 'language'], array_keys($localisedField))) {
                    $keys = implode(',', $missingKeys);

                    throw new MapperValidatorException("{$keys} missing for localisedField in mapper");
                }
            }
        }

        return true;
    }
}

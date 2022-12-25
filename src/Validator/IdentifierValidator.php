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
use SNDSABIN\ImportBundle\Helper\IdentifierType;

class IdentifierValidator implements ValidatorInterface
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
        if (!isset($data['identifier']) ||
            !is_array($data['identifier']) ||
            !$data['identifier']
        ) {
            throw new MapperValidatorException('identifier not configured properly in mapper');
        }

        self::checkForMissingAttributes($data['identifier']);
        self::checkForMissingAttributeValue($data['identifier']);

        return true;
    }

    /**
     * @param $identifier
     *
     * @return void
     *
     * @throws MapperValidatorException
     */
    private static function checkForMissingAttributes($identifier): void
    {
        if ($missingKeys = array_diff(['attribute', 'value', 'type'], array_keys($identifier))) {
            $keys = implode(',', $missingKeys);

            throw new MapperValidatorException("{$keys} missing for identifier in mapper");
        }
    }

    /**
     * @param array $data
     *
     * @return void
     *
     * @throws MapperValidatorException
     */
    private static function checkForMissingAttributeValue(array $data): void
    {
        foreach ($data as $key => $value) {
            if (!$value) {
                throw new MapperValidatorException("identifier {$key} not configured properly in mapper");
            }

            if ($key === 'type' && $value !== IdentifierType::NON_CONDITIONAL) {
                if (!isset($data['condition']) || !$data['condition']) {
                    throw new MapperValidatorException('condition missing for identifier in mapper');
                }
            }
        }
    }
}

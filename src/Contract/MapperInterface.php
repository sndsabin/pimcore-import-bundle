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

interface MapperInterface
{
    /**
     * @param array $data
     *
     * @return array
     */
    public function map(array $data): array;
}

<?php

/**
 * This source file is available under :
 * - GNU General Public License version 3 (GPLv3)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 *  @copyright  Copyright (c) sndsabin
 */

namespace SNDSABIN\ImportBundle\Helper;

use Pimcore\Model\DataObject\Folder as PimcoreFolder;

class Folder
{
    /**
     * @param string $name
     * @param int|null $parentId
     *
     * @return int|null
     */
    public static function find(string $name, ?int $parentId): ?int
    {
        if ($parentId && $parentId !== 1) {
            $path = PimcoreFolder::getById($parentId)->getRealFullPath();

            return PimcoreFolder::getByPath("${path}/{$name}")?->getId();
        }

        return PimcoreFolder::getByPath("/{$name}")?->getId();
    }

    /**
     * @param string $name
     * @param int $parentId
     *
     * @return int
     *
     * @throws \Exception
     */
    public static function findOrCreate(string $name, int $parentId = 1): int
    {
        if ($folderId = self::find($name, $parentId)) {
            return $folderId;
        }

        $folder = PimcoreFolder::create(['o_key' => $name, 'o_parentId' => $parentId]);
        $folder->save();

        return $folder->getId();
    }
}

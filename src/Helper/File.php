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

class File
{
    public static function rename(string $file, string $destinationDirectory): void
    {
        if (!is_dir($destinationDirectory)) {
            mkdir($destinationDirectory, 0755, true);
        }

        $explodedFilePath = explode('/', $file);
        $fileName = end($explodedFilePath);

        rename($file, "{$destinationDirectory}/{$fileName}");
    }
}

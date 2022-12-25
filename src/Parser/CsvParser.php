<?php

/**
 * This source file is available under :
 * - GNU General Public License version 3 (GPLv3)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 *  @copyright  Copyright (c) sndsabin
 */

namespace SNDSABIN\ImportBundle\Parser;

use SNDSABIN\ImportBundle\Contract\Parser;
use SNDSABIN\ImportBundle\Exception\InvalidFileException;

class CsvParser implements Parser
{
    /**
     * @param $file
     *
     * @return array
     *
     * @throws InvalidFileException
     */
    public function parse($file): array
    {
        if (!is_file($file) || pathinfo($file)['extension'] !== 'csv') {
            throw new InvalidFileException('either file doesnt exists or the file type is incorrect');
        }

        $csvData = array_map('str_getcsv', file($file));

        $header = [];
        foreach ($csvData[0] as $value) {
            $header[] = $value;
        }

        array_walk($csvData, function (& $row) use ($header) {
            $row = array_combine($header, $row);
        });

        array_shift($csvData);

        return $csvData;
    }
}

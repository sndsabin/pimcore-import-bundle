<?php

namespace SNDSABIN\ImportBundle\Parser;

use Exception;
use SNDSABIN\ImportBundle\Contract\Parser;
use SNDSABIN\ImportBundle\Exception\InvalidFileException;

class JsonParser implements Parser
{
    /**
     * @param $parse
     *
     * @return array
     *
     * @throws InvalidFileException
     * @throws Exception
     */
    public function parse($file): array
    {
        $this->validateIsJsonFile($file);
        $jsonContent = file_get_contents($file);

        $jsonData = json_decode($jsonContent, true);


        if ($jsonData == null) {
            throw new Exception(json_last_error_msg());
        }

        return $jsonData;
    }

    /**
     * @throws InvalidFileException
     */
    private function validateIsJsonFile(string $file)
    {
        if (!is_file($file) || pathinfo($file)['extension'] !== 'json') {
            throw new InvalidFileException('either file doesnt exists or the file type is incorrect');
        }
    }
}

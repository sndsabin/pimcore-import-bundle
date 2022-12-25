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

use Carbon\Carbon;
use SNDSABIN\ImportBundle\Contract\ImportProcessor as BaseImportProcessor;

class ImportProcessor extends BaseImportProcessor
{
    /**
     * @param string $file
     *
     * @return void
     *
     * @throws \Exception
     */
    public function process(string $file): void
    {
        $startTime = microtime(true);
        $records = $this->parser->parse($file);

        foreach ($records as $record) {
            try {
                $mappedData = $this->mapper->map($record);
                $this->importer->import($mappedData);
            } catch (\Exception $exception) {
                $dataObjectClass = $mappedData['class'] ?? '';
                \Pimcore\Log\Simple::log(
                    'csv-import-error-' . Carbon::now()->toDateString(),
                    "Error importing {$dataObjectClass} ". json_encode($record) . ": {$exception->getMessage()}"
                );
                $this->output?->writeln(
                    sprintf('<error>ERROR: %s</error>', $exception->getMessage()));
            }
        }

        unset($this->mapper, $this->importer);

        if ($this->bookKeeping) {
            $this->handleBookKeeping($file);
        }

        $this->output?->writeln(sprintf(
            '<info>INFO: %s</info>',
            'Total time :' . (microtime(true) - $startTime) / 60 . ' mins')
        );
    }

    /**
     * @param string $file
     *
     * @return self
     */
    private function handleBookKeeping(string $file): self
    {
        if ($this->baseDirectory && is_dir($this->baseDirectory)) {
            $destinationDirectory = $this->baseDirectory . '/imported/' . Carbon::now()->toDateString();

            File::rename($file, $destinationDirectory);
        }

        return $this;
    }
}

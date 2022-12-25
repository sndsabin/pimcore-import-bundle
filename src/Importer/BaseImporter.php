<?php

/**
 * This source file is available under :
 * - GNU General Public License version 3 (GPLv3)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 *  @copyright  Copyright (c) sndsabin
 */

namespace SNDSABIN\ImportBundle\Importer;

use Pimcore\Model\DataObject\Concrete;
use SNDSABIN\ImportBundle\Contract\Importer;
use SNDSABIN\ImportBundle\Helper\Folder;
use SNDSABIN\ImportBundle\Helper\IdentifierType;
use SNDSABIN\ImportBundle\Traits\ShouldValidate;
use SNDSABIN\ImportBundle\Validator\ClassValidator;
use SNDSABIN\ImportBundle\Validator\FolderValidator;
use SNDSABIN\ImportBundle\Validator\IdentifierValidator;
use SNDSABIN\ImportBundle\Validator\KeyValidator;
use SNDSABIN\ImportBundle\Validator\LocalisedFieldValidator;

class BaseImporter extends Importer
{
    use ShouldValidate;

    /** @var int */
    protected int $folderId;

    /** @var array */
    protected array $validators = [
        FolderValidator::class,
        IdentifierValidator::class,
        ClassValidator::class,
        LocalisedFieldValidator::class,
        KeyValidator::class,
    ];

    /**
     * @throws \Exception
     */
    public function import(array $data): void
    {
        $this->validate($data);

        $this->folderId = $this->folderId ?? Folder::findOrCreate($data['folder']);

        $dataObject = $this->findDataObject($data);
        $this->updateOrCreateDataObject($dataObject, $data);
    }

    /**
     * @param array $fields
     * @param Concrete $dataObject
     *
     * @return Concrete
     */
    private function attachLocalisedField(array $fields, Concrete $dataObject): Concrete
    {
        foreach ($fields as $field) {
            $setterMethod = 'set' . ucfirst($field['attribute']);
            if (method_exists($dataObject, $setterMethod)) {
                $dataObject->$setterMethod($field['value'], $field['language']);
            }
        }

        return $dataObject;
    }

    /**
     * @param mixed $dataObject
     * @param array $data
     *
     * @return self
     *
     * @throws \Exception
     */
    private function updateOrCreateDataObject(mixed $dataObject, array $data): self
    {
        $this->output?->writeln("Importing {$data['class']} with {$data['identifier']['attribute']} : {$data['identifier']['value']}");

        if (!$dataObject) {
            $dataObject = new $data['class']();
            $dataObject->setParentId($this->folderId);
        }

        foreach ($data['attributes'] as $attribute => $value) {
            if ($attribute == 'localisedField' && is_array($value)) {
                $dataObject = $this->attachLocalisedField($value, $dataObject);
            } else {
                $setterMethod = 'set' . ucfirst($attribute);
                if (method_exists($dataObject, $setterMethod)) {
                    match ($setterMethod) {
                        'setKey' => $dataObject->$setterMethod($this->slugify->slugify($value)),
                        default => $dataObject->$setterMethod($value)
                    };
                }
            }
        }

        $dataObject->setPublished(true)
                    ->setOmitMandatoryCheck(true)
                    ->save();

        unset($dataObject);

        return $this;
    }

    /**
     * @param array $data
     *
     * @return Concrete|null
     */
    private function findDataObject(array $data): Concrete|null
    {
        $identifierType = $data['identifier']['type'];
        if ($identifierType === IdentifierType::NON_CONDITIONAL) {
            $dataObject = $this->findUsingGetter($data);
        }

        if ($identifierType !== IdentifierType::NON_CONDITIONAL) {
            $dataObject = $this->findUsingCondition($data, $identifierType);
        }

        return $dataObject ?? null;
    }

    /**
     * @param mixed $condition
     * @param mixed $dataObjectList
     *
     * @return void
     */
    private function addConditionalParam(mixed $condition, mixed $dataObjectList): mixed
    {
        if (is_string($condition)) {
            $dataObjectList->addConditionParam($condition);
        }

        if (is_array($condition) && count($condition) >= 2) {
            $dataObjectList->addConditionParam($condition[0], $condition[1], $condition[2] ?? 'AND');
        }

        return $dataObjectList;
    }

    /**
     * @param mixed $condition
     * @param mixed $dataObjectList
     *
     * @return mixed
     */
    private function setCondition(mixed $condition, mixed $dataObjectList): mixed
    {
        if (is_string($condition)) {
            $dataObjectList->setCondition($condition);
        }

        if (is_array($condition) && count($condition) === 2) {
            $dataObjectList->setCondition($condition[0], $condition[1]);
        }

        return $dataObjectList;
    }

    /**
     * @param array $data
     *
     * @return mixed
     */
    private function findUsingGetter(array $data): mixed
    {
        $method = "getBy{$data['identifier']['attribute']}";

        return $data['class']::$method($data['identifier']['value'], 1);
    }

    /**
     * @param array $data
     * @param mixed $identifierType
     *
     * @return mixed
     */
    public function findUsingCondition(array $data, mixed $identifierType): mixed
    {
        $condition = $data['identifier']['condition'];

        $listingClass = "{$data['class']}\Listing";
        $dataObjectList = new $listingClass();
        $dataObjectList->setLimit(1);
        $dataObjectList->setUnpublished(true); // search unpublished one too

        if ($identifierType === IdentifierType::CONDITIONAL) {
            $dataObjectList = $this->setCondition($condition, $dataObjectList);
        }

        if ($identifierType === IdentifierType::CONDITIONAL_PARAM) {
            $dataObjectList = $this->addConditionalParam($condition, $dataObjectList);
        }

        $dataObjects = $dataObjectList->getObjects();

        if ($dataObjects) {
            $dataObject = reset($dataObjects); // maximum one result
        }

        return $dataObject ?? null;
    }
}

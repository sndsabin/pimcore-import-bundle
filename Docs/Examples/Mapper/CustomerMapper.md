# Examples

## Customer Mapper
- NON CONDITIONAL (with localized field `note`)
```php
use Pimcore\Model\DataObject\Customer;
use SNDSABIN\ImportBundle\Helper\IdentifierType;
use SNDSABIN\ImportBundle\Contract\MapperInterface;

class CustomerMapper implements MapperInterface
{
    /** @var string */
    const FOLDER = 'customer'; // the folder inside which all customer data objects would be created
    
    /**
     * @param array $data
     * @return array
     */
    public function map(array $data): array
    {
        return [
            'folder' => self::FOLDER,
            'class' => Customer::class,
            'identifier' => [
                'attribute' => 'code',
                'value' => $data['Code'],
                'type' => IdentifierType::NON_CONDITIONAL
            ],
            'attributes' => [
                'code' => $data['Code'],
                'firstname' => $data['First Name'],
                'lastname' => $data['Last Name'],
                'email' => $data['Email'],
                'company' => $data['Company'],
                'address' => $data['Address'],
                'country' => $data['Country'],
                'phone' => $data['Phone'],
                'acceptsMarketing' => (bool) $data['Accepts Marketing'],
                'key' => "{$data['Code']}-{$data['First Name']}",
                'localisedField' => [
                    [
                        'attribute' => 'note',
                        'value' => $data['Note English'],
                        'language' => 'en'
                    ] 
                ]
            ]
        ];
    }
}
```
- CONDITIONAL (with localized field `note`)
```php
use Pimcore\Model\DataObject\Customer;
use SNDSABIN\ImportBundle\Helper\IdentifierType;
use SNDSABIN\ImportBundle\Contract\MapperInterface;

class CustomerMapper implements MapperInterface
{
    /** @var string */
    const FOLDER = 'customer'; // the folder inside which all customer data objects would be created
    
    /**
     * @param array $data
     * @return array
     */
    public function map(array $data): array
    {
        return [
            'folder' => self::FOLDER,
            'class' => Customer::class,
            'identifier' => [
                'attribute' => 'code',
                'value' => $data['Code'],
                'type' => IdentifierType::CONDITIONAL,
		        'condition' => '<ANY_ONE_OF_THE_BELOW_CONDITIONS>',
//                'condition' => ["code LIKE ?", ["%{$data['Code']}%"]] // prepared statements
//                'condition' => ["code LIKE :code", ["code" => "%{$data['Code']}%"]] // named parameters
//                'condition' => ["code IN (?)", [[$data['Code']]]] // array bindings for prepared statements
//                'condition' => ["code IN (:code)", ["code" => [$data['Code']]]] // array bindings for named parameters
//                'condition' => "code LIKE " . "'%{$data['Code']}%'" // custom query
            ],
            'attributes' => [
                'code' => $data['Code'],
                'firstname' => $data['First Name'],
                'lastname' => $data['Last Name'],
                'email' => $data['Email'],
                'company' => $data['Company'],
                'address' => $data['Address'],
                'country' => $data['Country'],
                'phone' => $data['Phone'],
                'acceptsMarketing' => (bool) $data['Accepts Marketing'],
                'key' => "{$data['Code']}-{$data['First Name']}",
                'localisedField' => [
                    [
                        'attribute' => 'note',
                        'value' => $data['Note English'],
                        'language' => 'en'
                    ] 
                ]
            ]
        ];
    }
}
```
- CONDITIONAL PARAM (with localized field `note`)
```php
use Pimcore\Model\DataObject\Customer;
use SNDSABIN\ImportBundle\Helper\IdentifierType;
use SNDSABIN\ImportBundle\Contract\MapperInterface;

class CustomerMapper implements MapperInterface
{
    /** @var string */
    const FOLDER = 'customer'; // the folder inside which all customer data objects would be created
    
    /**
     * @param array $data
     * @return array
     */
    public function map(array $data): array
    {
        return [
            'folder' => self::FOLDER,
            'class' => Customer::class,
            'identifier' => [
                'attribute' => 'code',
                'value' => $data['Code'],
                'type' => IdentifierType::CONDITIONAL_PARAM, 
                'condition' => '<ANY_ONE_OF_THE_BELOW_CONDITIONS>',
//		        'condition' => ["code = ?", $data['Code']]
//              'condition' => ["code = ?", $data['Code'], "AND"]

            ],
            'attributes' => [
                'code' => $data['Code'],
                'firstname' => $data['First Name'],
                'lastname' => $data['Last Name'],
                'email' => $data['Email'],
                'company' => $data['Company'],
                'address' => $data['Address'],
                'country' => $data['Country'],
                'phone' => $data['Phone'],
                'acceptsMarketing' => (bool) $data['Accepts Marketing'],
                'key' => "{$data['Code']}-{$data['First Name']}",
                'localisedField' => [
                    [
                        'attribute' => 'note',
                        'value' => $data['Note English'],
                        'language' => 'en'
                    ] 
                ]
            ]
        ];
    }
}
```
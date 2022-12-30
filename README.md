# Import Bundle - Pimcore Bundle
An opinionated pimcore bundle for importing files.

- [Supports](#supports)
- [Installation](#installation)
- [Example use case (Import)](#example-use-case-import)

## Supports
- CSV

## Installation
### Prerequisite

Requires **pimcore >=10.0.0**

### Step 1 (install the bundle)
```
composer require sndsabin/import-bundle
```

### Step 2 (enable the bundle)
```php
bin/console pimcore:bundle:enable ImportBundle
```

## Example use case: Import
Let's say records of **customer** has to be imported to **[Customer DataObject Class](./Docs/Examples/DataObject/class_Customer_export.sample.json)** called from [customer.csv](./Docs/Examples/import-data/customer.csv).

### Step 3 (add mapper)
```php
<?php

namespace App\Mapper;

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
            'folder' => self::FOLDER, // mandatory
            'class' => Customer::class, // mandatory
            'identifier' => [ // analogous to primary key: used for update operation (mandatory) 
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
                'key' => "{$data['Code']}-{$data['First Name']}", // o_key (mandatory)
                'localisedField' => [
                    [
                        'attribute' => 'note',
                        'value' => $data['Note English'],
                        'language' => 'en'
                    ],
                    [
                        'attribute' => 'note',
                        'value' => $data['Note Nepali'],
                        'language' => 'ne'
                    ]
                ]
            ]
        ];

    }
}
```
**@see** [CustomerMapper.md](Docs/Examples/Mapper/CustomerMapper.md) for more examples on how to use **IdentifierType::CONDITIONAL** and **IdentifierType::CONDITIONAL_PARAM**.
### Step 4 (add configuration)
configure `import.yaml` for the **class** (Example: `customer` in this case) you wish to import

```yaml
#config/packages/import.yaml
import:
    config:
        base_directory: '/var/www/html/import-data' # base directory where all the files to be imported are located (required)
        customer:
            file: 'customer.csv'  # file name (required)
            mapper: 'App\Mapper\CustomerMapper' # mapper (required)
```
**@see** [CommandConfigResolver.php](src/Traits/CommandConfigResolver.php) and [CommandConfigValidator.php](src/Traits/CommandConfigValidator.php) for more info on how these attributes are parsed and validated.

**@see** [Sample Config File](Docs/Examples/Config/import.example.yaml) for all the configurable attributes.
### Step 5 (import using command)

```php
bin/console data:import [options]

Options:
  -c, --class=CLASS                     DataObject whose data is to be imported
  -f, --file[=FILE]                     path of the data file
      --book-keeping|--no-book-keeping  maintain the records (or do not maintain --no-book-keeping) of imported file
```

#### Example usage (for **customer** class)
```php
bin/console data:import -c customer
```

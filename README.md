# Import Bundle - Pimcore Bundle
An opinionated pimcore bundle for importing files.

- [Supports](#supports)
- [Installation](#installation)
- [Example use case](#example-use-case)

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

### Step 2 (add your configuration)
configure `import.yaml` for the **class** (Example: `customer` in this case) you wish to import

```yaml
#config/packages/import.yaml
import:
    config:
        base_directory: '' # base directory where all the files to be imported are located (recommended)
        customer:
            file: '' # file name (recommended)
            importer: '' # only required if other than base importer is to be used
            mapper: '' # optional
```

### Step 4 (import using command)

```php
bin/console data:import [options]

Options:
  -c, --class=CLASS                     DataObject whose data is to be imported
  -f, --file[=FILE]                     path of the data file
      --book-keeping|--no-book-keeping  maintain the records (or do not maintain --no-book-keeping) of imported csv file
```

#### Example usage (for **customer** class)
```php
bin/console data:import -c customer
```

## Example use case
To be added
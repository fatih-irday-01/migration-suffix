

![Laravel Suffix](./laravel_suffix.jpg)

## Installation

1. Run composer command to install the package

```shell
composer require fatihirday/suffix
```

2. Publish the config and migration files.

```shell
php artisan vendor:publish --provider="Fatihirday\Suffixed\SuffixServiceProvier"
```

* Response

```
* config/suffixed.php
* migrations/*_create_suffixes_table.php
```

3. To use the suffix list in the package

```shell 
php artisan migrate
```
If you are going to use your own list, you can delete the file `migrations/*_create_suffixes_table.php`

<br />

## Configuration

1. Config suffixed File

```php
return [
    'suffixes' => [
        'table' => \Fatihirday\Suffixed\Models\Suffix::class,
        'column' => 'code',
        'auto_create_suffixed_tables' => true,
    ],

    'suffix_auto_check' => true,

    'merge_operator' => '_',

    'suffix_max_length' => 3,
];
```

| Config | Des |
|---|---|
|`suffixes.table`| suffix list table |
|`suffixes.code`| suffix column |
|`suffixes.auto_create_suffixed_tables`| Automatically create suffixes tables when new record is added to `suffixes.table` table |
|`suffix_auto_check`| When it wants to work on the attached table, it automatically checks the table. |
|`merge_operator`| table name and suffix merge operator |
|`suffix_max_length`| suffix max length. use `null to be unlimited |

2. Suffix Model

```php
class Suffix extends Model
{
    use HasFactory, SuffixList;
}
````

`use SuffixList;` for custom model.

<br />

## Examples

### Make migration

<br />

#### 1. Insert row to suffixes table

```shell
php artisan tinker
```

```php
$row = new \Fatihirday\Suffixed\Models\Suffix();
$row->name = 'Fatih';
$row->code = 'fth';
$row->save();
```

<br />

#### 2. Make migration suffixed 

```shell
php artisan make:migration-suffix CreateDemoTable
```

```php

class CreateDenemeTable  extends SuffixMigration implements SuffixSchame
{
    protected $baseTableName = 'demo';

    public function upSuffix(string $tableName, string $prefix)
    {
        Schema::create($tableName, function (Blueprint $table) use ($prefix)  {
            $table->id();
            $table->string('name', 30);
            $table->timestamps();
        });
    }

    public function downSuffix(string $tableName, string $prefix)
    {
        Schema::dropIfExists($tableName);
    }
}
```

<br />

#### 3. Run the migrate
```shell
php artisan migrate
```
Created table `demo_fth`

<br />

#### 4. Make Model

Use Suffixed in your model to access suffixed tables
```php
class Demo extends Model
{
    use HasFactory, Suffixed;
}
```
<br />


### Use of suffix table

<br />

#### Check suffixed table
```php
App\Models\Demo::checkSuffixCode('fth');
// Response : true || false
```

<br />

#### Set suffix code
```php
App\Models\Demo::setSuffixCode('fth')->toSql();
// Response : "select * from `demo_fth`"
```

<br />

#### Get suffixed table name
```php
App\Models\Demo::setSuffixCode('fth')->getTable();
// Response : "demo_fth"
```

<br />

#### Get suffixed table suffix code
```php
App\Models\Demo::setSuffixCode('fth')->getSuffixCode();
// Response : "fth"
```

<br />

#### example query
```php
// insert row to demo_fth table
$newRow = App\Models\Demo::setSuffixCode('fth');
$newRow->name = 'deneme';
$newRow->save();


// get rows to demo_fth table
App\Models\Demo::setSuffixCode('fth')->whereNotNull('name')->get();
```









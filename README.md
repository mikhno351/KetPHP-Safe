# KetSafe

![Packagist Version](https://img.shields.io/packagist/v/ket-php/utils-safe)
![Packagist Downloads](https://img.shields.io/packagist/dt/ket-php/utils-safe?logo=packagist&logoColor=white)
![Static Badge](https://img.shields.io/badge/PHP-8.1-777BB4?logo=php&logoColor=white)



## Installation
Install via Composer:
```
composer require ket-php/utils-safe
```

## Usage

### Safe:
```php
use KetPHP\Utils\Safe;
use KetPHP\Utils\Common\Cast;

// Simple value
$value = Safe::get('Hello'); 
echo $value; // Hello

// Value with default
$value = Safe::get(null, 'Default'); 
echo $value; // Default

// Using a callable
$value = Safe::get(fn() => 123); 
echo $value; // 123

// Transform only if value exists
$value = Safe::get('  John  ', 'Unknown', fn($v) => trim($v));
echo $value; // John

// Transform string function
$value = Safe::get('  John  ', 'Unknown', 'trim');
echo $value; // John

// Default value ignores transform
$value = Safe::get(null, 'Fallback', fn($v) => strtoupper($v));
echo $value; // Fallback

// Optional casting
$value = Safe::get('123', null, null, Cast::INT); 
echo $value; // 123 (integer)

$data = ['known' => 'value'];

// Without null coalescing
// WARNING: PHP would normally trigger a Notice (Undefined index)
$value = Safe::get($data['unknown'], 'Default');
echo $value; // Default (PHP Notice / E_USER_WARNING is triggered)

// You can suppress the warning using the @ operator
$value = Safe::get(@$data['unknown'], 'Default');
echo $value; // Default (no warning)

// With null coalescing
// Safe and no warning
$value = Safe::get($data['unknown'] ?? null, 'Default');
echo $value; // Default
```

#### Constants for Casting:
| Constant        | Description     |
|-----------------| --------------- |
| `Cast::INT`     | Cast to integer |
| `Cast::FLOAT`   | Cast to float   |
| `Cast::STRING`  | Cast to string  |
| `Cast::BOOLEAN` | Cast to boolean |
| `Cast::ARRAY`   | Cast to array   |
| `Cast::OBJECT`  | Cast to object  |
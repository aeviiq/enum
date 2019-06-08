# Enum Component

## Why
To provide a way to implement enum flags in PHP.
This component is inspired by the https://github.com/myclabs/php-enum and the enum
[Flags] options in other programming languages.

## Installation
```
composer require aeviiq/enum
```

## Declaration
```php
final class Foo extends Flag
{
    public const BAR = 1;
    public const BAZ = 2;
    // ...
}
```

## Usage
```php
$foo = new Foo(Foo:BAR);
$foo->contains(new Foo(Foo:BAR)); // True
$foo->contains(new Foo(Foo:BAZ)); // False
$foo->contains(new Bar(Foo::BAR)); // InvalidArgumentException thrown
$foo = new Foo(6); // UnexpectedValueException thrown
```

More documentation can be found on https://github.com/myclabs/php-enum

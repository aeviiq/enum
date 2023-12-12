# Enum Component

## Why
To provide a way to implement enum flags in PHP.

## Installation
```
composer require aeviiq/enum
```

## Declaration
```php
final class Foo extends AbstractFlag
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

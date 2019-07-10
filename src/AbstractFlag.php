<?php declare(strict_types = 1);

namespace Aeviiq\Enum;

use Aeviiq\Enum\Exception\InvalidArgumentException;
use MyCLabs\Enum\Enum;

abstract class AbstractFlag extends Enum
{
    /**
     * @var int[]
     */
    protected static $flags = [];

    public function __construct($value)
    {
        static::$flags[static::class] = \array_sum(static::toArray());
        parent::__construct($value);
    }

    /**
     * @inheritdoc
     */
    public static function isValid($value): bool
    {
        return \is_int($value) ? ($value & static::$flags[static::class]) === $value : false;
    }

    public function contains(AbstractFlag $flag): bool
    {
        if (!($flag instanceof $this)) {
            throw new InvalidArgumentException(\sprintf('Argument 1 passed to %s() must be an instance of %s, %s given.', __METHOD__, static::class, \get_class($flag)));
        }

        return ($flag->getValue() & $this->getValue()) === $flag->getValue();
    }
}

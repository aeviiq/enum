<?php

declare(strict_types=1);

namespace Aeviiq\Enum;

use Aeviiq\Enum\Exception\InvalidArgumentException;
use Aeviiq\Enum\Exception\UnexpectedValueException;

abstract class AbstractFlag
{
    /**
     * @var array
     * @psalm-var array<class-string, array<string, int>>
     */
    protected static $cache = [];

    /**
     * @var array<string, int>
     */
    protected static $flags = [];

    /**
     * @throws \ReflectionException
     */
    final public function __construct(private readonly int $value)
    {
        static::$flags[static::class] = \array_sum(static::toArray());

        if (!static::isValid($value)) {
            throw new UnexpectedValueException(sprintf('Value \'%s\' is not part of the enum %s', $value, static::class));
        }
    }

    /**
     * @return array<string, static>
     */
    public static function values(): array
    {
        return \array_map(static function ($value) {
            return new static($value);
        }, static::toArray());
    }

    /**
     * @return array<string, int>
     *
     * @throws \ReflectionException
     */
    public static function toArray(): array
    {
        $class = static::class;
        if (!isset(static::$cache[$class])) {
            $reflection = new \ReflectionClass($class);
            $flags = $reflection->getConstants();
            foreach ($flags as $flagId => $flag) {
                if (!\is_int($flag)) {
                    throw new UnexpectedValueException(\sprintf('Flags must be integers but "%s" is of type "%s"', $flagId, gettype($flag)));
                }
            }

            /** @var array<string, int> $flags */
            static::$cache[$class] = $flags;
        }

        return static::$cache[$class];
    }

    public static function isValid(int $value): bool
    {
        return ($value & static::$flags[static::class]) === $value;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function contains(AbstractFlag $flag): bool
    {
        if (!($flag instanceof $this)) {
            throw new InvalidArgumentException(\sprintf('Argument 1 passed to %s() must be an instance of %s, %s given.', __METHOD__, static::class, \get_class($flag)));
        }

        $flagValue = $flag->getValue();

        return ($this->getValue() & $flagValue) === $flagValue;
    }

    /**
     * @return array<int>
     */
    public function explode(): array
    {
        $values = [];

        foreach (static::values() as $flag) {
            if ($this->contains($flag)) {
                $values[] = $flag->getValue();
            }
        }

        return $values;
    }

    public function count(): int
    {
        return count($this->explode());
    }

    final public function equals(AbstractFlag $variable): bool
    {
        return $this->getValue() === $variable->getValue() && static::class === \get_class($variable);
    }
}

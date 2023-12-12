<?php

declare(strict_types=1);

namespace Aeviiq\Enum\Tests;

use Aeviiq\Enum\AbstractFlag;
use Aeviiq\Enum\Exception\UnexpectedValueException;
use PHPUnit\Framework\TestCase;

final class AbstractFlagTest extends TestCase
{
    public static function constructorThrowsExceptionOnInvalidValueDataProvider(): array
    {
        return [
            [4],
            [5],
            [7],
            [12],
        ];
    }

    public static function constructorAllowsValidValuesAndCombinedBitValuesDataProvider(): array
    {
        return [
            [1],
            [2],
            [3],
            [8],
            [11],
        ];
    }

    public static function isValidSupportsCombinedBitValuesDataProvider(): array
    {
        return [
            'Test with defined value' => [1, true,],
            'Test with combined bit value' => [3, true],
            'Test with missing value' => [4, false],
            'Test with invalid combined bit value' => [5, false],
        ];
    }

    public static function explodeDataProvider(): array
    {
        return [
            'Test with defined value' => [2, [2]],
            'Test with combined value' => [9, [1, 8]],
            'Test with multiple combined values' => [11, [1, 2, 8]],
        ];
    }

    public static function countDataProvider(): array
    {
        return [
            'Test with 1 value' => [2, 1],
            'Test with 2 values' => [9, 2],
            'Test with 3 values' => [11, 3],
        ];
    }

    /**
     * @dataProvider constructorThrowsExceptionOnInvalidValueDataProvider
     */
    public function testConstructorThrowsExceptionOnInvalidValue($value): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->createSubject($value);
    }

    /**
     * @dataProvider constructorAllowsValidValuesAndCombinedBitValuesDataProvider
     */
    public function testConstructorAllowsValidValuesAndCombinedBitValues($value): void
    {
        $subject = $this->createSubject($value);
        static::assertEquals($value, $subject->getValue());
    }


    /**
     * @dataProvider isValidSupportsCombinedBitValuesDataProvider
     */
    public function testIsValidSupportsCombinedBitValues($value, bool $expected): void
    {
        $subject = $this->createSubject(1);
        $result = $subject::isValid($value);
        $expected ? static::assertTrue($result) : static::assertFalse($result);
    }

    public function testContainsChecksTheCorrectBits(): void
    {
        $subject = $this->createSubject(10);
        static::assertFalse($subject->contains($this->createSubject(1)));
        static::assertTrue($subject->contains($this->createSubject(2)));
        static::assertTrue($subject->contains($this->createSubject(8)));
        static::assertFalse($subject->contains($this->createSubject(11)));
    }

    /**
     * @dataProvider explodeDataProvider
     */
    public function testExplode($value, array $expected): void
    {
        $subject = $this->createSubject($value);
        self::assertEquals($subject->explode(), $expected);
    }

    /**
     * @dataProvider countDataProvider
     */
    public function testCount(int $value, int $expectedCount): void
    {
        $subject = $this->createSubject($value);
        self::assertEquals($subject->count(), $expectedCount);
    }

    private function createSubject($flag): object
    {
        return new class($flag) extends AbstractFlag
        {
            public const FLAG_1 = 1;
            public const FLAG_2 = 2;
            public const FLAG_3 = 8;
        };
    }
}

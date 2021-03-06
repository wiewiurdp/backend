<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Tests\Infrastructure\Condition\Calculator;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Condition\Domain\Condition\TextAttributeValueCondition;
use Ergonode\Condition\Infrastructure\Condition\Calculator\TextAttributeValueConditionCalculatorStrategy;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Value\Domain\ValueObject\StringValue;

/**
 */
class TextAttributeValueConditionCalculatorStrategyTest extends TestCase
{
    /**
     * @var MockObject|AttributeRepositoryInterface
     */
    private MockObject $repository;

    /**
     * @var TextAttributeValueConditionCalculatorStrategy
     */
    private TextAttributeValueConditionCalculatorStrategy $strategy;

    /**
     */
    protected function setUp(): void
    {
        $this->repository = $this->createMock(AttributeRepositoryInterface::class);
        $this->strategy = new TextAttributeValueConditionCalculatorStrategy($this->repository);
    }

    /**
     */
    public function testSupports(): void
    {
        $this->assertTrue($this->strategy->supports('TEXT_ATTRIBUTE_VALUE_CONDITION'));
        $this->assertFalse($this->strategy->supports('test'));
    }

    /**
     * @param string         $option
     * @param string         $expected
     * @param ValueInterface $value
     * @param bool           $result
     *
     * @dataProvider dataProvider
     */
    public function testCalculate(string $option, string $expected, ValueInterface $value, bool $result): void
    {
        $object = $this->createMock(AbstractProduct::class);
        $configuration = $this->createMock(TextAttributeValueCondition::class);
        $configuration
            ->expects($this->once())
            ->method('getAttribute')
            ->willReturn($this->createMock(AttributeId::class));
        $this
            ->repository
            ->expects($this->once())
            ->method('load')
            ->willReturn($this->createMock(AbstractAttribute::class));
        $configuration->expects($this->once())->method('getOption')->willReturn($option);
        $configuration->expects($this->once())->method('getValue')->willReturn($expected);
        $object->expects($this->once())->method('hasAttribute')->willReturn(true);
        $object->expects($this->once())->method('getAttribute')->willReturn($value);
        $this->assertSame($result, $this->strategy->calculate($object, $configuration));
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            [
                'option' => '~',
                'expected' => 'abcd',
                'value' => new TranslatableStringValue(new TranslatableString(['pl' => '1234'])),
                'result' => false,
            ],
            [
                'option' => '~',
                'expected' => 'abcd',
                'value' => new StringValue('1234'),
                'result' => false,
            ],
            [
                'option' => '~',
                'expected' => 'abcd',
                'value' => new StringValue('abcd'),
                'result' => true,
            ],
            [
                'option' => '~',
                'expected' => 'abcd',
                'value' => new  TranslatableStringValue(new TranslatableString(['pl' => 'abcd'])),
                'result' => true,
            ],
            [
                'option' => '~',
                'expected' => 'cd',
                'value' => new  TranslatableStringValue(new TranslatableString(['pl' => 'abcd'])),
                'result' => true,
            ],
            [
                'option' => '=',
                'expected' => '1234',
                'value' => new  TranslatableStringValue(new TranslatableString(['pl' => 'abcd'])),
                'result' => false,
            ],
            [
                'option' => '=',
                'expected' => 'abcd',
                'value' => new StringValue('1234'),
                'result' => false,
            ],
            [
                'option' => '=',
                'expected' => 'abcd',
                'value' => new StringValue('abcd'),
                'result' => true,
            ],
            [
                'option' => '=',
                'expected' => 'abcd',
                'value' => new  TranslatableStringValue(new TranslatableString(['pl' => 'abcd'])),
                'result' => true,
            ],
            [
                'option' => '=',
                'expected' => 'cd',
                'value' => new  TranslatableStringValue(new TranslatableString(['pl' => 'abcd'])),
                'result' => false,
            ],
        ];
    }
}

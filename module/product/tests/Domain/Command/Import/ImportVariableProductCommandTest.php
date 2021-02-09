<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Domain\Command\Import;

use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\Product\Domain\Command\Import\ImportVariableProductCommand;
use Ergonode\Core\Domain\ValueObject\TranslatableString;

class ImportVariableProductCommandTest extends TestCase
{
    public function testCommandCreation(): void
    {
        $importId = $this->createMock(ImportId::class);
        $sku = 'any sku';
        $template = 'code template';
        $categories = ['any category code'];
        $attributes = ['code' => $this->createMock(TranslatableString::class)];
        $bindings = ['and bind attribute code'];
        $children = ['any child sku'];

        $command = new ImportVariableProductCommand(
            $importId,
            $sku,
            $template,
            $categories,
            $bindings,
            $children,
            $attributes
        );
        self::assertSame($importId, $command->getImportId());
        self::assertSame($sku, $command->getSku());
        self::assertSame($template, $command->getTemplate());
        self::assertSame($categories, $command->getCategories());
        self::assertSame($attributes, $command->getAttributes());
        self::assertSame($bindings, $command->getBindings());
        self::assertSame($children, $command->getChildren());
    }
}
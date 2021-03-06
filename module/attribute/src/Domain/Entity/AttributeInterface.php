<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Entity;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\SharedKernel\Domain\AggregateId;

/**
 */
interface AttributeInterface
{
    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return AttributeCode
     */
    public function getCode(): AttributeCode;

    /**
     * @return AggregateId;
     */
    public function getId(): AggregateId;
}

<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Application\DependencyInjection\CompilerPass;

use Ergonode\Exporter\Application\Provider\UpdateExportProfileCommandBuilderProvider;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 */
class UpdateExportProfileCommandBuilderCompilerPass implements CompilerPassInterface
{
    public const TAG = 'export.export_profile.update_export_profile_builder_interface';

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->has(UpdateExportProfileCommandBuilderProvider::class)) {
            $this->processServices($container);
        }
    }

    /**
     * @param ContainerBuilder $container
     */
    private function processServices(ContainerBuilder $container): void
    {
        $arguments = [];
        $definition = $container->findDefinition(UpdateExportProfileCommandBuilderProvider::class);
        $strategies = $container->findTaggedServiceIds(self::TAG);

        foreach ($strategies as $id => $strategy) {
            $arguments[] = new Reference($id);
        }

        $definition->setArguments($arguments);
    }
}

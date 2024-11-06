<?php

namespace App\DependencyInjection;

use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DoctrineCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('doctrine.orm.default_configuration')) {
            return;
        }

        $definition = $container->getDefinition('doctrine.orm.default_configuration');

        // Ajouter les préférences de génération des identifiants
        $definition->addMethodCall('setIdentityGenerationPreferences', [
            [
                PostgreSQLPlatform::class => ClassMetadata::GENERATOR_TYPE_SEQUENCE,
            ],
        ]);
    }
}

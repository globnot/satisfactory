<?php

namespace App\Infrastructure\Doctrine;

use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\Mapping\ClassMetadata;

class DoctrineConfiguration
{
    public function __construct(Configuration $configuration)
    {
        // Définir les préférences de génération des identifiants
        $configuration->setIdentityGenerationPreferences([
            PostgreSQLPlatform::class => ClassMetadata::GENERATOR_TYPE_SEQUENCE,
        ]);
    }
}

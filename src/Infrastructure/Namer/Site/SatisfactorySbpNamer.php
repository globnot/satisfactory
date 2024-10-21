<?php

namespace App\Infrastructure\Namer\Site;

use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\NamerInterface;

class SatisfactorySbpNamer implements NamerInterface
{
    public function name($object, PropertyMapping $mapping): string
    {
        $file = $mapping->getFile($object);
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        return $originalName.'.sbp';
    }
}

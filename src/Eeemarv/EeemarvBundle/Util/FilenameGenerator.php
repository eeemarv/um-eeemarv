<?php

namespace Eeemarv\EeemarvBundle\Util;

use Gedmo\Uploadable\FilenameGenerator\FilenameGeneratorInterface;
use Eeemarv\EeemarvBundle\Util\UniqueIdGenerator; 

class FilenameGenerator implements FilenameGeneratorInterFace
{
    public static function generate($filename, $extension, $object = null)
    {
		$uniqueIdGenerator = new UniqueIdGenerator();
		return strtolower($uniqueIdGenerator->generate().$extension);
    }
}

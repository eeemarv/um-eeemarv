<?php

namespace Eeemarv\EeemarvBundle\Util;

use Oneup\UploaderBundle\Uploader\File\FileInterface;
use Oneup\UploaderBundle\Uploader\Naming\NamerInterface;
use Eeemarv\EeemarvBundle\Util\UniqueIdGenerator; 

class FileNamer implements NamerInterFace
{
    public function name(FileInterface $file)
    {
		$uniqueIdGenerator = new UniqueIdGenerator();
		return strtolower($uniqueIdGenerator->generate().'.'.$file->getExtension());
    }
}

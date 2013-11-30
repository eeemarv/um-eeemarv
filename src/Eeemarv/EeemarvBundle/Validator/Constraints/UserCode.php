<?php

namespace Eeemarv\EeemarvBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UserCode extends Constraint
{
    public $message = '"%string%" is not a valid Code';
    
    
    
}

<?php

namespace Eeemarv\EeemarvBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ActiveUserCode extends Constraint
{
    public $message = '"%string%" is not a valid Code';
    
    public function validatedBy()
	{
		return 'eeemarv_active_user_code_validator';
	}
    
    
    
}

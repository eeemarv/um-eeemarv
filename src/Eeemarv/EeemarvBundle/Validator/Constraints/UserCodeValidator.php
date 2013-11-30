<?php

namespace Eeemarv\EeemarvBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UserCodeValidator extends ConstraintValidator
{

    public function validate($value, Constraint $constraint)
    {
        if (!preg_match('/^[-a-z0-9]+$/', $value, $matches)) {
            $this->context->addViolation($constraint->message, array('%string%' => $value));
        }
    }
}

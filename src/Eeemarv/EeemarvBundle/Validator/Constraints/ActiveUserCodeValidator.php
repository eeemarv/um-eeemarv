<?php

namespace Eeemarv\EeemarvBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;

/*
 * Validates if the first part of the code is an active user (anything before a slash)
 */ 


class ActiveUserCodeValidator extends ConstraintValidator
{
    /**
     * @var EntityManager;
     */
    protected $em;		
	
    public function __construct(EntityManager $em)
    {
		$this->em = $em;
    }	
	
    public function validate($value, Constraint $constraint)
    {
		$repository = $em->getRepository('EeemarvBundle:User');
		
		$code = trim($value);
		$code = strtolower($code);
		list($code) = explode('/', $code); 

		$user = $repository->findOneByCode($code);

        if (!$user) {
            $this->context->addViolation($constraint->message, array('%string%' => $value));
        }
    }
    
    
}

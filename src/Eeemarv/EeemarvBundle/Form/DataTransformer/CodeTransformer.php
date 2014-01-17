<?php

namespace Eeemarv\EeemarvBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\ORM\EntityManager;

use Eeemarv\EeemarvBundle\Entity\User;

class CodeTransformer implements DataTransformerInterface
{
    /**
     * @var EntityManager;
     */
    protected $em;	
	
	protected $repo;
	
    public function __construct(EntityManager $em)
    {
		$this->em = $em;
		$this->repo = $em->getRepository('EeemarvBundle:User');		
    }

    /**
     * Transforms Code to Code+space+username.
     *
     * @param  User $user
     * @return string
     */
    public function transform($code)
    {
		$user = $this->repo->findOneBy(array('code' => $code));
		
        if (!$user || !$user->getCode() || !$user->getIsActive()) {
            return null;
        }
        
		return $code.' '.$user->getUsername();
    }

    /**
     * Transforms Code+space+username to code.
     *
     * @param  string $codeString
     * @return string
     */
    public function reverseTransform($codeString)
    {
		$codeString = trim($codeString);
		list($code) = explode(' ', $codeString);
		$code = strtolower($code);
		
		if(!ctype_alnum(str_replace('/', '', $code))) {
			throw new TransformationFailedException(sprintf(
			'Your code %s contains non-alfanumeric characters.', $code)
			);
		} 		

		list($localCode) = explode('/', $code);
		
		if (!$this->repo->findOneBy(array('code' => $localCode))){   // + active code (seperate transformer?) // + external codes 
			throw new TransformationFailedException(sprintf(
			'The local user with code %s does not exist.', $localCode)
			);			
		}	

        return $code;
    }
}

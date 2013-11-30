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
	
	
	
    public function __construct(EntityManager $em)
    {
		$this->em = $em;
    }

    /**
     * Transforms User to Code+space+username.
     *
     * @param  User $user
     * @return string
     */
    public function transform($user)
    {
        if (empty($user) || empty($user->getCode() || !($user->getActive())) {
            return null;
        }
        
		return $user->getCode().' '.$user->getUsername();
    }

    /**
     * Transforms Code+space+username to user.
     *
     * @param  string $codeString
     * @return User|null
     */
    public function reverseTransform($codeString)
    {
		$repository = $this->em->getRepository('EeemarvBundle:User');
		
		$codeString = trim($codeString);
		list($code) = explode(' ', $codeString);
		list($code) = explode('/', $code);
		$code = strtolower($code);
		
		$user = $repository->findOneBy(array('code' => $code));

        return $user;
    }
}

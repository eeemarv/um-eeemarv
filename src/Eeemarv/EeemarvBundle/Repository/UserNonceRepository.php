<?php

namespace Eeemarv\EeemarvBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Eeemarv\EeemarvBundle\Entity\UserNonce;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class UserNonceRepository extends EntityRepository
{
	
	private $duration = 300;
	
		
	public function cleanUp(integer $duration = null)
	{
		$duration = ($duration) ? $duration : $this->duration;		
		$nonceExpiredAt = new DateTime((time() - $duration), new \DateTimeZone('UTC'));		

		$numDeleted = $this->getEntityManager()
				->createQuery('delete from EeemarvBundle:UserNonce un where un.createdAt < :nonceExpiredAt')
				->setParameter('nonceExpiredAt', $nonceExpiredAt->format('Y-m-d H:i:s'))
				->execute();				
		// log $numDeleted
		
		return $this;		
	}

	public function verify(string $nonce = null, string $code = null)
	{
		if (!$nonce){
            throw new AuthenticationException('No nonce provided');				
		}	
		if (!$code){
            throw new AuthenticationException('No user-code provided');				
		}
		
		// we don't check for nonce-expiration here; we just clean them up reguraly with a cronjob.
		
		$em = $this->getEntityManager();		
		$userNonces = $em
			->createQuery('select un from EeemarvBundle:UserNonce un 
				join un.user u 
				where u.code = :code and un.nonce = :nonce')
			->setParameter('code', $code)
			->setParameter('nonce', $nonce)
			->getResult();
			
		if (sizeof($userNonces)){
			return false;
		}
		
		$user = $em->getRepository('EeemarvBundle:User')->findOneByCode($code);
        if (!$user) {
            // throw $this->createNotFoundException('Unable to find User entity.');
            return false; 
        }

		$userNonce = new UserNonce();
		$userNonce->setUser($user);
		$userNonce->setNonce($nonce);
		$em->persist($userNonce);
		$em->flush();
				
		return true;
	}  
}

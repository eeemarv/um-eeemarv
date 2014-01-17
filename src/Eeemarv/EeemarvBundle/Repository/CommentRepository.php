<?php

namespace Eeemarv\EeemarvBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Eeemarv\EeemarvBundle\Entity\Message;

class CommentRepository extends EntityRepository
{
	public function findAfter($datetime, Message $message)
	{
		if (!$datetime || !$message){
			return array();
		}
		
		$query = $this->getEntityManager()->createQueryBuilder()
			->select('c, u')
			->from('EeemarvBundle:Comment', 'c')
			->join('c.createdBy', 'u')
			->where('c.createdAt > :datetime')
			->andWhere('c.message = :message')
			->orderBy('c.createdAt', 'asc')
			->setParameter('datetime', $datetime)
			->setParameter('message', $message)
			->getQuery();	
		return $query->getResult();		
		
	}	
	
}

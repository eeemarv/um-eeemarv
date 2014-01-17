<?php

namespace Eeemarv\EeemarvBundle\Repository;

use Doctrine\ORM\EntityRepository;

use Eeemarv\EeemarvBundle\Entity\User;

class TransactionRepository extends EntityRepository
{
	
	public function findByUser(User $user)
	{
		$query = $this->getEntityManager()->createQueryBuilder()
			->select('t')
			->from('EeemarvBundle:Transaction', 't')
			->where('t.toUser = :toUser')
			->orWhere('t.fromUser = :fromUser')			
			->orderBy('t.transactionAt', 'desc')
			->setParameter('toUser', $user)
			->setParameter('fromUser', $user)
			->getQuery();	
		return $query->getResult();	
	}




	
}

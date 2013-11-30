<?php

namespace Eeemarv\EeemarvBundle\Repository;

use Doctrine\ORM\EntityRepository;



class CategoryRepository extends EntityRepository
{

/*
    public function findTree()
    {
		$query = $this->getEntityManager()->createQueryBuilder()
			->select('c.name, c.level, c.messageCount, c.left, c.right') 
			->from('EeemarvBundle:Category', 'c')
			->orderBy('c.left', 'ASC')			
			->getQuery();	
		return $query->getResult();
    } 
*/

	
	
}

<?php

namespace Eeemarv\EeemarvBundle\Repository;

//use Doctrine\ORM\EntityRepository;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;


class CategoryRepository extends NestedTreeRepository
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

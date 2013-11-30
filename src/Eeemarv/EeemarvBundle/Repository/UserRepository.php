<?php

namespace Eeemarv\EeemarvBundle\Repository;

// use Symfony\Component\Security\Core\User\UserInterface;
// use Symfony\Component\Security\Core\User\UserProviderInterface;
// use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
// use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
// use Doctrine\ORM\NoResultException;

use Doctrine\ORM\EntityRepository;



class UserRepository extends EntityRepository
{


    public function findMembers($latitude, $longitude)
    {
		$query = $this->getEntityManager()->createQueryBuilder()
			->select('u.code, u.slug, u.username, u.balance, count(m.id) as messageCount, u.systemAccount, u.quitting, GEO_DISTANCE(:latitude, :longitude, u.latitude, u.longitude) AS distance') 
			->from('EeemarvBundle:User', 'u')
			->leftJoin('u.messages', 'm')
			->where('u.active = 1')
			->groupBy('u.id')
			
			->orderBy('u.code', 'asc')
			->setParameter('latitude', $latitude)
			->setParameter('longitude', $longitude)
			->getQuery();	
		return $query->getResult();
    }
    
    public function findAjaxUsers()
    {
		$query = $this->getEntityManager()->createQueryBuilder()
			->select('u.code as cd, u.username as nm, (u.balanceLimit - u.balance) as mx') 
			->from('EeemarvBundle:User', 'u') 
			->where('u.active = 1')
			->getQuery();	
		return $query->getResult();
    }
    
    public function findHighestPresetCode()
    {
		$query = $this->getEntityManager()->createQueryBuilder()
			->select('max(u.code)') 
			->from('EeemarvBundle:User', 'u') 
			->where('u.active = 0')
			->andWhere('u.code like \'--%\'')
			->getQuery();	
		return $query->getSingleScalarResult();
    }    
        
    
     
}

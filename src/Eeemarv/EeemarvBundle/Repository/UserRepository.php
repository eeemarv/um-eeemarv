<?php

namespace Eeemarv\EeemarvBundle\Repository;

// use Symfony\Component\Security\Core\User\UserInterface;
// use Symfony\Component\Security\Core\User\UserProviderInterface;
// use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
// use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
// use Doctrine\ORM\NoResultException;

use Doctrine\ORM\EntityRepository;
use Eeemarv\EeemarvBundle\Entity\User;


class UserRepository extends EntityRepository
{


    public function findGeo($latitude, $longitude)
    {
		$query = $this->getEntityManager()->createQueryBuilder()
			->select('u.code, u.username, u.balance, count(m.id) as messageCount, 
				u.isSystemAccount, u.externalPassword, u.isLeaving, u.activatedAt, u.isActive,
				GEO_DISTANCE(:latitude, :longitude, u.latitude, u.longitude) AS distance') 
			->from('EeemarvBundle:User', 'u')
			->leftJoin('u.messages', 'm')
		//	->where('u.activated = 1')
			->groupBy('u.id')
			
			->orderBy('u.code', 'asc')
			->setParameter('latitude', $latitude)
			->setParameter('longitude', $longitude)
			->getQuery();	
		return $query->getResult();
    }
 
    public function getGeoQuery($latitude, $longitude)
    {
		$query = $this->getEntityManager()->createQueryBuilder()
			->select('u.code, u.username, u.balance, count(m.id) as messageCount, 
				u.isSystemAccount, u.externalPassword, u.isLeaving, u.activatedAt, u.isActive,
				GEO_DISTANCE(:latitude, :longitude, u.latitude, u.longitude) AS distance') 
			->from('EeemarvBundle:User', 'u')
			->leftJoin('u.messages', 'm')
		//	->where('u.activated = 1')
			->groupBy('u.id')
			
			->orderBy('u.code', 'asc')
			->setParameter('latitude', $latitude)
			->setParameter('longitude', $longitude)
			->getQuery();	
		return $query;
    } 
 
 
 
    
    public function getDistance(User $user1, User $user2)
    {
		$query = $this->getEntityManager()->createQueryBuilder()
			->select('GEO_DISTANCE(:latitude1, :longitude1, :latitude2, :longitude2) as distance')
			->from('EeemarvBundle:User', 'u') 
			->setParameter('latitude1', $user1->getLatitude())
			->setParameter('longitude1', $user1->getLongitude())
			->setParameter('latitude2', $user2->getLatitude())
			->setParameter('longitude2', $user2->getLongitude())
			->getQuery();
		$result = $query->getResult();
			
		return $result[0]['distance'];
	}
    
    public function findTypeaheadUsers()
    {
		$query = $this->getEntityManager()->createQueryBuilder()
			->select('u.code as c, 
					u.username as n, 
					u.balanceLimit as l, 
					u.balance as b, 
					u.isLeaving as le, 
					u.isSystemAccount as s,
					u.isExternal as e, 
					u.activatedAt as a') 
			->from('EeemarvBundle:User', 'u') 
			->where('u.isActive = 1')
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

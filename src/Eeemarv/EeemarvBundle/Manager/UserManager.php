<?php

namespace Eeemarv\EeemarvBundle\Manager;

use FOS\UserBundle\Doctrine\UserManager as FOSUserManager;
use FOS\UserBundle\Model\UserInterface;

use Eeemarv\EeemarvBundle\Util\UniqueIdGenerator;

class UserManager extends FOSUserManager
{

  

     /**
     * Derives a username from firstName and lastName or ensure uniqueness
     *
     * @param UserInterface $user
     *
     * @return string
     */
    public function presetUserName(UserInterface $user)
    {
		$firstName = trim(strtolower($user->getFirstName()));
		$lastName = trim(strtolower($user->getLastName()));
		for ($i = 0; $i < strlen($lastName); $i++){
			$newUsername = $firstName;
			$newUsername .= ($i) ? ' '.substr($lastName, 0, $i) : '';
			if (!$this->repository->findOneBy(array('usernameCanonical' => $newUsername))){
				return $newUsername;
			}	
		}
		$uniqueIdGenerator = new UniqueIdGenerator();
		return $firstName.' --'.substr(strtolower($uniqueIdGenerator->generateUniqueId()), 0, 4);
    }
    
     /**
     * Derives a code from firstName and lastName or ensure uniqueness
     *
     * @param UserInterface $user
     *
     * @return string
     */
    public function presetCode(UserInterface $user)
    {
		$highestCode = $this->repository->findHighestPresetCode();
		if ($highestCode){
			$newCode = trim($highestCode, '-');
			$newCode++;
			return '--'.$newCode;
		}
		return '--0';
    }    
    
    
      


    /**
     * Canonicalizes a string
     * @param string $string
     * @return string
     */
 /*   public function canonicalize($string)
    {
		$string = $this->usernameCanonicalizer->canonicalize($string);
        return (strlen($string)) ? $string : null;
    }    
  */


  
  
}

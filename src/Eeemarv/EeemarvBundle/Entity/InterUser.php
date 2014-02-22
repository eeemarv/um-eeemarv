<?php

namespace Eeemarv\EeemarvBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="inter_user")
 * @ORM\Entity(repositoryClass="Lets\TransactBundle\Repository\InterUserRepository")
 * @ORM\HasLifecycleCallbacks 
 */
class InterUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id = null;

  	/**
  	 * @ORM\OneToOne(targetEntity="User", mappedBy="interUser")
	 * @ORM\JoinColumn(name="user_id")
	 */
	protected $user;

  	/**
	 * @ORM\Column(type="string")
	 */
	protected $username;

	/**
	* @ORM\Column(name="string", length=20)
	 */
	protected $postalCode;

	/**
	* @ORM\Column(type="decimal", precision=9, scale=6)
	 */
	protected $latitude;	
	
	/**
	* @ORM\Column(type="decimal", precision=9, scale=6)
	 */
	protected $longitude;

/* Account */

 	/**
	 * @ORM\Column(type="string", length=80, unique=true)
	 */
	protected $code;

	/**
	 * @ORM\Column(type="integer")
	 */
	protected $balance = 0;
	
	/**
	 * @ORM\Column(type="integer", name="balance_limit")
	 */
	protected $balanceLimit = 0;	

	/**
	 * @ORM\Column(type="boolean", name="system_account")
	 */
	protected $systemAccount = false;
	
	/**
	 * @ORM\Column(type="boolean", name="connector_group")
	 */
	protected $connectorGroup = false;	
	
	/**
	 * @ORM\Column(type="boolean", name="lets_group")
	 */
	protected $letsGroup = false;	
	
	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $isLeaving = false;
	
	/**
	 * @ORM\Column(type="boolean", name="force_positive")
	 */
	protected $forcePositive = false;	
		
	
/* Image */ 

	private $tempImagePath;

    /**
     * @Assert\Image(maxSize="500000")
     */	
	private $image;

    /**
     * @ORM\Column(type="string", name="image_path", nullable=true)
     */
    private $imagePath = null; 	
	

/* Created / Updated */

	/**
	 * @Gedmo\Timestampable(on="create")
	 * @ORM\Column(type="datetime", name="created_at", nullable=true)
	 */
	protected $createdAt = null;

	/**
	 * @Gedmo\Timestampable(on="update")
	 * @ORM\Column(type="datetime", name="updated_at", nullable=true)
	 */
	protected $updatedAt = null;
		

	public function __construct()
    {
                              
    }


    public function getAbsoluteImagePath()
    {
        return (null === $this->imagePath) ? null : $this->getUploadRootDir().'/'.$this->imagePath;
    }

    public function getWebImagePath()
    {
        return (null === $this->imagePath) ? null : $this->getImageUploadDir().'/'.$this->imagePath;
    }
    
    public function getRelativeImagePath()
    {
        return (null === $this->imagePath) ? null : '/..'.$this->getImageUploadDir().'/'.$this->imagePath;
    }    

    protected function getImageUploadRootDir()
    {
        return __DIR__.'/../../../../web'.$this->getImageUploadDir();
    }

    protected function getImageUploadDir()
    {
        return '/uploads/images/users';
    }




/** 
 * getters and setters
 * 
 */

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
 
    


    public function __toString()
    {
		$code = ($this->code) ? $this->getCode . ' ' : '';
        return $code . $this->username;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     * @return User
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    
        return $this;
    }

    /**
     * Get latitude
     *
     * @return float 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     * @return User
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    
        return $this;
    }

    /**
     * Get longitude
     *
     * @return float 
     */
    public function getLongitude()
    {
        return $this->longitude;
    }



    /**
     * Set code
     *
     * @param string $code
     * @return User
     */
    public function setCode($code)
    {
        $this->code = $code;
    
        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set balance		
     *
     * @param integer $balance
     * @return User
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;
    
        return $this;
    }

    /**
     * Get balance
     *
     * @return integer 
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * Set balanceLimit
     *
     * @param integer $balanceLimit
     * @return User
     */
    public function setBalanceLimit($balanceLimit)
    {
        $this->balanceLimit = $balanceLimit;
    
        return $this;
    }

    /**
     * Get balanceLimit
     *
     * @return integer 
     */
    public function getBalanceLimit()
    {
        return $this->balanceLimit;
    }



    /**
     * Set systemAccount
     *
     * @param boolean $systemAccount
     * @return User
     */
    public function setSystemAccount($systemAccount)
    {
        $this->systemAccount = $systemAccount;
    
        return $this;
    }

    /**
     * Get systemAccount
     *
     * @return boolean 
     */
    public function getSystemAccount()
    {
        return $this->systemAccount;
    }

    /**
     * Set quitting
     *
     * @param boolean $quitting
     * @return User
     */
    public function setQuitting($quitting)
    {
        $this->quitting = $quitting;
    
        return $this;
    }

    /**
     * Get quitting
     *
     * @return boolean 
     */
    public function getQuitting()
    {
        return $this->quitting;
    }

    /**
     * Set forcePositive
     *
     * @param boolean $forcePositive
     * @return User
     */
    public function setForcePositive($forcePositive)
    {
        $this->forcePositive = $forcePositive;
    
        return $this;
    }

    /**
     * Get forcePositive
     *
     * @return boolean 
     */
    public function getForcePositive()
    {
        return $this->forcePositive;
    }



    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return User
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return User
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }



    /**
     * Set imagePath
     *
     * @param string $imagePath
     * @return User
     */
    public function setImagePath($imagePath)
    {
        $this->imagePath = $imagePath;

        return $this;
    }

    /**
     * Get imagePath
     *
     * @return string 
     */
    public function getImagePath()
    {
        return $this->imagePath;
    }


    /**
     * Set username
     *
     * @param string $username
     * @return InterUser
     */
    public function setUsername($username)
    {
        $this->username = $username;
    
        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set postalCode
     *
     * @param string $postalCode
     * @return InterUser
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
    
        return $this;
    }

    /**
     * Get postalCode
     *
     * @return string 
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Set connectorGroup
     *
     * @param boolean $connectorGroup
     * @return InterUser
     */
    public function setConnectorGroup($connectorGroup)
    {
        $this->connectorGroup = $connectorGroup;
    
        return $this;
    }

    /**
     * Get connectorGroup
     *
     * @return boolean 
     */
    public function getConnectorGroup()
    {
        return $this->connectorGroup;
    }

    /**
     * Set letsGroup
     *
     * @param boolean $letsGroup
     * @return InterUser
     */
    public function setLetsGroup($letsGroup)
    {
        $this->letsGroup = $letsGroup;
    
        return $this;
    }

    /**
     * Get letsGroup
     *
     * @return boolean 
     */
    public function getLetsGroup()
    {
        return $this->letsGroup;
    }

    /**
     * Set user
     *
     * @param \Lets\TransactBundle\Entity\User $user
     * @return InterUser
     */
    public function setUser(\Lets\TransactBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \Lets\TransactBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}

<?php

namespace Eeemarv\EeemarvBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table(name="user_nonces")
 * @ORM\Entity(repositoryClass="Eeemarv\EeemarvBundle\Entity\UserNonceRepository")
 */
class UserNonce
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id = null;

	/**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="nonces")
	 * @ORM\JoinColumn(name="user_id")
	 */
	protected $user;

    /**
	 * @var string $nonce
	 * @ORM\Column()
	 */
    private $nonce;	

	/**
	 * @Gedmo\Timestampable(on="create")
	 * @ORM\Column(type="datetime", name="created_at")
	 */
	protected $createdAt;


    public function __construct()
    {      
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    

    /**
     * Set nonce
     *
     * @param string $nonce
     * @return UserNonce
     */
    public function setNonce($nonce)
    {
        $this->nonce = $nonce;
    
        return $this;
    }

    /**
     * Get nonce
     *
     * @return string 
     */
    public function getNonce()
    {
        return $this->nonce;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return UserNonce
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
     * Set user
     *
     * @param \Eeemarv\EeemarvBundle\Entity\User $user
     * @return UserNonce
     */
    public function setUser(\Eeemarv\EeemarvBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \Eeemarv\EeemarvBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}

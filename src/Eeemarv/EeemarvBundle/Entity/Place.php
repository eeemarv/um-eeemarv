<?php

namespace Eeemarv\EeemarvBundle\Entity;

use Gedmo\Translatable\Translatable;
use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Vich\GeographicalBundle\Annotation as Vich;

/**
 * @ORM\Table(name="places")
 * @ORM\Entity(repositoryClass="Eeemarv\EeemarvBundle\Repository\PlaceRepository")
 * @Gedmo\TranslationEntity(class="Eeemarv\EeemarvBundle\Entity\PlaceTranslation")
 * @Vich\Geographical(on="update") 
 */
class Place implements Translatable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;   
    
	/**
	 * @ORM\Column(type="string", length=20, name="postal_code")
	 * @Assert\NotBlank
	 */
	protected $postalCode;

	/**
	 * @Gedmo\Translatable
	 * @ORM\Column(type="string")
	 */
	protected $name;

	/**
	 * @ORM\Column(type="string", length=2, name="country")
	 * @Assert\NotBlank
	 */
	protected $country;

	/**
	* @ORM\Column(type="decimal", precision=9, scale=6, nullable=true)
	 */
	protected $latitude = null;	
	
	/**
	* @ORM\Column(type="decimal", precision=9, scale=6, nullable=true)
	 */
	protected $longitude = null;

	/**
	 * @ORM\OneToMany(targetEntity="User", mappedBy="place")
	 */
	protected $users;
	
	/**
	* @ORM\Column(type="boolean")
	 */
	protected $external = false;	
	
	
	/**
	 * @ORM\Column(type="datetime", name="created_at", nullable=true)
	 * @Gedmo\Timestampable(on="create")
	 */
	protected $createdAt = null;	
	
	/**
	 * @Gedmo\Blameable(on="create")
	 * @ORM\ManyToOne(targetEntity="User")
	 * @ORM\JoinColumn(name="created_by", nullable=true)
	 */
	protected $createdBy = null;	

	/**
	 * @ORM\Column(type="datetime", name="updated_at", nullable=true)
	 * @Gedmo\Timestampable(on="update")
	 */
	protected $updatedAt = null;
	
	/**
	 * @Gedmo\Blameable(on="update")
	 * @ORM\ManyToOne(targetEntity="User")
	 * @ORM\JoinColumn(name="updated_by", nullable=true)
	 */
	protected $updatedBy = null;
 
     /**
     * @Gedmo\Locale
     */
    private $locale;
    
    /**
	* @ORM\OneToMany(targetEntity="PlaceTranslation", mappedBy="object", cascade={"persist", "remove"})
	*/
    private $translations;
   
     

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->translations = new ArrayCollection();                 
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
     * Set postalCode
     *
     * @param string $postalCode
     * @return Place
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
     * Set country
     *
     * @param string $country
     * @return Place
     */
    public function setCountry($country)
    {
        $this->country = $country;
    
        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }


    /**
     * Set latitude
     *
     * @param float $latitude
     * @return Place
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
     * @return Place
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
     * Add users
     *
     * @param \Eeemarv\EeemarvBundle\Entity\User $users
     * @return Place
     */
    public function addUser(\Eeemarv\EeemarvBundle\Entity\User $users)
    {
        $this->users[] = $users;
    
        return $this;
    }

    /**
     * Remove users
     *
     * @param \Eeemarv\EeemarvBundle\Entity\User $users
     */
    public function removeUser(\Eeemarv\EeemarvBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set external
     *
     * @param boolean $external
     * @return Place
     */
    public function setExternal($external)
    {
        $this->external = $external;
    
        return $this;
    }

    /**
     * Get external
     *
     * @return boolean 
     */
    public function getExternal()
    {
        return $this->external;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Place
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
     * Set createdBy
     *
     * @param \Eeemarv\EeemarvBundle\Entity\User $createdBy
     * @return Place
     */
    public function setCreatedBy(\Eeemarv\EeemarvBundle\Entity\User $createdBy = null)
    {
        $this->createdBy = $createdBy;
    
        return $this;
    }

    /**
     * Get createdBy
     *
     * @return \Eeemarv\EeemarvBundle\Entity\User 
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }


    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Place
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
     * Set updatedBy
     *
     * @param \Eeemarv\EeemarvBundle\Entity\User $updatedBy
     * @return Place
     */
    public function setUpdatedBy(\Eeemarv\EeemarvBundle\Entity\User $updatedBy = null)
    {
        $this->updatedBy = $updatedBy;
    
        return $this;
    }

    /**
     * Get updatedBy
     *
     * @return \Eeemarv\EeemarvBundle\Entity\User 
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }
    
     /**
     * Add translations
     *
     * @param \Eeemarv\EeemarvBundle\Entity\PlaceTranslation $translations
     * @return Place
     */
    public function addTranslation(\Eeemarv\EeemarvBundle\Entity\PlaceTranslation $translations)
    {
        $this->translations[] = $translations;
    
        return $this;
    }

    /**
     * Remove translations
     *
     * @param \Eeemarv\EeemarvBundle\Entity\PlaceTranslation $translations
     */
    public function removeTranslation(\Eeemarv\EeemarvBundle\Entity\PlaceTranslation $translations)
    {
        $this->translations->removeElement($translations);
    }

    /**
     * Get translations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTranslations()
    {
        return $this->translations;
    }   
      
    
     /**
     * @Vich\GeographicalQuery
     * This method builds the location to query for coordinates.
     */
    public function getLocation()
    {
        return sprintf('%s, %s %s',
            $this->getName(),						
            $this->country,
            $this->postalCode);
    }
    
    public function __toString()
    {
        return $this->postalCode . ' ' . $this->getName() . ' ' . $this->country;  
    }    
    



    /**
     * Set name
     *
     * @param string $name
     * @return Place
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
}

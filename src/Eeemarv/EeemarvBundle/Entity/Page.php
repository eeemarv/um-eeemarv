<?php

namespace Eeemarv\EeemarvBundle\Entity;

use Gedmo\Translatable\Translatable;
use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * ORM\Table(name="pages")
 * ORM\Entity(repositoryClass="Gedmo\Sortable\Entity\Repository\SortableRepository")
 * ORM\Entity(repositoryClass="Eeemarv\EeemarvBundle\Repository\PageRepository")
 * Gedmo\TranslationEntity(class="Eeemarv\EeemarvBundle\Entity\PageTranslation")
 * Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Page implements Translatable
{
    /**
     * ORM\Id
     * ORM\Column(type="integer")
     * ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id = null;

	/**
	 * Gedmo\Translatable
	 * ORM\Column(type="string")
	 */
	protected $subject;
	
	/**
	 * Gedmo\Translatable
	 * Gedmo\Slug(fields={"subject"}) 
	 * ORM\Column(type="string", unique=true)
	 */
	protected $slug;
	
	/**
	 * Gedmo\SortablePosition
	 * ORM\Column(type="integer")
	 * 
	 */
	protected $position;
		

	/**
	 * Assert\NotBlank
	 * Gedmo\Translatable
	 * ORM\Column(type="text")
	 */
	protected $content;	


	/**
	 * ORM\Column(type="boolean")
	 */
	protected $published = false;

	/**
	 * ORM\Column(type="boolean")
	 */
	protected $protected = false;

	/**
	 * ORM\OneToMany(targetEntity="PageImage", mappedBy="page")
	 */
	protected $images;

	/**
	 * Gedmo\Timestampable(on="create")
	 * ORM\Column(type="datetime", name="created_at", nullable=true)
	 */
	protected $createdAt = null;

	/**
	 * Gedmo\Blameable(on="create")
	 * ORM\ManyToOne(targetEntity="User")
	 * ORM\JoinColumn(name="created_by")
	 */
	protected $createdBy;

	/**
	 * Gedmo\Timestampable(on="update")
	 * ORM\Column(type="datetime", name="updated_at", nullable=true)
	 */
	protected $updatedAt = null;
		
	/**
	 * Gedmo\Blameable(on="update")
	 * ORM\ManyToOne(targetEntity="User")
	 * ORM\JoinColumn(name="updated_by")
	 */
	protected $updatedBy;
	
    /**
     * ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    protected $deletedAt;	
	
	
    /**
     * @Gedmo\Locale
     */
    private $locale;	
	

    /**
	* @ORM\OneToMany(targetEntity="PageTranslation", mappedBy="object", cascade={"persist", "remove"})
	*/
    private $translations;
    

    public function __construct()
    {
        $this->translations = new ArrayCollection(); 
        $this->images = new ArrayCollection();                                        
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
     * Set published
     *
     * @param boolean $published
     * @return Page
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean 
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set protected
     *
     * @param boolean $protected
     * @return Page
     */
    public function setProtected($protected)
    {
        $this->protected = $protected;

        return $this;
    }

    /**
     * Get protected
     *
     * @return boolean 
     */
    public function getProtected()
    {
        return $this->protected;
    }





    /**
     * Set position
     *
     * @param integer $position
     * @return Page
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Add images
     *
     * @param \Eeemarv\EeemarvBundle\Entity\PageImage $images
     * @return Page
     */
    public function addImage(\Eeemarv\EeemarvBundle\Entity\PageImage $images)
    {
        $this->images[] = $images;

        return $this;
    }

    /**
     * Remove images
     *
     * @param \Eeemarv\EeemarvBundle\Entity\PageImage $images
     */
    public function removeImage(\Eeemarv\EeemarvBundle\Entity\PageImage $images)
    {
        $this->images->removeElement($images);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getImages()
    {
        return $this->images;
    }


    /**
     * Add transactions
     *
     * @param \Eeemarv\EeemarvBundle\Entity\Transaction $transactions
     * @return Page
     */
    public function addTransaction(\Eeemarv\EeemarvBundle\Entity\Transaction $transactions)
    {
        $this->transactions[] = $transactions;

        return $this;
    }

    /**
     * Remove transactions
     *
     * @param \Eeemarv\EeemarvBundle\Entity\Transaction $transactions
     */
    public function removeTransaction(\Eeemarv\EeemarvBundle\Entity\Transaction $transactions)
    {
        $this->transactions->removeElement($transactions);
    }

    /**
     * Get transactions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTransactions()
    {
        return $this->transactions;
    }




    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Page
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
     * @return Page
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
     * @return Page
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
     * @return Page
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
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     * @return Page
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;
        
        return $this;
    }
    
    /**
     * Get deletedAt
     *
     * @return \DateTime 
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }



    /**
     * Add translations
     *
     * @param \Eeemarv\EeemarvBundle\Entity\PageTranslation $translations
     * @return Page
     */
    public function addTranslation(\Eeemarv\EeemarvBundle\Entity\PageTranslation $translations)
    {
        $this->translations[] = $translations;

        return $this;
    }

    /**
     * Remove translations
     *
     * @param \Eeemarv\EeemarvBundle\Entity\PageTranslation $translations
     */
    public function removeTranslation(\Eeemarv\EeemarvBundle\Entity\PageTranslation $translations)
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
     * Set subject
     *
     * @param string $subject
     * @return Page
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    
        return $this;
    }

    /**
     * Get subject
     *
     * @return string 
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Page
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    
        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Page
     */
    public function setContent($content)
    {
        $this->content = $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }


}

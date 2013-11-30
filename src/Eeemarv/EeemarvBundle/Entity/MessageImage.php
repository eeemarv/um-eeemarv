<?php

namespace Eeemarv\EeemarvBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity 
 * @ORM\Table(name="message_images")
 * @Gedmo\Uploadable(path="/uploads/images/messages", filenameGenerator="SHA1", maxSize="2000000")
 */
class MessageImage
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id = null;

	/**
	 * @ORM\ManyToOne(targetEntity="Message", inversedBy="images")
	 * @ORM\JoinColumn(name="message_id")
	 */
	protected $message;
	
	/**
	 * @ORM\Column(name="path", type="string")
	 * @Gedmo\UploadableFilePath
	 */
	protected $path;	

	/**
	 * @ORM\Column(name="size", type="decimal")
	 * @Gedmo\UploadableFileSize
	 */
	protected $size;
	
	/**
	 * @ORM\Column(name="mime_type", type="string")
	 * @Gedmo\UploadableFileMimeType
	 */
	protected $mimeType;	
	
	
	/**
	 * @Gedmo\Timestampable(on="create")
	 * @ORM\Column(type="datetime", name="created_at", nullable=true)
	 */
	protected $createdAt = null;
	
	/**
	 * @Gedmo\Blameable(on="create")
	 * @ORM\ManyToOne(targetEntity="User")
	 * @ORM\JoinColumn(name="updated_by", nullable=true)
	 */
	protected $createdBy = null;	


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
     * Set path
     *
     * @param string $path
     * @return MessageImage
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set size
     *
     * @param float $size
     * @return MessageImage
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return float 
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set mimeType
     *
     * @param string $mimeType
     * @return MessageImage
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * Get mimeType
     *
     * @return string 
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return MessageImage
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
     * @return MessageImage
     */
    public function setCreatedBy($createdBy)
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
     * Set message
     *
     * @param \Eeemarv\EeemarvBundle\Entity\Message $message
     * @return MessageImage
     */
    public function setMessage(\Eeemarv\EeemarvBundle\Entity\Message $message = null)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return \Eeemarv\EeemarvBundle\Entity\Message 
     */
    public function getMessage()
    {
        return $this->message;
    }
}

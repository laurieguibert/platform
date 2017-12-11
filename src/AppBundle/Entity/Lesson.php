<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Lesson
 *
 * @ORM\Table(name="lesson")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LessonRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Lesson
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=150, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="duration", type="integer")
     */
    private $duration;

    /**
     * @var bool
     *
     * @ORM\Column(name="certificate", type="boolean")
     */
    private $certificate;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\LessonType")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lesson_type;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\DurationType")
     * @ORM\JoinColumn(nullable=false)
     */
    private $duration_type;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Sector")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sector;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Level")
     * @ORM\JoinColumn(nullable=false)
     */
    private $level;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="lessons")
     */
    private $users;

    public function __construct()
    {
        $this->created_at = new \DateTime("now");
        $this->updated_at = null;
        $this->users = new ArrayCollection();
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Lesson
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

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Lesson
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     *
     * @return Lesson
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set certificate
     *
     * @param boolean $certificate
     *
     * @return Lesson
     */
    public function setCertificate($certificate)
    {
        $this->certificate = $certificate;

        return $this;
    }

    /**
     * Get certificate
     *
     * @return bool
     */
    public function getCertificate()
    {
        return $this->certificate;
    }

    /**
     * Set lessonType
     *
     * @param \AppBundle\Entity\LessonType $lessonType
     *
     * @return Lesson
     */
    public function setLessonType(\AppBundle\Entity\LessonType $lessonType)
    {
        $this->lesson_type = $lessonType;

        return $this;
    }

    /**
     * Get lessonType
     *
     * @return \AppBundle\Entity\LessonType
     */
    public function getLessonType()
    {
        return $this->lesson_type;
    }

    /**
     * Set durationType
     *
     * @param \AppBundle\Entity\DurationType $durationType
     *
     * @return Lesson
     */
    public function setDurationType(\AppBundle\Entity\DurationType $durationType)
    {
        $this->duration_type = $durationType;

        return $this;
    }

    /**
     * Get durationType
     *
     * @return \AppBundle\Entity\DurationType
     */
    public function getDurationType()
    {
        return $this->duration_type;
    }

    /**
     * Set sector
     *
     * @param \AppBundle\Entity\Sector $sector
     *
     * @return Lesson
     */
    public function setSector(\AppBundle\Entity\Sector $sector)
    {
        $this->sector = $sector;

        return $this;
    }

    /**
     * Get sector
     *
     * @return \AppBundle\Entity\Sector
     */
    public function getSector()
    {
        return $this->sector;
    }

    /**
     * Set level
     *
     * @param \AppBundle\Entity\Level $level
     *
     * @return Lesson
     */
    public function setLevel(\AppBundle\Entity\Level $level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return \AppBundle\Entity\Level
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Lesson
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Lesson
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @ORM\PreUpdate
     */
    public function updateDate()
    {
        $this->setUpdatedAt(new \Datetime());
    }

    /**
     * Add user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Lesson
     */
    public function addUser(\AppBundle\Entity\User $user)
    {
        $this->users[] = $user;
        $user->addLesson($this);

        return $this;
    }

    /**
     * Remove user
     *
     * @param \AppBundle\Entity\User $user
     */
    public function removeUser(\AppBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
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
}

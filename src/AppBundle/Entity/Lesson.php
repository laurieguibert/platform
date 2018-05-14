<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Lesson
 *
 * @ORM\Table(name="lesson")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LessonRepository")
 * @ORM\HasLifecycleCallbacks
 * @Vich\Uploadable
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
     * @Assert\NotBlank(message="Please enter a lesson's name.")
     * @Assert\Length(
     *      min = 10,
     *      max = 255,
     *      minMessage = "Your lesson's name must be at least {{ limit }} characters long",
     *      maxMessage = "Your lesson's name cannot be longer than {{ limit }} characters"
     * )
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     * @Assert\NotBlank(message="Please enter a description.")
     * @Assert\Length(
     *      min = 10,
     *      max = 255,
     *      minMessage = "Your description must be at least {{ limit }} characters long",
     *      maxMessage = "Your description cannot be longer than {{ limit }} characters"
     * )
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="duration", type="integer")
     * @Assert\NotBlank(message="Please enter a duration.")
     * @Assert\Type(
     *     type="integer",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private $duration;

    /**
     * @var bool
     *
     * @ORM\Column(name="certificate", type="boolean")
     * @Assert\NotBlank(message="Please enter a duration.")
     * @Assert\Type(
     *     type="bool",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Summary")
     * @ORM\JoinColumn(nullable=false)
     */
    private $summary;

    /**
     * @var array
     *
     * @ORM\Column(name="tags", type="array", length=255)
     */
    private $tags;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\UserLesson", mappedBy="lesson", cascade={"persist"})
     */
    private $userLesson;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="lesson_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt;

    public function __construct()
    {
        $this->created_at = new \DateTime("now");
        $this->updated_at = null;
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
        return $this->updatedAt;
    }

    /**
     * @ORM\PreUpdate
     */
    public function updateDate()
    {
        $this->setUpdatedAt(new \Datetime());
    }

    /**
     * Add userLesson
     *
     * @param \AppBundle\Entity\UserLesson $userLesson
     *
     * @return Lesson
     */
    public function addUserLesson(\AppBundle\Entity\UserLesson $userLesson)
    {
        $this->userLesson[] = $userLesson;

        return $this;
    }

    /**
     * Remove userLesson
     *
     * @param \AppBundle\Entity\UserLesson $userLesson
     */
    public function removeUserLesson(\AppBundle\Entity\UserLesson $userLesson)
    {
        $this->userLesson->removeElement($userLesson);
    }

    /**
     * Get userLesson
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserLesson()
    {
        return $this->userLesson;
    }

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;
        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set summary
     *
     * @param \AppBundle\Entity\Summary $summary
     *
     * @return Lesson
     */
    public function setSummary(\AppBundle\Entity\Summary $summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * Get summary
     *
     * @return \AppBundle\Entity\Summary
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set tags
     *
     * @param array $tags
     *
     * @return Lesson
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Get tags
     *
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }
}

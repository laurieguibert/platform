<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserLesson
 *
 * @ORM\Table(name="user_lesson")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserLessonRepository")
 */
class UserLesson
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
     * @var \DateTime
     *
     * @ORM\Column(name="startedAt", type="datetime")
     */
    private $startedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endedAt", type="datetime", nullable=true)
     */
    private $endedAt;

    /**
     * @var bool
     *
     * @ORM\Column(name="certificated", type="boolean")
     */
    private $certificated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="certificatedDate", type="datetime", nullable=true)
     */
    private $certificatedDate;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="userLesson")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Lesson", inversedBy="userLesson")
     * @ORM\JoinColumn(name="lesson_id", referencedColumnName="id")
     */
    private $lesson;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\UserLessonStatus")
     * @ORM\JoinColumn(nullable=false)
     */
    private $status;

    /**
     * @var boolean
     *
     * @ORM\Column(name="favorite", type="boolean", nullable=false)
     */
    private $favorite;

    public function __construct (){
        $this->favorite = 0;
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
     * Set startedAt
     *
     * @param \DateTime $startedAt
     *
     * @return UserLesson
     */
    public function setStartedAt($startedAt)
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    /**
     * Get startedAt
     *
     * @return \DateTime
     */
    public function getStartedAt()
    {
        return $this->startedAt;
    }

    /**
     * Set endedAt
     *
     * @param \DateTime $endedAt
     *
     * @return UserLesson
     */
    public function setEndedAt($endedAt)
    {
        $this->endedAt = $endedAt;

        return $this;
    }

    /**
     * Get endedAt
     *
     * @return \DateTime
     */
    public function getEndedAt()
    {
        return $this->endedAt;
    }

    /**
     * Set certificated
     *
     * @param boolean $certificated
     *
     * @return UserLesson
     */
    public function setCertificated($certificated)
    {
        $this->certificated = $certificated;

        return $this;
    }

    /**
     * Get certificated
     *
     * @return bool
     */
    public function getCertificated()
    {
        return $this->certificated;
    }

    /**
     * Set certificatedDate
     *
     * @param \DateTime $certificatedDate
     *
     * @return UserLesson
     */
    public function setCertificatedDate($certificatedDate)
    {
        $this->certificatedDate = $certificatedDate;

        return $this;
    }

    /**
     * Get certificatedDate
     *
     * @return \DateTime
     */
    public function getCertificatedDate()
    {
        return $this->certificatedDate;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return UserLesson
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set lesson
     *
     * @param \AppBundle\Entity\Lesson $lesson
     *
     * @return UserLesson
     */
    public function setLesson(\AppBundle\Entity\Lesson $lesson = null)
    {
        $this->lesson = $lesson;

        return $this;
    }

    /**
     * Get lesson
     *
     * @return \AppBundle\Entity\Lesson
     */
    public function getLesson()
    {
        return $this->lesson;
    }

    /**
     * Set favorite
     *
     * @param boolean $favorite
     *
     * @return UserLesson
     */
    public function setFavorite($favorite)
    {
        $this->favorite = $favorite;

        return $this;
    }

    /**
     * Get favorite
     *
     * @return boolean
     */
    public function getFavorite()
    {
        return $this->favorite;
    }

    /**
     * Set status
     *
     * @param \AppBundle\Entity\UserLessonStatus $status
     *
     * @return UserLesson
     */
    public function setStatus(\AppBundle\Entity\UserLessonStatus $status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \AppBundle\Entity\UserLessonStatus
     */
    public function getStatus()
    {
        return $this->status;
    }
}

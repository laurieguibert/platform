<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @UniqueEntity("email", message="This email is already used.")
 * @UniqueEntity("username", message="This username is already used.")
 * @Vich\Uploadable
 *
 */
class User implements UserInterface
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
     * @ORM\Column(name="firstname", type="string", length=50, nullable=true)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=50, nullable=true)
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=50, unique=true, nullable=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     * @Assert\NotBlank(message="Please enter a password.")
     */
    private $password;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Country")
     * @ORM\JoinColumn(nullable=true)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     * @Assert\NotBlank(message="Please enter an email.")
     * @Assert\Email(message="The email {{ value }} is not a valid email.")
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=1000, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="linkedin", type="string", length=50, nullable=true)
     */
    private $linkedin;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean")
     */
    private $status;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="array", length=255)
     */
    private $roles;

    /**
     * @var \DateTime $lastLogin
     *
     * @ORM\Column(type="datetime", nullable = true)
     */
    protected $lastLogin;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Situation")
     * @ORM\JoinColumn(nullable=true)
     */
    private $situation;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Sector")
     * @ORM\JoinColumn(nullable=true)
     */
    private $sector;

    /**
     * @var string
     *
     * @ORM\Column(name="current_position", type="string", length=150, nullable=true)
     */
    private $currentPosition;

    /**
     * @var string
     *
     * @ORM\Column(name="school", type="string", length=100, nullable=true)
     */
    private $school;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\StudiesLevel")
     * @ORM\JoinColumn(nullable=true)
     */
    private $studiesLevel;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="user_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $cvName;

    /**
     * @Vich\UploadableField(mapping="cv_files", fileNameProperty="cvName")
     * @var File
     */
    private $cvFile;

    /**
     * @var \DateTime $askTrainer
     *
     * @ORM\Column(type="datetime", nullable = true, precision=6)
     */
    private $askTrainer;

    /**
     * @var \DateTime $answerTrainer
     *
     * @ORM\Column(type="datetime", nullable = true, precision=6)
     */
    private $answerTrainer;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\UserLesson", mappedBy="user", cascade={"persist"})
     */
    private $userLesson;

    /**
     * @var \DateTime $createdAt
     *
     * @ORM\Column(type="datetime", precision=6)
     */
    protected $createdAt;

    /**
     * @var \DateTime $updatedAt
     *
     * @ORM\Column(type="datetime", nullable = true, precision=6)
     */
    protected $updatedAt;

    public function __construct() {
        $this->firstname = null;
        $this->lastname = null;
        $this->username = null;
        $this->roles = array("ROLE_USER");
        $this->status = 0;
        $this->createdAt = new \DateTime("now");
        $this->updatedAt = null;
        $this->description = null;
        $this->linkedin = null;
        $this->school = null;
        $this->sector = null;
        $this->country = null;
        $this->studiesLevel = null;
        $this->askTrainer = null;
        $this->answerTrainer = null;
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
     * Set username
     *
     * @param string $username
     *
     * @return User
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
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set roles
     *
     * @param array $roles
     *
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get roles
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }


    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return User
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    //Salt is not used
    public function getSalt()
    {
        return null;
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
     * Set lastLogin
     *
     * @param \DateTime $lastLogin
     *
     * @return User
     */
    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    /**
     * Get lastLogin
     *
     * @return \DateTime
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * Set situation
     *
     * @param \AppBundle\Entity\Situation $situation
     *
     * @return User
     */
    public function setSituation(\AppBundle\Entity\Situation $situation)
    {
        $this->situation = $situation;

        return $this;
    }

    /**
     * Get situation
     *
     * @return \AppBundle\Entity\Situation
     */
    public function getSituation()
    {
        return $this->situation;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return User
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
     * Set linkedin
     *
     * @param string $linkedin
     *
     * @return User
     */
    public function setLinkedin($linkedin)
    {
        $this->linkedin = $linkedin;

        return $this;
    }

    /**
     * Get linkedin
     *
     * @return string
     */
    public function getLinkedin()
    {
        return $this->linkedin;
    }

    public function setCvFile(File $image = null)
    {
        $this->cvFile = $image;
        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getCvFile()
    {
        return $this->cvFile;
    }

    public function setCvName($image)
    {
        $this->cvName = $image;
    }

    public function getCvName()
    {
        return $this->cvName;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set sector
     *
     * @param \AppBundle\Entity\Sector $sector
     *
     * @return User
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
     * Set country
     *
     * @param \AppBundle\Entity\Country $country
     *
     * @return User
     */
    public function setCountry(\AppBundle\Entity\Country $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return \AppBundle\Entity\Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set school
     *
     * @param string $school
     *
     * @return User
     */
    public function setSchool($school)
    {
        $this->school = $school;

        return $this;
    }

    /**
     * Get school
     *
     * @return string
     */
    public function getSchool()
    {
        return $this->school;
    }

    /**
     * Set studiesLevel
     *
     * @param \AppBundle\Entity\StudiesLevel $studiesLevel
     *
     * @return User
     */
    public function setStudiesLevel(\AppBundle\Entity\StudiesLevel $studiesLevel = null)
    {
        $this->studiesLevel = $studiesLevel;

        return $this;
    }

    /**
     * Get studiesLevel
     *
     * @return \AppBundle\Entity\StudiesLevel
     */
    public function getStudiesLevel()
    {
        return $this->studiesLevel;
    }

    /**
     * Set currentPosition
     *
     * @param string $currentPosition
     *
     * @return User
     */
    public function setCurrentPosition($currentPosition)
    {
        $this->currentPosition = $currentPosition;

        return $this;
    }

    /**
     * Get currentPosition
     *
     * @return string
     */
    public function getCurrentPosition()
    {
        return $this->currentPosition;
    }

    /**
     * Add userLesson
     *
     * @param \AppBundle\Entity\UserLesson $userLesson
     *
     * @return User
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

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
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
     *
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
     * @ORM\PreUpdate
     */
    public function updateDate()
    {
        $this->setUpdatedAt(new \Datetime());
    }

    /**
     * Set askTrainer
     *
     * @param \DateTime $askTrainer
     *
     * @return User
     */
    public function setAskTrainer($askTrainer)
    {
        $this->askTrainer = $askTrainer;

        return $this;
    }

    /**
     * Get askTrainer
     *
     * @return \DateTime
     */
    public function getAskTrainer()
    {
        return $this->askTrainer;
    }

    /**
     * Set answerTrainer
     *
     * @param \DateTime $answerTrainer
     *
     * @return User
     */
    public function setAnswerTrainer($answerTrainer)
    {
        $this->answerTrainer = $answerTrainer;

        return $this;
    }

    /**
     * Get answerTrainer
     *
     * @return \DateTime
     */
    public function getAnswerTrainer()
    {
        return $this->answerTrainer;
    }
}

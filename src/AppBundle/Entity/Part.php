<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Part
 *
 * @ORM\Table(name="part")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PartRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Part
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     * @Assert\NotBlank(message="Please enter a name for the part.")
     * @Assert\Length(
     *      min = 10,
     *      max = 255,
     *      minMessage = "Your part name must be at least {{ limit }} characters long",
     *      maxMessage = "Your part name cannot be longer than {{ limit }} characters"
     * )
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="string", length=1500)
     * @Assert\NotBlank(message="Please enter a content.")
     * @Assert\Length(
     *      min = 20,
     *      max = 1500,
     *      minMessage = "Your content must be at least {{ limit }} characters long",
     *      maxMessage = "Your content cannot be longer than {{ limit }} characters"
     * )
     */
    private $content;

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
     * @return Part
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
     * Set content
     *
     * @param string $content
     *
     * @return Part
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

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Part
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
     * @return Part
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
}

<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * LessonType
 *
 * @ORM\Table(name="lesson_type")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LessonTypeRepository")
 */
class LessonType
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
     * @Assert\NotBlank(message="Please enter a lesson type's name.")
     * @Assert\Length(
     *      min = 10,
     *      max = 255,
     *      minMessage = "Your lesson type's name must be at least {{ limit }} characters long",
     *      maxMessage = "Your lesson type's name cannot be longer than {{ limit }} characters"
     * )
     */
    private $name;


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
     * @return LessonType
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

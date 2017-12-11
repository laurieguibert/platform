<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DurationType
 *
 * @ORM\Table(name="duration_type")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DurationTypeRepository")
 */
class DurationType
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
     * @ORM\Column(name="name", type="string", length=25, unique=true)
     * @Assert\NotBlank(message="Please enter a duration type's name.")
     * @Assert\Length(
     *      min = 2,
     *      max = 25,
     *      minMessage = "Your duration type's name must be at least {{ limit }} characters long",
     *      maxMessage = "Your duration type's name cannot be longer than {{ limit }} characters"
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
     * @return DurationType
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

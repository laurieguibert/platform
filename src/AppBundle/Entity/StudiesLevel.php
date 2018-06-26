<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StudiesLevel
 *
 * @ORM\Table(name="studies_level")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StudiesLevelRepository")
 */
class StudiesLevel
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
     * @ORM\Column(name="level", type="string", length=100, unique=true)
     */
    private $level;

    /**
     * @var string
     *
     * @ORM\Column(name="short_level", type="string", length=10)
     */
    private $shortLevel;


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
     * Set level
     *
     * @param string $level
     *
     * @return StudiesLevel
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return string
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set shortLevel
     *
     * @param string $shortLevel
     *
     * @return StudiesLevel
     */
    public function setShortLevel($shortLevel)
    {
        $this->shortLevel = $shortLevel;

        return $this;
    }

    /**
     * Get shortLevel
     *
     * @return string
     */
    public function getShortLevel()
    {
        return $this->shortLevel;
    }
}

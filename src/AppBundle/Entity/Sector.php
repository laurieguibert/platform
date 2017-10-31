<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Sector
 *
 * @ORM\Table(name="sector")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SectorRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Sector
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
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="Sector",  mappedBy="parent_sector")
     */
    private $child_sector;

    /**
     * @ORM\ManyToMany(targetEntity="Sector", inversedBy="child_sector")
     * @ORM\JoinTable(name="sector_hierarchy",
     *      joinColumns={@ORM\JoinColumn(name="child_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="parent_id", referencedColumnName="id")}
     *      )
     */
    private $parent_sector;

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
        $this->parent_sector = new ArrayCollection();
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
     * @return Sector
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Sector
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
     * @return Sector
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
     * Add childSector
     *
     * @param \AppBundle\Entity\Sector $childSector
     *
     * @return Sector
     */
    public function addChildSector(\AppBundle\Entity\Sector $childSector)
    {
        $this->child_sector[] = $childSector;

        return $this;
    }

    /**
     * Remove childSector
     *
     * @param \AppBundle\Entity\Sector $childSector
     */
    public function removeChildSector(\AppBundle\Entity\Sector $childSector)
    {
        $this->child_sector->removeElement($childSector);
    }

    /**
     * Get childSector
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildSector()
    {
        return $this->child_sector;
    }

    /**
     * Add parentSector
     *
     * @param \AppBundle\Entity\Sector $parentSector
     *
     * @return Sector
     */
    public function addParentSector(\AppBundle\Entity\Sector $parentSector)
    {
        $this->parent_sector[] = $parentSector;

        return $this;
    }

    /**
     * Remove parentSector
     *
     * @param \AppBundle\Entity\Sector $parentSector
     */
    public function removeParentSector(\AppBundle\Entity\Sector $parentSector)
    {
        $this->parent_sector->removeElement($parentSector);
    }

    /**
     * Get parentSector
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParentSector()
    {
        return $this->parent_sector;
    }

    /**
     * @ORM\PreUpdate
     */
    public function updateDate()
    {
        $this->setUpdatedAt(new \Datetime());
    }
}

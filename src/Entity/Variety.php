<?php

/*
 * produced by laurent GIBERT
 *
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Variety.
 *
 * @author Laurent GIBERT
 *
 * @ORM\Table(name="variety")
 * @ORM\Entity
 */
class Variety
{
    /**
     * The identifier of the variety.
     *
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id = null;

    /**
     * The variety name.
     *
     * @var string
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * Plant in the variety.
     *
     * @var Plant[]
     * @ORM\ManyToMany(targetEntity="Plant", mappedBy="varieties")
     **/
    protected $plants;

    /**
     * The category parent.
     *
     * @var Variety
     * @ORM\ManyToOne(targetEntity="Variety")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     **/
    protected $parent;

    public function __construct()
    {
        $this->plants = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Get the id of the variety.
     * Return null if the variety is new and not saved.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the name of the variety.
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get the name of the variety.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Return all plant associated to the variety.
     *
     * @return Plant[]
     */
    public function getPlants()
    {
        return $this->plants;
    }

    /**
     * Set all plants in the variety.
     *
     * @param Plant[] $plants
     */
    public function setPlants($plants)
    {
        $this->plants->clear();
        $this->plants = new ArrayCollection($plants);
    }

    /**
     * Add a plant in the Variety.
     *
     * @param $plant Plant The plant to associate
     */
    public function addPlant($plant)
    {
        if ($this->plants->contains($plant)) {
            return;
        }

        $this->plants->add($plant);
        $plant->addVariety($this);
    }

    /**
     * @param Plant $plant
     */
    public function removePlant($plant)
    {
        if (!$this->plants->contains($plant)) {
            return;
        }

        $this->plants->removeElement($plant);
        $plant->removeVariety($this);
    }
}

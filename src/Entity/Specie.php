<?php

/*
 * produced by laurent GIBERT
 *
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Specie.
 *
 * @author Laurent GIBERT
 *
 * @ORM\Table(name="specie")
 * @ORM\Entity
 */
class Specie
{
    /**
     * The identifier of the specie.
     *
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id = null;

    /**
     * The specie name.
     *
     * @var string
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * Plant in the specie.
     *
     * @var Plant[]
     * @ORM\ManyToMany(targetEntity="Plant", mappedBy="species")
     **/
    protected $plants;



    public function __construct()
    {
        $this->plants = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Get the id of the category.
     * Return null if the category is new and not saved.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the name of the category.
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get the name of the category.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Return all plant associated to the category.
     *
     * @return Plant[]
     */
    public function getPlants()
    {
        return $this->plants;
    }

    /**
     * Set all plants in the specie.
     *
     * @param Plant[] $plants
     */
    public function setPlants($plants)
    {
        $this->plants->clear();
        $this->plants = new ArrayCollection($plants);
    }

    /**
     * Add a plant in the specie.
     *
     * @param $plant Plant The plant to associate
     */
    public function addPlant($plant)
    {
        if ($this->plants->contains($plant)) {
            return;
        }

        $this->plants->add($plant);
        $plant->addSpecie($this);
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
        $plant->removeSpecie($this);
    }
}

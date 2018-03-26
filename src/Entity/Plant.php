<?php
/****
 * Class PLant.
 *
 * @author Laurent GIBERT
 *
 * @ORM\Entity
 * @Vich\Uploadable
*/
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


class Plant
{

 /**
 * The identifier of the plant.
 *
 * @var int
 * @ORM\Column(name="id", type="integer")
 * @ORM\Id
 * @ORM\GeneratedValue(strategy="AUTO")
 */
    private $id = null;

    /**
     * The creation date of the plant.
     *
     * @var \DateTime
     * @ORM\Column(type="datetime", name="created_at")
     */
    private $createdAt = null;

    /**
     * The update date of the plant.
     *
     * @var \DateTime
     * @ORM\Column(type="datetime", name="updated_at")
     */
    private $updatedAt = null;

    /**
     * List of tags associated to the plant.
     *
     * @var string[]
     * @ORM\Column(type="simple_array")
     */
    private $tags = array();

    /**
     * Indicate if the plant is enabled (available in user_store).
     *
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $enabled = false;

    /**
     * It only stores the name of the image associated with the plant.
     *
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $image;

    /**
     * This unmapped property stores the binary contents of the image file
     * associated with the plant.
     *
     * @Vich\UploadableField(mapping="plant_images", fileNameProperty="image")
     *
     * @var File
     */
    private $imageFile;

    /**
     * Features of the plant.
     * Associative array, the key is the name/type of the feature, and the value the data.
     * Example:<pre>array(
     *     'size' => '13cm x 15cm x 6cm',
     *     'bluetooth' => '4.1'
     * )</pre>.
     *
     * @var array
     * @ORM\Column(type="array")
     */
    private $features = array();

    /**
     * The price of the plant.
     *
     * @var float
     * @ORM\Column(type="float")
     */
    private $price = 0.0;

    /**
     * The name of the plant.
     *
     * @var string
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * The description of the plant.
     *
     * @var string
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * List of varieties where the plants is
     * (Owning side).
     *
     * @var Variety[]
     * @ORM\ManyToOne(targetEntity="Variety", inversedBy="plants")
     * @ORM\JoinTable(nullable=true)
     */
    private $varieties;

    /**
     * List of species where the plants is
     * (Owning side).
     *
     * @var Specie[]
     * @ORM\ManyToOne(targetEntity="Specie", inversedBy="plants")
     * @ORM\JoinTable(nullable=true)
     */
    private $species;


    /**
     * @var PurchaseItem[]
     * @ORM\OneToMany(targetEntity="PurchaseItem", mappedBy="plant", cascade={"remove"})
     */
    private $purchasedItems;


    /**
     * @var Seed[]
     * @ORM\OneToMany(targetEntity="Seed", mappedBy="plant", cascade={"remove"})
     */
    private $seeds;
    

    public function __construct()
    {
        $this->varieties = new ArrayCollection();
        $this->species = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * Add a variety in the plant association.
     * (Owning side).
     *
     * @param $variety Variety the category to associate
     */
    public function addVariety($variety)
    {
        if ($this->varieties->contains($variety)) {
            return;
        }

        $this->varieties->add($variety);
        $variety->addPlant($this);
    }

    /**
     * Remove a variety in the plant association.
     * (Owning side).
     *
     * @param $variety Variety the category to associate
     */
    public function removeVariety($variety)
    {
        if (!$this->varieties->contains($variety)) {
            return;
        }

        $this->varieties->removeElement($variety);
        $variety->removePlant($this);
    }
    /**
     * Add a specie in the plant association.
     * (Owning side).
     *
     * @param $specie Specie the category to associate
     */
    public function addSpecie($specie)
    {
        if ($this->species->contains($specie)) {
            return;
        }

        $this->species->add($specie);
        $specie->addPlant($this);
    }

    /**
     * Remove a specie in the plant association.
     * (Owning side).
     *
     * @param $specie Specie the category to associate
     */
    public function removeSpecie($specie)
    {
        if (!$this->species->contains($specie)) {
            return;
        }

        $this->species->removeElement($specie);
        $specie->removePlant($this);
    }

    /**
     * Set the description of the plant.
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * The the full description of the plant.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * Set if the plant
     * is enable.
     *
     * @param bool $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * Is the plant enabled?
     *
     * @return bool
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Alias of getEnabled.
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->getEnabled();
    }

    /**
     * Set the list of features.
     * The parameter is an associative array (key as type, value as data.
     *
     * @param array $features
     */
    public function setFeatures($features)
    {
        $this->features = $features;
    }

    /**
     * Get all plant features.
     *
     * @return array
     */
    public function getFeatures()
    {
        return $this->features;
    }

    /**
     * @param File $image
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;
    }

    /**
     * @return File
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set the plant name.
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Retrieve the name of the plant.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the price.
     *
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * Get the price of the plant.
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set the list of the tags.
     *
     * @param \string[] $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * Get the list of tags associated to the plant.
     *
     * @return \string[]
     */
    public function getTags()
    {
        return $this->tags;
    }



}

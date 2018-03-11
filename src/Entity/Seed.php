<?php
declare(strict_types = 1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM; 

	/** 
 	* @ORM\Entity
 	* @ORM\Table(name="seed")
 	*/

class Seed { 
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO") 
	 */ 

	public $id;

	/** 
	 * @ORM\Column(type="string", length=100)
	 */

	public $name; 

    /**
     * @ORM\Column(type="decimal", scale=2)
     */
    public $price;

    /**
     * @ORM\Column(type="date")
     */
    public $startSowingDate;

    /**
     * @ORM\Column(type="date")
     */
    public $endSowingDate;

}

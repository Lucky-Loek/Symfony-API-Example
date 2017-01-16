<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use Webmozart\Assert\Assert;

/**
 * Car.
 *
 * @ORM\Table(name="car")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CarRepository")
 */
class Car
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
     * @ORM\Column(name="brand", type="string", length=255)
     */
    private $brand;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="year", type="integer", nullable=true)
     */
    private $year;

    public function __construct($brand, $name, $year)
    {
        Assert::allNotNull([$brand, $name, $year], 'Brand, Name and Year should all be filled');
        Assert::allString([$brand, $name], 'Brand and Name should be a string');
        Assert::integerish($year, 'Year should be an integer');

        $this->brand = $brand;
        $this->name = $name;
        $this->year = (int) $year;
    }

    /**
     * Returns the properties of this object.
     *
     * @return array
     */
    public function getProperties()
    {
        return get_object_vars($this);
    }

    /**
     * Set the properties of this object.
     *
     * @param array $properties
     *
     * @return Car $this
     */
    public function setProperties(array $properties)
    {
        foreach ($properties as $key => $property) {
            if (is_null($property)) {
                throw new InvalidArgumentException('Property '.$key.' can not be null');
            }
        }

        $this->id = $properties['id'];
        $this->brand = $properties['brand'];
        $this->name = $properties['name'];
        $this->year = $properties['year'];

        return $this;
    }
}

<?php

namespace AppBundle\Dto;

use AppBundle\Entity\Car;

class CarDtoAssembler
{
    /**
     * @return CarDto
     */
    public static function createFromEntity(Car $car)
    {
        $dto = new CarDto();
        $properties = $car->getProperties();

        $dto->id = $properties['id'];
        $dto->brand = $properties['brand'];
        $dto->name = $properties['name'];
        $dto->year = $properties['year'];

        return $dto;
    }
}

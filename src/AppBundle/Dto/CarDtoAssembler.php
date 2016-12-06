<?php

namespace AppBundle\Dto;

use AppBundle\Entity\Car;

class CarDtoAssembler
{
    public static function createFromEntity(Car $car)
    {
        $dto = new CarDto();
        $dto->id = $car->getId();
        $dto->brand = $car->getBrand();
        $dto->name = $car->getName();
        $dto->year = $car->getYear();

        return $dto;
    }
}

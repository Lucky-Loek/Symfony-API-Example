<?php

namespace tests\AppBundle\Dto;

use AppBundle\Dto\CarDtoAssembler;
use AppBundle\Entity\Car;

class CarDtoAssemblerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldMakeDto()
    {
        $car = new Car('Ford', 'Mustang', 1972);

        $carDto = CarDtoAssembler::createFromEntity($car);

        $this->assertEquals('Ford', $carDto->brand);
    }
}

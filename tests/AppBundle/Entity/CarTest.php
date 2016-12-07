<?php

namespace tests\AppBundle\Entity;

use AppBundle\Entity\Car;

class CarTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldCreateCarOnValidInput()
    {
        new Car('Testbrand', 'Testname', 2016);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function shouldThrowExceptionOnInvalidInput()
    {
        new Car(1234, 'Testname', 2016);
    }

    /**
     * @test
     */
    public function shouldUpdateWithValidData()
    {
        $car = new Car('Testbrand', 'Testname', 2016);
        $car->setName('Bettertestname');
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function shouldThrowExceptionOnUpdateWithInvalidData()
    {
        $car = new Car('Testbrand', 'Testname', 2016);
        $car->setName(null);
    }
}

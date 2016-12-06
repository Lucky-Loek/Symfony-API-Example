<?php

namespace AppBundle\Features\Context;

use AppBundle\Entity\Car;
use Behat\Behat\Context\Context;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

class FeatureContext implements Context
{
    // Boots App and kernel, gives access to container
    use KernelDictionary;

    /**
     * @BeforeScenario
     */
    public function clearDatabase()
    {
        $manager = $this->getContainer()->get('doctrine')->getManager();
        $purger = new ORMPurger($manager);
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_TRUNCATE);
        $purger->purge();
    }

    /**
     * @Given I have a car with brand :arg1 and name :arg2 and year :arg3
     */
    public function iHaveACarWithBrandAndNameAndYear($arg1, $arg2, $arg3)
    {
        $car = new Car($arg1, $arg2, $arg3);

        $em = $this->getContainer()->get('doctrine')->getManager();
        $em->persist($car);
        $em->flush();
    }
}

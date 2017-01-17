<?php

namespace AppBundle\Features\Context;

use AppBundle\Entity\Car;
use AppBundle\Entity\User;
use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Sanpi\Behatch\Context\RestContext;

class FeatureContext implements Context
{
    // Boots App and kernel, gives access to container
    use KernelDictionary;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var array
     */
    protected $user;

    /**
     * @var RestContext
     */
    protected $restContext;

    /**
     * @param \Behat\Behat\Hook\Scope\BeforeScenarioScope $scope
     *
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $environment = $scope->getEnvironment();
        $this->restContext = $environment->getContext('Sanpi\Behatch\Context\RestContext');
    }

    /**
     * @BeforeScenario
     */
    public function clearDatabaseAndTokenAndUser()
    {
        $manager = $this->getContainer()->get('doctrine')->getManager();
        $purger = new ORMPurger($manager);
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_TRUNCATE);
        $purger->purge();

        $this->token = '';
        $this->user = null;
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

    /**
     * @Given /^I am a user with username "([^"]*)" and password "([^"]*)"$/
     */
    public function iAmAUserWithUsernameAndPassword($username, $plainPassword)
    {
        $user = new User();

        $encoder = $this->getContainer()->get('security.password_encoder');
        $encodedPassword = $encoder->encodePassword($user, $plainPassword);

        $user->setUsername($username);
        $user->setPassword($encodedPassword);

        $em = $this->getContainer()->get('doctrine')->getManager();
        $em->persist($user);
        $em->flush();

        $this->user = [
            'username' => $username,
            'password' => $plainPassword,
        ];
    }

    /**
     * @Given I have a valid token
     */
    public function iHaveAValidToken()
    {
        $this->restContext->iAddHeaderEqualTo('username', $this->user['username']);
        $this->restContext->iAddHeaderEqualTo('password', $this->user['password']);

        $response = $this->restContext->iSendARequestTo('POST', $this->getContainer()->getParameter('app_url') . '/api/token');

        $body = $response->getContent();

        $body = json_decode($body);

        $this->token = $body->token;
    }

    /**
     * @Given /^I add that token to my request as Authorization header$/
     */
    public function iAddThatTokenToMyRequestAsAuthorizationHeader()
    {
        $this->restContext->iAddHeaderEqualTo('Authorization', 'Bearer '. $this->token);
    }
}

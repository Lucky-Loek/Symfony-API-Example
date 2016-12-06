<?php

namespace tests\AppBundle\EventDispatcher;

use AppBundle\EventDispatcher\Listener\KernelRequestListener;
use Mockery;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Kernel;

class KernelRequestListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldLogWhenRequestIsReceived()
    {
        $logger = Mockery::mock(LoggerInterface::class);
        $listener = new KernelRequestListener($logger);

        $request = new Request();

        $event = new GetResponseEvent(Mockery::mock(Kernel::class), $request, HttpKernelInterface::MASTER_REQUEST);

        $logger->shouldReceive('info')->once();

        $listener->onKernelRequest($event);
    }
}

<?php

namespace App\Tests\EventListener;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\MethodProphecy;
use Prophecy\Prophecy\ObjectProphecy;

use App\EventListener\CorsListener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class CorsEventListenerTest extends TestCase
{
    /**
     * Test if every Response headers contain:
     * Access-Control-Allow-Headers
     * Access-Control-Allow-Origin
     * Access-Control-Allow-Credentials
     */
    public function testOnKernelResponse()
    {
        /** @var FilterResponseEvent|ObjectProphecy $event */
        $event = $this->prophesize(FilterResponseEvent::class);

        /** @var ResponseHeaderBag|MethodProphecy $responseHeaderBag */
        $responseHeaderBag = $this->prophesize(ResponseHeaderBag::class);

        /** @var Response|ObjectProphecy $response */
        $response = $this->prophesize(Response::class);

        $response->headers = $responseHeaderBag;
        $event->getResponse()->willReturn($response->reveal());

        $responseHeaderBag->set('Access-Control-Allow-Headers', 'content-type, accept')->shouldBeCalled();
        $responseHeaderBag->set('Access-Control-Allow-Origin', 'http://sf.local')->shouldBeCalled();
        $responseHeaderBag->set('Access-Control-Allow-Credentials', 'true')->shouldBeCalled();

        /** @var ContainerInterface|ObjectProphecy $container */
        $container = $this->prophesize(ContainerInterface::class);

        $container->getParameter('cors_accept')->willReturn('http://sf.local');

        $cors = new CorsListener($container->reveal());
        $cors->onKernelResponse($event->reveal());
    }
}

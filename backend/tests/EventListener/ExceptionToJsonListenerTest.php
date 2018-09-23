<?php

namespace App\Tests\EventListener;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\MethodProphecy;
use Prophecy\Prophecy\ObjectProphecy;

use App\EventListener\ExceptionToJsonListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class ExceptionToJsonListenerTest extends TestCase
{
    /**
     * Test every no-code exception be with 500 code.
     * Every 500 Exception with 'Service Error' message.
     */
    public function testOnKernelException500()
    {
        /** @var GetResponseForExceptionEvent|ObjectProphecy|MethodProphecy $event */
        $event = $this->prophesize(GetResponseForExceptionEvent::class);

        $exception = new \Exception('Message');

        $event->getException()->willReturn($exception);

        $exListener = new ExceptionToJsonListener();

        $responseData = [
            'errors' => ['Service Error'],
        ];

        $event->setResponse(new JsonResponse($responseData, 500))->shouldBeCalled();

        $exListener->onKernelException($event->reveal());
    }

    /**
     * Test every Exception with code produce same status.
     * Test response has same error message with Exception
     */
    public function testOnKernelException()
    {
        /** @var GetResponseForExceptionEvent|ObjectProphecy|MethodProphecy $event */
        $event = $this->prophesize(GetResponseForExceptionEvent::class);

        $exception = new \Exception('404 Exception', 404);


        $event->getException()->willReturn($exception);

        $exListener = new ExceptionToJsonListener();

        $responseData = [
            'errors' => ['404 Exception'],
        ];

        $event->setResponse(new JsonResponse($responseData, 404))->shouldBeCalled();

        $exListener->onKernelException($event->reveal());
    }
}

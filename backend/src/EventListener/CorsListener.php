<?php

declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CorsListener
{
    protected $corsHosts;

    /**
     * CorsListener constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->corsHosts = $container->getParameter('cors_accept') ?? '*';
    }

    public function onKernelResponse(FilterResponseEvent $event): void
    {
        $responseHeaders = $event->getResponse()->headers;

        $responseHeaders->set('Access-Control-Allow-Headers', 'content-type, accept');
        $responseHeaders->set('Access-Control-Allow-Origin', $this->corsHosts);
        $responseHeaders->set('Access-Control-Allow-Credentials', 'true');
    }
}

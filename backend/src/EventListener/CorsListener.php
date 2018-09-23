<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class CorsListener
{
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $responseHeaders = $event->getResponse()->headers;

        $responseHeaders->set('Access-Control-Allow-Headers', 'content-type, accept');
        $responseHeaders->set('Access-Control-Allow-Origin', 'http://sf.local');
        $responseHeaders->set('Access-Control-Allow-Credentials', 'true');
    }
}

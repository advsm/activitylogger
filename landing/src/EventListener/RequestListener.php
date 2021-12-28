<?php

namespace App\EventListener;

use App\Component\JsonRpcClient;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpClient\HttpClient;

class RequestListener
{
    private JsonRpcClient $jsonRpcClient;

    public function __construct(JsonRpcClient $jsonRpcClient)
    {
        $this->jsonRpcClient = $jsonRpcClient;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        if (!$event->isMainRequest()) {
            // don't do anything if it's not the main request
            return;
        }

        $request = $event->getRequest();
        $result = $this->jsonRpcClient->request('log', [
            'url' => $request->getUri(),
            'domain' => $request->getHost(),
            'ip' => $request->getClientIp(),
            'user_agent' => $request->headers->get('User-Agent'),
        ]);

        return;
    }
}

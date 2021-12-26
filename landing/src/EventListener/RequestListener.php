<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpClient\HttpClient;

class RequestListener
{
    public function onKernelRequest(RequestEvent $event)
    {
        if (!$event->isMainRequest()) {
            // don't do anything if it's not the main request
            return;
        }

        $request = $event->getRequest();
        $client = HttpClient::create();

        $body = [
            "jsonrpc" => "2.0",
            "method" => "log",
            "params" => [
                'url' => $request->getUri(),
                'domain' => $request->getHost(),
                'ip' => $request->getClientIp(),
                'user_agent' => $request->headers->get('User-Agent'),
            ],
            "id" => time(),
        ];

        $response = $client->request('POST', 'http://nginx:8080/json-rpc', [
            'body' => json_encode($body),
        ]);

        $response->getContent();
        return;
    }
}

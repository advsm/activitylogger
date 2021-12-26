<?php

namespace App\Component;

use Symfony\Component\HttpClient\HttpClient;

class JsonRpcClient
{
    /**
     * @var HttpClient
     */
    protected $client;

    public function __construct($client = null)
    {
        $client = HttpClient::create();
        $response = $client->request('POST', 'http://nginx:8080/json-rpc', [
            'body' => '[{ "jsonrpc":"2.0","method":"ping","params":[],"id" : 1 }]',
        ]);
    }
}

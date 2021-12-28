<?php

namespace App\Component;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class JsonRpcClient
{
    /**
     * @var HttpClientInterface
     */
    protected $client;

    /**
     * JSON RPC API Server url, usually stored in .env
     *
     * @var string
     */
    private string $jsonRpcServerUrl;

    public function __construct(array $arguments = [])
    {
        $url = $arguments['url'] ?? null;
        $this->setServer($url);
    }

    public function setServer(string $url) : self
    {
        $this->jsonRpcServerUrl = $url;
        return $this;
    }

    public function request(string $method, array $params = []) : array
    {
        $body = [
            "jsonrpc" => "2.0",
            "method" => $method,
            "params" => $params,
            "id" => time(),
        ];

        $response = $this->getHttpClient()->request('POST', $this->jsonRpcServerUrl, [
            'body' => json_encode($body),
        ]);

        $json = json_decode($response->getContent(), true);
        return $json['result'];
    }

    public function getHttpClient() : HttpClientInterface
    {
        if (!$this->client instanceof HttpClientInterface) {
            $this->client = HttpClient::create();
        }

        return $this->client;
    }

    public function setHttpClient(HttpClientInterface $client) : self
    {
        $this->client = $client;
        return $this;
    }

}

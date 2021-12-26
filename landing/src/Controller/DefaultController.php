<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/default', name: 'default')]
    public function index(): Response
    {
        $client = HttpClient::create();
        $response = $client->request('POST', 'http://nginx:8080/json-rpc', [
            'body' => '[{ "jsonrpc":"2.0","method":"log","params":[],"id":1 }]',
        ]);

        $content = $response->getContent();
        return new Response($content);
    }

}

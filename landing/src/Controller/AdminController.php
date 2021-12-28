<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin/activity', name: 'activity')]
    public function activity(Request $request): Response
    {
        $limit = 4;
        $page = $request->get('page') ?? 1;
        $offset = $page * $limit - $limit;

        $client = HttpClient::create();
        $body = [
            "jsonrpc" => "2.0",
            "method" => "history",
            "params" => [
                "offset" => $offset,
                "limit" => $limit,
            ],
            "id" => time(),
        ];

        $response = $client->request('POST', 'http://nginx:8080/json-rpc', [
            'body' => json_encode($body),
        ]);

        $json = json_decode($response->getContent(), true);

        $total = $json['result']['total'];
        $pagesCount = ceil($total / $limit);

        $urls = $json['result']['data'];

        return $this->render('admin/activity.html.twig', [
            'page' => $page,
            'pagesCount' => $pagesCount,
            'urls' => $urls,
        ]);
    }
}

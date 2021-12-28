<?php

namespace App\Controller;

use App\Component\JsonRpcClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin/activity', name: 'activity')]
    public function activity(Request $request, JsonRpcClient $jsonRpcClient): Response
    {
        $limit = 4;
        $page = $request->get('page') ?? 1;
        $offset = $page * $limit - $limit;

        $result = $jsonRpcClient->request("history", [
            "offset" => $offset,
            "limit" => $limit,
        ]);

        $total = $result['total'];
        $pagesCount = ceil($total / $limit);

        $urls = $result['data'];

        return $this->render('admin/activity.html.twig', [
            'page' => $page,
            'pagesCount' => $pagesCount,
            'urls' => $urls,
        ]);
    }
}

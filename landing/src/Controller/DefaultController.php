<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;

class DefaultController extends AbstractController
{
    #[Route('/default', name: 'default')]
    public function index(Request $request): Response
    {
        return new Response('DefaultController::index');
    }

}

<?php

namespace App\Controller;

use App\Entity\Visit;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;

class DefaultController extends AbstractController
{
    #[Route('/default', name: 'default')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Visit::class);

        /** @var QueryBuilder $builder */
        $builder = $repository->createQueryBuilder('v')
            ->select('v.url', 'COUNT(v.url) as cnt', 'MAX(v.createdAt) as last_visit')
            ->groupBy('v.url')
            ->orderBy('last_visit', 'desc')
            ->setFirstResult(100)
            ->setMaxResults(2);


        $query = $builder->getQuery();
        $result = $query->getResult();
        foreach ($result as $row) {
            var_dump($row);
        }
        die();
        // SHOW SQL:
        var_dump($query->getSQL());
        var_dump($query->getParameters());
        die();

        return new Response('DefaultController::index');
    }

}

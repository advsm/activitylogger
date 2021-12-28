<?php

namespace App\Method;

use Yoanm\JsonRpcServer\Domain\JsonRpcMethodInterface;
use Yoanm\JsonRpcParamsSymfonyValidator\Domain\MethodWithValidatedParamsInterface;

use Doctrine\ORM\EntityManager;

use App\Entity\Visit;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Constraints\Type;

class VisitHistoryMethod implements JsonRpcMethodInterface, MethodWithValidatedParamsInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function apply(array $paramList = null) : array
    {
        $offset = $paramList['offset'] ?? 0;
        $limit = $paramList['limit'] ?? 100;

        $repository = $this->entityManager->getRepository(Visit::class);

        $builder = $repository->createQueryBuilder('v')
            ->select('v.url', 'COUNT(v.url) as cnt', 'MAX(v.createdAt) as last_visit')
            ->groupBy('v.url')
            ->orderBy('last_visit', 'desc')
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        $urls = $builder->getQuery()->getResult();

        $builder = $repository->createQueryBuilder('v')
            ->select('COUNT(DISTINCT(v.url))');
        $total = $builder->getQuery()->getSingleColumnResult();

        return ['total' => current($total), 'data' => $urls];
    }

    public function getParamsConstraint() : Constraint
    {
        return new Collection(['fields' => [
            'limit' => new Optional([
                new Type('int'),
                new Length(['min' => 1, 'max' => 100])
            ]),
            'offset' => new Optional([
                new Type('int'),
                new Length(['min' => 1, 'max' => 100])
            ]),
        ]]);
    }
}
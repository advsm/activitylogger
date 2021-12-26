<?php

namespace App\Method;

use Yoanm\JsonRpcServer\Domain\JsonRpcMethodInterface;
use Doctrine\ORM\EntityManager;
use App\Entity\Visit;

class LogVisitMethod implements JsonRpcMethodInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function apply(array $paramList = null) : string
    {

        $visit = new Visit();

        $visit->setUrl($paramList['url']);
        $visit->setDomain($paramList['domain']);
        $visit->setIp($paramList['ip']);
        $visit->setUserAgent($paramList['user_agent']);

        $this->entityManager->persist($visit);
        $this->entityManager->flush();

        return $visit->getId();
    }
}
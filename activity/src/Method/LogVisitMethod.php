<?php

namespace App\Method;

use Yoanm\JsonRpcServer\Domain\JsonRpcMethodInterface;
use Yoanm\JsonRpcParamsSymfonyValidator\Domain\MethodWithValidatedParamsInterface;

use Doctrine\ORM\EntityManager;

use App\Entity\Visit;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Constraints\Ip;
use Symfony\Component\Validator\Constraints\Url;

class LogVisitMethod implements JsonRpcMethodInterface, MethodWithValidatedParamsInterface
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

    public function getParamsConstraint() : Constraint
    {
        return new Collection(['fields' => [
            'url' => new Required([
                new NotBlank(),
                new Length(['max' => 512]),
                new Url(),
            ]),
            'domain' => new Required([
                new NotBlank(),
                new Length(['max' => 64]),
            ]),
            'ip' => new Required([
                new NotBlank(),
                new Length(['max' => 16]),
                new Ip(),
            ]),
            'user_agent' => new Required([
                new NotBlank(),
                new Length(['max' => 512]),
            ])
        ]]);
    }
}
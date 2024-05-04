<?php

namespace App\Repository;

use App\Entity\AccountApplication;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AccountApplication>
 */
class AccountApplicationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccountApplication::class);
    }

    public function getByIdentification(string $appId, string $accountId)
    {
        return $this->findOneBy([
            'appId' => $appId,
            'accountId' => $accountId
        ]);
    }
}

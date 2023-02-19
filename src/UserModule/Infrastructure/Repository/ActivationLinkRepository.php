<?php

namespace Netborg\Fediverse\Api\UserModule\Infrastructure\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Netborg\Fediverse\Api\UserModule\Infrastructure\Entity\ActivationLink;

/**
 * @extends ServiceEntityRepository<ActivationLink>
 *
 * @method ActivationLink|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActivationLink|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActivationLink[]    findAll()
 * @method ActivationLink[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivationLinkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActivationLink::class);
    }

    public function save(ActivationLink $activationLink, bool $flush = false): void
    {
        $this->getEntityManager()->persist($activationLink);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ActivationLink $activationLink, bool $flush = false): void
    {
        $this->getEntityManager()->remove($activationLink);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneByUuid(string $uuid): ?ActivationLink
    {
        return $this->createQueryBuilder('al')
            ->andWhere('al.uuid = :uuid')
            ->setParameter('uuid', $uuid)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findOneById(int $id): ?ActivationLink
    {
        return $this->find($id);
    }
}

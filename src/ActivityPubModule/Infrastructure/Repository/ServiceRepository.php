<?php

namespace Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Entity\Service;

/**
 * @extends ServiceEntityRepository<Service>
 *
 * @method Service|null find($id, $lockMode = null, $lockVersion = null)
 * @method Service|null findOneBy(array $criteria, array $orderBy = null)
 * @method Service[]    findAll()
 * @method Service[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceRepository extends ServiceEntityRepository implements ServiceRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Service::class);
    }

    public function save(Service $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Service $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findOneByPreferredUsername(string $username): ?Service
    {
        return $this->createQueryBuilder('a')
            ->where('a.preferredUsername = :username')->setParameter('username', $username)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}

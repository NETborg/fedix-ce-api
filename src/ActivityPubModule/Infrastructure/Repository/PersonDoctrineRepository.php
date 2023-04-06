<?php

namespace Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Entity\Person;

/**
 * @extends ServiceEntityRepository<Person>
 *
 * @method Person|null find($id, $lockMode = null, $lockVersion = null)
 * @method Person|null findOneBy(array $criteria, array $orderBy = null)
 * @method Person[]    findAll()
 * @method Person[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonDoctrineRepository extends ServiceEntityRepository implements PersonDoctrineRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }

    public function save(Person $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Person $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findOneByPreferredUsername(string $username): ?Person
    {
        return $this->createQueryBuilder('p')
            ->where('p.preferredUsername = :username')->setParameter('username', $username)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findOneByUuid(string $identifier): ?Person
    {
        return $this->createQueryBuilder('p')
            ->where('p.uuid = :identifier')->setParameter('identifier', $identifier)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findForUser(string $userIdentifier): iterable
    {
        yield $this->createQueryBuilder('p')
            ->where('JSONB_EXISTS(p.users, :identifier) = true')->setParameter('identifier', $userIdentifier)
            ->getQuery()
            ->execute()
        ;
    }

    public function hasForUser(string $userIdentifier): bool
    {
        $persons = iterator_to_array($this->findForUser($userIdentifier));

        return !empty(array_shift($persons));
    }
}

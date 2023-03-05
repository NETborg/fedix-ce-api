<?php

namespace Netborg\Fediverse\Api\UserModule\Infrastructure\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Netborg\Fediverse\Api\UserModule\Infrastructure\Entity\User;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $user, bool $flush = false): void
    {
        $this->getEntityManager()->remove($user);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneByUsername(string $username): ?User
    {
        return $this->createQueryBuilder('u')
            ->where('u.username = :username')->setParameter('username', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findOneByEmail(string $email): ?User
    {
        return $this->createQueryBuilder('u')
            ->where('u.email = :email')->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findOneByUsernameOrEmail(string $phrase): ?User
    {
        return $this->createQueryBuilder('u')
            ->where('u.username = :username')->setParameter('username', $phrase)
            ->orWhere('u.email = :email')->setParameter('email', $phrase)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findOneByUuid(string $uuid): ?User
    {
        return $this->createQueryBuilder('u')
            ->where('u.uuid = :uuid')->setParameter('uuid', $uuid)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findOneByAnyIdentifier(string $identifier): ?User
    {
        if (Uuid::isValid($identifier)) {
            return $this->findOneByUuid($identifier);
        }

        return $this->createQueryBuilder('u')
            ->where('u.username = :phrase')
            ->orWhere('u.email = :phrase')
            ->setParameter('phrase', $identifier)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findOneById(int $id): ?User
    {
        return $this->find($id);
    }
}

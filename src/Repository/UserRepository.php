<?php

namespace Netborg\Fediverse\Api\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Netborg\Fediverse\Api\Entity\User;
use Netborg\Fediverse\Api\Interfaces\Repository\UserRepositoryInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
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

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByUsername(string $username): ?User
    {
        return $this->createQueryBuilder('u')
            ->where('u.username = :username')->setParameter('username', $username)
            ->getQuery()
            ->getSingleResult();
    }

    public function findByEmail(string $email): ?User
    {
        return $this->createQueryBuilder('u')
            ->where('u.email = :email')->setParameter('email', $email)
            ->getQuery()
            ->getSingleResult();
    }

    public function findByUsernameOrEmail(string $phrase): ?User
    {
        return $this->createQueryBuilder('u')
            ->where('u.username = :username')->setParameter('username', $phrase)
            ->orWhere('u.email = :email') ->setParameter('email', $phrase)
            ->getQuery()
            ->getSingleResult();
    }
}

<?php

namespace Netborg\Fediverse\Api\AuthModule\Infrastructure\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use League\Bundle\OAuth2ServerBundle\Model\Client;
use Netborg\Fediverse\Api\AuthModule\Infrastructure\Entity\Oauth2UserConsent;
use Netborg\Fediverse\Api\UserModule\Infrastructure\Entity\User;

/**
 * @extends ServiceEntityRepository<Oauth2UserConsent>
 *
 * @method Oauth2UserConsent|null find($id, $lockMode = null, $lockVersion = null)
 * @method Oauth2UserConsent|null findOneBy(array $criteria, array $orderBy = null)
 * @method Oauth2UserConsent[]    findAll()
 * @method Oauth2UserConsent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class Oauth2UserConsentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Oauth2UserConsent::class);
    }

    public function save(Oauth2UserConsent $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Oauth2UserConsent $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneByUserAndClient(User|null $user, Client|null $client): ?Oauth2UserConsent
    {
        if (!$user || $client) {
            return null;
        }

        return $this->createQueryBuilder('o')
            ->where('o.user = :user')->setParameter('user', $user)
            ->andWhere('o.client = :client')->setParameter('client', $client)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}

<?php

namespace App\Repository;

use App\Entity\PetLike;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method PetLike|null find($id, $lockMode = null, $lockVersion = null)
 * @method PetLike|null findOneBy(array $criteria, array $orderBy = null)
 * @method PetLike[]    findAll()
 * @method PetLike[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PetLikeRepository extends ServiceEntityRepository
{
    public $security;

    public function __construct(ManagerRegistry $registry, Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, PetLike::class);

    }

    // /**
    //  * @return PetLike[] Returns an array of PetLike objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PetLike
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function hasUserLiked(string $target, string $user_id = null): ?PetLike{
        
        $user_id = $user_id ?? ($this->security->getUser() ? $this->security->getUser()->getId() : null);
        
        return $this->findOneBy(['target' => $target, 'author' => $user_id]);
    }
}

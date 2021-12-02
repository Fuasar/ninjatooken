<?php
namespace App\Repository;

use App\Entity\User\Friend;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use App\Entity\User\User;
use Doctrine\Persistence\ManagerRegistry;

class FriendRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Friend::class);
    }

    public function getFriends(User $user, $nombreParPage=5, $page=1)
    {
        $page = max(1, $page);

        $query = $this->createQueryBuilder('f')
            ->where('f.user = :user')
            ->andWhere('f.isConfirmed = true')
            ->andWhere('f.isBlocked = false')
            ->setParameter('user', $user)
            ->addOrderBy('f.dateAjout', 'DESC')
            ->setFirstResult(($page-1) * $nombreParPage)
            ->setMaxResults($nombreParPage)
            ->distinct(true);

        return $query->getQuery()->getResult();
    }

    public function getDemandes(User $user, $nombreParPage=5, $page=1): Paginator
    {
        $page = max(1, $page);

        $query = $this->createQueryBuilder('f')
            ->where('f.user = :user')
            ->andWhere('f.isConfirmed = false')
            ->andWhere('f.isBlocked = false')
            ->setParameter('user', $user)
            ->addOrderBy('f.dateAjout', 'DESC')
            ->setFirstResult(($page-1) * $nombreParPage)
            ->setMaxResults($nombreParPage);

        return new Paginator($query);
    }

    public function getNumFriends(User $user)
    {
        $query = $this->createQueryBuilder('f')
            ->select('COUNT(f)')
            ->where('f.user = :user')
            ->andWhere('f.isConfirmed = true')
            ->andWhere('f.isBlocked = false')
            ->setParameter('user', $user)
            ->getQuery();

        return $query->getSingleScalarResult();
    }

    public function getNumDemandes(User $user)
    {

        $query = $this->createQueryBuilder('f')
            ->select('COUNT(f)')
            ->where('f.user = :user')
            ->andWhere('f.isConfirmed = false')
            ->andWhere('f.isBlocked = false')
            ->setParameter('user', $user)
            ->getQuery();

        return $query->getSingleScalarResult();
    }

    public function getNumBlocked(User $user)
    {

        $query = $this->createQueryBuilder('f')
            ->select('COUNT(f)')
            ->where('f.user = :user')
            ->andWhere('f.isBlocked = true')
            ->setParameter('user', $user)
            ->getQuery();

        return $query->getSingleScalarResult();
    }

    public function getBlocked(User $user, $nombreParPage=5, $page=1): Paginator
    {
        $page = max(1, $page);

        $query = $this->createQueryBuilder('f')
            ->where('f.user = :user')
            ->andWhere('f.isBlocked = true')
            ->setParameter('user', $user)
            ->addOrderBy('f.dateAjout', 'DESC')
            ->setFirstResult(($page-1) * $nombreParPage)
            ->setMaxResults($nombreParPage);

        return new Paginator($query);
    }

    public function deleteAllBlocked(User $user = null): bool
    {
        if($user){
            $query = $this->createQueryBuilder('f')
                ->delete('App\Entity\User\Friend', 'f')
                ->where('f.user = :user')
                ->andWhere('f.isBlocked = true')
                ->setParameter('user', $user)
                ->getQuery();
     
            return 1 === $query->getScalarResult();
        }
        return false;
    }

    public function deleteAllDemandes(User $user = null): bool
    {
        if($user){
            $query = $this->createQueryBuilder('f')
                ->delete('App\Entity\User\Friend', 'f')
                ->where('f.user = :user')
                ->andWhere('f.isConfirmed = false')
                ->setParameter('user', $user)
                ->getQuery();
     
            return 1 === $query->getScalarResult();
        }
        return false;
    }
}
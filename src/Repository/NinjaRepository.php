<?php
namespace App\Repository;

use App\Entity\Game\Ninja;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class NinjaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ninja::class);
    }

    public function getNinjas($order="experience", $filter="", $nombreParPage=5, $page=1)
    {
        $page = max(1, $page);

        $query = $this->createQueryBuilder('n');

        if($order=="experience")
            $query->orderBy('n.experience', 'DESC');
        elseif($order=="assassinnat")
            $query->orderBy('n.missionAssassinnat', 'DESC');
        elseif($order=="course")
            $query->orderBy('n.missionCourse', 'DESC');

        if(!empty($filter)){
            $query->where('n.classe = :classe')
                ->setParameter('classe', $filter);
        }

        $query->setFirstResult(($page-1) * $nombreParPage)
            ->setMaxResults($nombreParPage);

        return $query->getQuery()->getResult();
    }

    public function getNumNinjas($classe="")
    {
        $query = $this->createQueryBuilder('n')
            ->select('COUNT(n)')
            ->leftJoin('App\Entity\User\User', 'u', 'WITH', 'n.user = u.id')
            ->where('u.locked = 0');

        if(!empty($classe)){
            $query->andWhere('n.classe = :classe')
                ->setParameter('classe', $classe);
        }

        $query = $query->getQuery();

        $query->enableResultCache(true, 1800);
        $query->useQueryCache(true);

        return $query->getSingleScalarResult();
    }

    public function getSumExperience()
    {
        $query = $this->createQueryBuilder('n')
            ->select('SUM(n.experience)');

        $query = $query->getQuery();

        $query->enableResultCache(true, 1800);
        $query->useQueryCache(true);

        return $query->getSingleScalarResult();
    }

    public function getClassement(int $experience = 0): string
    {
        return "-";
        // deactivate / time consumer
        $query = $this->createQueryBuilder('a')
            ->select('COUNT(a)')
            ->leftJoin('App\Entity\User\User', 'u', 'WITH', 'a.user = u.id')
            ->where('u.locked = 0')
            ->andWhere('a.experience > :experience')
            ->setParameter('experience', $experience);

        return $query->getQuery()->getSingleScalarResult() + 1;
    }
}
<?php

namespace App\Repository;

use App\Entity\Clan\Clan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * ClanRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ClanRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Clan::class);
    }

    public function getClans($order="date", $nombreParPage=5, $page=1)
    {
        $page = max(1, $page);

        $query = $this->createQueryBuilder('c')
            ->addSelect('c')
            ->leftJoin('App\Entity\Clan\ClanUtilisateur', 'cu', 'WITH', 'c.id = cu.clan')
            ->addSelect('COUNT(cu) as num')
            ->where('c.online = :online')
            ->setParameter('online', true)
            ->groupBy('c.id')
            ->distinct(true);

        // par date d'ajout
        if ($order == "date") {
           $query->addOrderBy('c.dateAjout', 'DESC');
        // par nombre de ninja
        } else if ($order == "ninja") {
            $query->addOrderBy('num', 'DESC');
        // par composition
        } else if ($order == "composition") {
            $query->leftJoin('App\Entity\Game\Ninja', 'n', 'WITH', 'n.user = cu.membre')
                ->addSelect('AVG(n.experience)*COUNT(cu) as avgxp')
                ->addOrderBy('avgxp', 'DESC');
        // par moyenne d'expérience
        } else if ($order == "experience") {
            $query->leftJoin('App\Entity\Game\Ninja', 'n', 'WITH', 'n.user = cu.membre')
                ->addSelect('AVG(n.experience) as avgxp')
                ->addOrderBy('avgxp', 'DESC');
        }

        $query->setFirstResult(($page-1) * $nombreParPage)
            ->setMaxResults($nombreParPage);

        return $query->getQuery()->getResult();
    }

    public function getNumClans()
    {
        $query = $this->createQueryBuilder('c')
            ->where('c.online = :online')
            ->setParameter('online', true)
            ->select('COUNT(c)');

        return $query->getQuery()->getSingleScalarResult();
    }

    public function searchClans($q = "", $nombreParPage=5, $page=1)
    {
        $query = $this->createQueryBuilder('c')
            ->where('c.online = :online')
            ->setParameter('online', true)
            ->addOrderBy('c.dateAjout', 'DESC');

        if(!empty($q)){
            $query->andWhere('c.nom LIKE :q')
                ->andWhere('c.description LIKE :q')
                ->setParameter('q', '%'.$q.'%');
        }

        $query->setFirstResult(($page-1) * $nombreParPage)
            ->setMaxResults($nombreParPage);

        return $query->getQuery()->getResult();
    }
}

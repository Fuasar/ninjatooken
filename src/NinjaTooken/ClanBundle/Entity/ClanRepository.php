<?php

namespace NinjaTooken\ClanBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ClanRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ClanRepository extends EntityRepository
{
    public function getClans($order="date", $nombreParPage=5, $page=1)
    {
        $page = max(1, $page);

        $query = $this->createQueryBuilder('c')
            ->addSelect('c')
            ->leftJoin('NinjaTookenClanBundle:ClanUtilisateur', 'cu', 'WITH', 'c.id = cu.clan')
            ->addSelect('COUNT(cu) as num')
            ->where('c.online = :online')
            ->setParameter('online', true)
            ->groupBy('c.id')
            ->distinct(true);

        // par date d'ajout
        if($order == "date"){
           $query->addOrderBy('c.dateAjout', 'DESC');
        // par nombre de ninja
        }elseif($order == "ninja"){
            $query->addOrderBy('num', 'DESC');
        // par composition
        }elseif($order == "composition"){
            $query->leftJoin('NinjaTookenGameBundle:Ninja', 'n', 'WITH', 'n.user = cu.membre')
                ->addSelect('AVG(n.experience)*COUNT(cu) as avgxp')
                ->addOrderBy('avgxp', 'DESC');
        // par moyenne d'expérience
        }elseif($order == "experience"){
            $query->leftJoin('NinjaTookenGameBundle:Ninja', 'n', 'WITH', 'n.user = cu.membre')
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
}
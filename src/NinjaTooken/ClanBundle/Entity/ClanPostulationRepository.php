<?php
namespace NinjaTooken\ClanBundle\Entity;
 
use Doctrine\ORM\EntityRepository;
use NinjaTooken\ClanBundle\Entity\Clan;
use NinjaTooken\UserBundle\Entity\User;
 
class ClanPostulationRepository extends EntityRepository
{

    public function getByClanUser(Clan $clan=null, User $user=null)
    {
        $query = $this->createQueryBuilder('cp');

        if(isset($clan)){
            $query->where('cp.clan = :clan')
                ->setParameter('clan', $clan);
        }
        if(isset($user)){
            $query->andWhere('cp.postulant = :user')
                ->setParameter('user', $user);
        }

        return $query->getQuery()->getOneOrNullResult();
    }
}
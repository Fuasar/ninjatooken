<?php
namespace App\Repository;

use App\Entity\User\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    public function getSendMessages(UserInterface $user, $nombreParPage=5, $page=1)
    {
        $page = max(1, $page);

        $query = $this->createQueryBuilder('m')
            ->where('m.author = :author')
            ->andWhere('m.hasDeleted = 0')
            ->setParameter('author', $user)
            ->addOrderBy('m.dateAjout', 'DESC')
            ->setFirstResult(($page-1) * $nombreParPage)
            ->setMaxResults($nombreParPage)
            ->getQuery();

        return $query->getResult();
    }

    public function getFirstSendMessage(UserInterface $user)
    {
        $query = $this->createQueryBuilder('m')
            ->where('m.author = :author')
            ->andWhere('m.hasDeleted = 0')
            ->setParameter('author', $user)
            ->addGroupBy('m.id')
            ->addOrderBy('m.dateAjout', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getQuery();

        return $query->getResult();
    }

    public function getNumSendMessages(UserInterface $user)
    {
        $query = $this->createQueryBuilder('m')
            ->select('COUNT(m)')
            ->where('m.author = :author')
            ->andWhere('m.hasDeleted = 0')
            ->setParameter('author', $user)
            ->getQuery();

        return $query->getSingleScalarResult();
    }

    public function getReceiveMessages(UserInterface $user, $nombreParPage=5, $page=1)
    {
        $page = max(1, $page);

        $query = $this->createQueryBuilder('m')
            ->leftJoin('m.receivers', 'mu')
            ->where('mu.destinataire = :user')
            ->andWhere('mu.hasDeleted = 0')
            ->setParameter('user', $user)
            ->addGroupBy('m.id')
            ->addOrderBy('m.dateAjout', 'DESC')
            ->setFirstResult(($page-1) * $nombreParPage)
            ->setMaxResults($nombreParPage)
            ->getQuery();

        return $query->getResult();
    }

    public function getFirstReceiveMessage(UserInterface $user)
    {
        $query = $this->createQueryBuilder('m')
            ->leftJoin('m.receivers', 'mu')
            ->where('mu.destinataire = :user')
            ->andWhere('mu.hasDeleted = 0')
            ->setParameter('user', $user)
            ->addGroupBy('m.id')
            ->addOrderBy('m.dateAjout', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getQuery();

        return $query->getResult();
    }

    public function getNumReceiveMessages(UserInterface $user)
    {
        $query = $this->createQueryBuilder('m')
            ->leftJoin('m.receivers', 'mu')
            ->select('COUNT(m)')
            ->where('mu.destinataire = :user')
            ->andWhere('mu.hasDeleted = 0')
            ->setParameter('user', $user)
            ->getQuery();

        return $query->getSingleScalarResult();
    }

    public function getNumNewMessages(UserInterface $user)
    {
        $query = $this->createQueryBuilder('m')
            ->select('COUNT(m)')
            ->innerJoin('App\Entity\User\MessageUser', 'mu', 'WITH', 'm.id = mu.message')
            ->where('mu.destinataire = :user')
            ->andWhere('mu.dateRead is NULL')
            ->andWhere('mu.hasDeleted = :hasDeleted')
            ->setParameter('user', $user)
            ->setParameter('hasDeleted', false)
            ->getQuery();

        return $query->getSingleScalarResult();
    }
}
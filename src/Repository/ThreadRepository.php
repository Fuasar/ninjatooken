<?php
namespace App\Repository;

use App\Entity\Forum\Thread;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use App\Entity\Forum\Forum;
use App\Entity\User\User;
use Doctrine\Persistence\ManagerRegistry;

class ThreadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Thread::class);
    }

    public function getThreads(Forum $forum, $nombreParPage=5, $page=1): Paginator
    {
        $page = max(1, $page);

        $query = $this->createQueryBuilder('t')
            ->where('t.forum = :forum')
            ->setParameter('forum', $forum)
            ->addOrderBy('t.isPostit', 'DESC')
            ->addOrderBy('t.lastCommentAt', 'DESC')
            ->getQuery();

        $query->setFirstResult(($page-1) * $nombreParPage)
            ->setMaxResults($nombreParPage);

        return new Paginator($query);
    }

    public function getEvents($nombreParPage=5, $page=1): Paginator
    {
        $page = max(1, $page);

        $query = $this->createQueryBuilder('t')
            ->where('t.isEvent = :isEvent')
            ->setParameter('isEvent', true)
            ->addOrderBy('t.isPostit', 'DESC')
            ->addOrderBy('t.lastCommentAt', 'DESC')
            ->getQuery();

        $query->setFirstResult(($page-1) * $nombreParPage)
            ->setMaxResults($nombreParPage);

        return new Paginator($query);
    }

    public function searchThreads(User $user=null, Forum $forum=null, $q = "", $nombreParPage=5, $page=1)
    {
        $query = $this->createQueryBuilder('t')
            ->addOrderBy('t.isPostit', 'DESC')
            ->addOrderBy('t.lastCommentAt', 'DESC');

        if(!empty($q)){
            $query->andWhere('t.nom LIKE :q')
                ->andWhere('t.body LIKE :q')
                ->setParameter('q', '%'.$q.'%');
        }

        if(isset($user)){
            $query->andWhere('t.author = :user')
                ->setParameter('user', $user);
        }

        if(isset($forum)){
            $query->andWhere('t.forum = :forum')
                ->setParameter('forum', $forum);
        }

        $query->setFirstResult(($page-1) * $nombreParPage)
            ->setMaxResults($nombreParPage);

        return $query->getQuery()->getResult();
    }

    public function deleteThreadsByForum(Forum $forum = null): bool
    {
        if($forum){
            $query = $this->createQueryBuilder('t')
                ->delete('App\Entity\Forum\Thread', 't')
                ->where('t.forum = :forum')
                ->setParameter('forum', $forum)
                ->getQuery();
     
            return 1 === $query->getScalarResult();
        }
        return false;
    }
}
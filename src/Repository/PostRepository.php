<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Post::class);
    }

    public function findAllBy($tag, $orderBy, $direction = 'ASC', $limit = 10, $offset = 0): array {
        $qb = $this->createQueryBuilder('p')
                ->orderBy('p.' . $orderBy, $direction)
                ->setMaxResults($limit)
                ->setFirstResult($offset);

        if (!empty($tag)) {
            $qb->andWhere($qb->expr()->like('p.tags', ':tag'));
            $qb->setParameter('tag', '%' . $tag . '%');
        }

        $query = $qb->getQuery();

        return $query->execute();
    }

}

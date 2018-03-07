<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Tag;

/**
 * ArticleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArticleRepository extends \Doctrine\ORM\EntityRepository
{
    public function getArticleByTag(Tag $tag)
    {
        $qb = $this->createQueryBuilder('article');

        $qb
            ->join('article.tags', 'tag')
            ->where($qb->expr()->eq('tag.id', $tag->getId()));

        return $qb->getQuery()->getResult();
    }

    public function findByLike(array $array)
    {
        $title = array_keys($array);

        $qb = $this->createQueryBuilder('article');

        $qb->where($qb->expr()->like('article.' . array_shift($title), ':title'));
        $qb->setParameter('title', array_shift($array) . '%');

        return $qb->getQuery()->getResult();
    }

    public function findOneByLike(array $array)
    {
        $title = array_keys($array);

        $qb = $this->createQueryBuilder('article');

        $qb->where($qb->expr()->like('article.' . array_shift($title), ':title'));
        $qb->setParameter('title', '%' . array_shift($array) . '%');

        return $qb->getQuery()->getSingleResult();
    }
}

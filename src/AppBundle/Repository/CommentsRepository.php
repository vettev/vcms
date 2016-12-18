<?php

namespace AppBundle\Repository;

/**
 * CommentsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CommentsRepository extends \Doctrine\ORM\EntityRepository
{
  public function getCommentsByUserId($id)
  {
    $query = $this->createQueryBuilder('c')
        ->where("c.userId = :id")
        ->addOrderBy('c.createdAt', 'DESC')
        ->setParameter('id', $id)
        ->getQuery();
    
    return $query;
  }
}

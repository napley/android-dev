<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * PointRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PointRepository extends EntityRepository
{
    public function findPointByVersion($Android)
    {

        $query = $this->_em->createQuery('SELECT f FROM AppBundle:Point f JOIN f.Android fa WHERE fa.id = :version ORDER BY f.date ASC');
        $query->setParameter('version', $Android->getId());

        return $query->getResult();
    }

    
}

<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * InfoSiteRepository
 *
 */
class InfoSiteRepository extends EntityRepository
{

    public function findByArray($i)
    {
        // On passe par le QueryBuilder vide de l'EntityManager pour l'exemple
        $qb = $this->_em->createQueryBuilder();

        $qb->select('if')
                ->from('AppBundle:InfoSite', 'if')
                ->where('if.id = :id')
                ->setParameter('id', $i);

        return $qb->getQuery()->getArrayResult();
        
    }

}

<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * MotCleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MotCleRepository extends EntityRepository
{

    public function findByMotCle($text)
    {
        $query = $this->_em->createQuery('SELECT m FROM AppBundle:MotCle m 
                        WHERE m.nom = :text');
        $query->setParameter('text', $text);
        $motCle = $query->getOneOrNullResult();

        return $motCle;
    }
    
    public function findAllByMotCle($text)
    {
        $query = $this->_em->createQuery('SELECT m FROM AppBundle:MotCle m 
                        WHERE m.nom LIKE :text ORDER BY m.nom ASC');
        $query->setParameter('text', "%".$text."%");
        $motCle = $query->getResult();

        return $motCle;
    }

}

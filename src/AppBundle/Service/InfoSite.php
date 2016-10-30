<?php

namespace AppBundle\Service;

use Symfony\Bridge\Doctrine\RegistryInterface;

class InfoSite
{

    protected $doctrine;

    function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getInfoSite()
    {
        $repository = $this->doctrine->getRepository('AppBundle:InfoSite');

        $info = $repository->findByArray(1);
        
        return $info[0];
    }

}
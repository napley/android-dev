<?php

namespace AppBundle\Service;

use Symfony\Bridge\Doctrine\RegistryInterface;

class Menu
{

    protected $doctrine;

    function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getMenu()
    {
        $repository = $this->doctrine->getRepository('AppBundle:Type');

        $typeArticles = $repository->findCat(1);
        $typeTutos = $repository->findCat(2);
        
        $repositoryP = $this->doctrine->getRepository('AppBundle:Projet');
        $projets = $repositoryP->findNotEmpty();
        
        $menu = ['typeA' => $typeArticles, 'typeT' => $typeTutos, 'projets' => $projets];
        
        return $menu;
    }

}
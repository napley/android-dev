<?php

//src/AppBundle/Service/Point.php

namespace AppBundle\Service;

use Symfony\Bridge\Doctrine\RegistryInterface;

class Point
{

    protected $doctrine;

    function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getPoint($version, $date)
    {

        $Point = $this->doctrine
                ->getRepository('AppBundle:Android')
                ->findPoint($version, $date);


        if (!empty($Point)) {
            return $Point->getPct();
        } else {
            return 0;
        }
    }

}

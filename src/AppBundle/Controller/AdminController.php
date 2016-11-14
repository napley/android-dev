<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{

    /**
     * @Route("/admin", name="admin_homepage")
     * @Route("/admin/", name="admin_homepage")
     */
    public function indexAction()
    {
        

        return $this->render(
                        'admin/index.html.twig'
        );
    }

    

    /**
     * @Route("/update/stat", name="admin_update_stat")
     */
    public function updateStatAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        //Mise à jour des stats
        $repository = $em->getRepository('AppBundle:Article');
        $repoStat = $em->getRepository('AppBundle:Stat');

        $stats = $repoStat->getAllByRank(999);

        $content = array();
        foreach ($stats as $stat) {
            $em->remove($stat);
            $em->flush();
        }

        // this token is used to authenticate your API request. 
        // You can get the token on the API page inside your Piwik interface
        $token_auth = $this->getParameter('token_piwik');

        $firstDay = new \DateTime();
        $today = new \DateTime();
        $firstDay->modify('-7 day');

        $url = $this->getParameter('url_api_piwik') ."?module=API&method=Actions.getPageTitles&idSite=1&period=range&date=" . $firstDay->format('Y-m-d') . "," . $today->format('Y-m-d') . "&format=json&filter_limit=15&token_auth=" . $token_auth;

        $fetched = file_get_contents($url);
        $content = json_decode($fetched);
        
        foreach ($content as $cle => $article) {
            $content[$cle]->label = trim(str_replace(' | Android-dev.fr', '', html_entity_decode($article->label, ENT_QUOTES)));
            $article = $repository->findOneByTitre($content[$cle]->label);
            if ($article == null) {
                unset($content[$cle]);
            } else {
                $stat = new \AppBundle\Entity\Stat();
                $stat->setArticle($article);
                $stat->setRank($cle);
                
                $em->persist($stat);
                $em->flush();
            }
        }
        
        $stats = $em->getRepository('AppBundle:Stat')->findAll();
        
        return $this->render(
                        'admin/update/stat.html.twig', array(
                        'stats' => $stats
        ));
    }
}

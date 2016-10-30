<?php

namespace AppBundle\Twig;

use Symfony\Bridge\Doctrine\RegistryInterface;

class AppExtension extends \Twig_Extension
{

    /**
     * @var Markdown
     */
    private $doctrine;

    function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('infosite', array($this, 'getInfoSite')),
            new \Twig_SimpleFunction('toparticle', array($this, 'getTopArticle')),
            new \Twig_SimpleFunction('toppiwik', array($this, 'getTopPiwik')),
            new \Twig_SimpleFunction('lastupdate', array($this, 'getLastUpdate')),
            new \Twig_SimpleFunction('filtrecat', array($this, 'getFiltreCat')),
            new \Twig_SimpleFunction('nbtotal', array($this, 'getNbTotal')),
            new \Twig_SimpleFunction('headerquote', array($this, 'getHeaderQuote')),
            new \Twig_SimpleFunction('partproject', array($this, 'getPartProject')),
            new \Twig_SimpleFunction('articlekey', array($this, 'getArticleKey')),
        );
    }

    /**
     * @return array
     */
    public function getInfoSite()
    {
        $repository = $this->doctrine->getRepository('AppBundle:InfoSite');

        $info = $repository->findByArray(1);

        return array(
            'infosite' => $info[0],
        );
    }

    /**
     * @return array
     */
    public function getTopArticle()
    {
        $repository = $this->doctrine->getRepository('AppBundle:Article');

        $article = $repository->findByTop();

        return $article;
    }

    /**
     * @return array
     */
    public function getTopPiwik($nb)
    {
        $repository = $this->doctrine->getRepository('AppBundle:Stat');

        $stats = $repository->getAllByRank($nb);
        $articles = [];
        foreach ($stats as $stat) {
            if (!empty($stat->getArticle())) {
                $articles[] = $stat->getArticle();
            }
        }

        return $articles;
    }

    /**
     * @return array
     */
    public function getLastUpdate()
    {
        $repository = $this->doctrine->getRepository('AppBundle:Article');

        $articles = $repository->findLastUpdateArticle(3);

        return $articles;
    }

    /**
     * @return array
     */
    public function getFiltreCat($param)
    {
        $elements = array();

        switch ($param) {
            case 'article' :
            case 'tuto' :
                if ($param == 'article') {
                    $repository = $this->doctrine->getRepository('AppBundle:Type');
                    $cats = $repository->findCat(1);
                    $link_name = 'article_cat';
                }
                if ($param == 'tuto') {
                    $repository = $this->doctrine->getRepository('AppBundle:Type');
                    $cats = $repository->findCat(2);
                    $link_name = 'tuto_cat';
                }
                foreach ($cats as $cat) {
                    $elements[] = ['nom' => $cat->getNom(), 'slug' => $cat->getSlug(), 'link'=> $link_name ];
                }

                break;
                
            case 'projet' :
                $repository = $this->doctrine->getRepository('AppBundle:Projet');
                $projets = $repository->findNotEmpty();
                foreach ($projets as $projet) {
                    $elements[] = ['nom' => $projet->getTitre(), 'slug' => $projet->getSlug(), 'link'=> 'projet_voir' ];
                }
                
                break;
        }


        return $elements;
    }

    /**
     * @return array
     */
    public function getNbTotal($param)
    {
        $nb = 0;

        switch ($param) {
            case 'article' :
            case 'tuto' :
                if ($param == 'article') {
                    $repository = $this->doctrine->getRepository('AppBundle:Article');
                    $nb = $repository->findTotal(1);
                }
                if ($param == 'tuto') {
                    $repository = $this->doctrine->getRepository('AppBundle:Article');
                    $nb = $repository->findTotal(2);
                }

                break;
                
            case 'projet' :
                $repository = $this->doctrine->getRepository('AppBundle:Projet');
                $nb = $repository->findTotal();
                
                break;
        }
        
        return $nb;
    }

    /**
     * @return array
     */
    public function getHeaderQuote()
    {
        $repository = $this->doctrine->getRepository('AppBundle:Quote');

        $quote = $repository->findByRand();

        return $quote;
    }
    

    /**
     * @return array
     */
    public function getPartProject()
    {
        $repository = $this->doctrine->getRepository('AppBundle:Article');

        $articles = $repository->findAllPartProject();
        
        return $articles;
    }
    

    /**
     * @return array
     */
    public function getArticleKey($id = 64, $nb = 5)
    {
        $repository = $this->doctrine->getRepository('AppBundle:Article');
        $articles = $repository->findArticleCatByIdKey($id, $nb);

        return $articles;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        // the name of the Twig extension must be unique in the application. Consider
        // using 'app.extension' if you only have one Twig extension in your application.
        return 'app.extension';
    }

}

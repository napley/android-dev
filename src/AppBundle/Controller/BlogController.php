<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Article;
use AppBundle\Entity\Categorie;
use AppBundle\Entity\MotCle;
use AppBundle\Entity\Projet;
use AppBundle\Entity\Contact;
use AppBundle\Form\ContactType;

class BlogController extends Controller
{

    /**
     * @Route("/", name="homepage", defaults={"page" = 1})
     * @Route("/{page}", name="blog_index_paginated", requirements={"page" : "\d+"})
     */
    public function indexAction($page)
    {
        $infoSite = $this->container->get('app.infosite')->getInfoSite();

        $query = $this->getDoctrine()->getRepository('AppBundle:Article')->getLatest(1);
        $paginator = $this->get('knp_paginator');
        $articles = $paginator->paginate($query, $page, $infoSite['nbByPageHome']);
        $articles->setUsedRoute('blog_index_paginated');

        $query = $this->getDoctrine()->getRepository('AppBundle:Article')->getLatest(2);
        $paginator = $this->get('knp_paginator');
        $tutos = $paginator->paginate($query, $page, $infoSite['nbByPageHome']);
        $tutos->setUsedRoute('blog_index_paginated');

        $query = $this->getDoctrine()->getRepository('AppBundle:Projet')->findLastNotEmpty();
        $paginator = $this->get('knp_paginator');
        $projets = $paginator->paginate($query, $page, $infoSite['nbByPageHome']);
        $projets->setUsedRoute('blog_index_paginated');

        return $this->render(
                        'blog/index.html.twig', ['articles' => $articles,
                    'tutos' => $tutos,
                    'projets' => $projets,
                    'page' => $page
        ]);
    }

    /**
     * @Route("/mentions", name="mentions")
     * @Route("/mentions/", name="mentions")
     */
    public function mentionsAction()
    {
        return $this->render(
                        'blog/mentions.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     * @Route("/contact/", name="contact")
     */
    public function contactAction(Request $request)
    {
        $contact = new Contact();
        $success = false;
        $error = false;

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // les données sont un tableau avec les clés "name", "email", et "message"
            $data = $form->getData();

            $message = \Swift_Message::newInstance();
            $message->setSubject($contact->getObjet());
            $message->setFrom($contact->getMail());
            $message->setTo('postmaster@android-dev.fr');
            $message->setBody($this->renderView('blog/part/email.html.twig', array('data' => $data)));
            $message->setContentType("text/html");
            $send = $this->get('mailer')->send($message);

            if ($send) {
                $success = true;
                $contact = new Contact();
                $form = $this->createForm(ContactType::class, $contact);
            } else {
                $error = true;
            }
        }

        return $this->render(
                'blog/contact.html.twig', [
                    'form' => $form->createView(),
                    'success' => $success,
                    'error' => $error
                ]
        );
    }

    /**
     * @Route("/apropos", name="apropos")
     * @Route("/apropos/", name="apropos")
     */
    public function aproposAction()
    {
        return $this->render(
                        'blog/apropos.html.twig');
    }
    
    /**
    * @Route("/a_propos", name="old_apropos")
    * @Route("/a_propos/", name="old_apropos")
    */
    public function oldAProposAction()
    {
        return $this->redirect($this->generateUrl('apropos'), 301);
    }


    /**
     * @Route("/fragmentation", name="fragmentation")
     * @Route("/fragmentation/", name="fragmentation")
     */
    public function fragmentAction()
    {
        $dtStart = new \DateTime('2009-12-01 00:00:00');
        $today = new \DateTime();

        $Androids = $this->getDoctrine()
                ->getManager()
                ->getRepository('AppBundle:Android')
                ->findAll();

        $arrayPoint = array();
        while ($today > $dtStart) {
            $trouve = 0;
            foreach ($Androids as $key => $Android) {
                $pct = $this->container->get('app.point')->getPoint($Android, $dtStart);

                if ($pct > 0) {
                    $arrayPoint['courbe' . $key]['data'][$dtStart->getTimestamp()] = $pct;
                    $arrayPoint['courbe' . $key]['titre'] = $Android->getTitre();
                    $arrayPoint['courbe' . $key]['code'] = $Android->getCode();
                    $trouve = 1;
                }
            }
            if ($trouve) {
                foreach ($Androids as $key => $And) {
                    if (!isset($arrayPoint['courbe' . $key]['data'][$dtStart->getTimestamp()])) {
                        $arrayPoint['courbe' . $key]['data'][$dtStart->getTimestamp()] = 0;
                        $arrayPoint['courbe' . $key]['titre'] = $And->getTitre();
                        $arrayPoint['courbe' . $key]['code'] = $And->getCode();
                    }
                }
            }
            $dtStart->modify('+1 month');
        }

        $repository = $this->getDoctrine()->getRepository('AppBundle:Article');
        $links = $repository->findArticleCatByIdKey(64, 12);


        return $this->render('blog/fragmentation.html.twig', [
                    'Points' => $arrayPoint,
                    'links' => $links
        ]);
    }

    /**
     * @Route("/mot-cle/{slug}", name="motcle", defaults={"page" = 1})
     * @Route("/mot-cle/{slug}/{page}", name="mot-cle_paginated", requirements={"slug" : "[a-zA-Z0-9_-]+", "page" : "\d+"})
     * @ParamConverter("categorie", class="AppBundle:MotCle", options={"slug" = "slug"})
     */
    public function motcleAction(MotCle $motcle, $page)
    {
        $infoSite = $this->container->get('app.infosite')->getInfoSite();

        $query = $this->getDoctrine()->getRepository('AppBundle:Article')->findByMotCles($motcle->getSlug());
        $paginator = $this->get('knp_paginator');
        $articles = $paginator->paginate($query, $page, $infoSite['nbByPage']);
        $articles->setUsedRoute('mot-cle_paginated');

        return $this->render(
                        'blog/motcle.html.twig', ['articles' => $articles,
                    'page' => $page,
                    'motcle' => $motcle
        ]);
    }

    /**
     * @Route("/article", name="home_article", defaults={"page" = 1})
     * @Route("/article/{page}", name="home_article_paginated", requirements={"page" : "\d+"})
     */
    public function indexArticleAction($page)
    {
        $infoSite = $this->container->get('app.infosite')->getInfoSite();

        $query = $this->getDoctrine()->getRepository('AppBundle:Article')->getLatest(1);
        $paginator = $this->get('knp_paginator');
        $articles = $paginator->paginate($query, $page, $infoSite['nbByPage']);
        $articles->setUsedRoute('home_article_paginated');

        return $this->render(
                        'blog/articles.html.twig', ['articles' => $articles,
                    'page' => $page,
                    'current' => 'Articles'
        ]);
    }

    /**
     * @Route("/article/{slug}", name="article_cat", defaults={"page" = 1})
     * @Route("/article/{slug}/{page}", name="article_cat_paginated", requirements={"slug" : "[a-zA-Z0-9_-]+", "page" : "\d+"})
     * @ParamConverter("categorie", class="AppBundle:Categorie", options={"slug" = "slug"})
     */
    public function indexArticleCatAction(Categorie $categorie, $page)
    {
        $infoSite = $this->container->get('app.infosite')->getInfoSite();

        $query = $this->getDoctrine()->getRepository('AppBundle:Article')->findArticleByCat($categorie);
        $paginator = $this->get('knp_paginator');
        $articles = $paginator->paginate($query, $page, $infoSite['nbByPage']);
        $articles->setUsedRoute('article_cat_paginated');

        return $this->render(
                        'blog/articles.html.twig', ['articles' => $articles,
                    'page' => $page,
                    'current' => 'Articles',
                    'categorie' => $categorie
        ]);
    }

    /**
     * @Route("/tuto", name="home_tuto", defaults={"page" = 1})
     * @Route("/tuto/", name="home_tuto", defaults={"page" = 1})
     * @Route("/tuto/{page}", name="home_tuto_paginated", requirements={"page" : "\d+"})
     */
    public function indexTutoAction($page)
    {
        $infoSite = $this->container->get('app.infosite')->getInfoSite();

        $query = $this->getDoctrine()->getRepository('AppBundle:Article')->getLatest(2);
        $paginator = $this->get('knp_paginator');
        $articles = $paginator->paginate($query, $page, $infoSite['nbByPage']);
        $articles->setUsedRoute('home_tuto_paginated');

        return $this->render(
                'blog/tutos.html.twig', ['articles' => $articles,
                    'page' => $page,
                    'current' => 'Tutos'
        ]);
    }
    
    /**
     * @Route("/astuce", name="old_home_tuto", defaults={"page" = 1})
     * @Route("/astuce/{page}", name="old_home_tuto_paginated", requirements={"page" : "\d+"})
    */
    public function indexOldTutoAction($page)
    {
        return $this->redirect($this->generateUrl('home_tuto_paginated', array('page' => $page)), 301);
    }

    /**
     * @Route("/tuto/{slug}", name="tuto_cat", defaults={"page" = 1})
     * @Route("/tuto/{slug}/{page}", name="tuto_cat_paginated", requirements={"slug" : "[a-zA-Z0-9_-]+", "page" : "\d+"})
     * @ParamConverter("categorie", class="AppBundle:Categorie", options={"slug" = "slug"})
     */
    public function indexTutoCatAction(Categorie $categorie, $page)
    {
        $infoSite = $this->container->get('app.infosite')->getInfoSite();

        $query = $this->getDoctrine()->getRepository('AppBundle:Article')->findTutoByCat($categorie);
        $paginator = $this->get('knp_paginator');
        $articles = $paginator->paginate($query, $page, $infoSite['nbByPage']);
        $articles->setUsedRoute('tuto_cat_paginated');

        return $this->render(
                'blog/tutos.html.twig', ['articles' => $articles,
                    'page' => $page,
                    'current' => 'Tutos',
                    'categorie' => $categorie
        ]);
    }
    
    /**
     * @Route("/astuce/{slug}", name="old_tuto_cat", defaults={"page" = 1})
     * @Route("/astuce/{slug}/{page}", name="old_tuto_cat_paginated", requirements={"slug" : "[a-zA-Z0-9_-]+", "page" : "\d+"})
     * @ParamConverter("categorie", class="AppBundle:Categorie", options={"slug" = "slug"})
    */
    public function indexOldTutoCatAction(Categorie $categorie, $page)
    {
        return $this->redirect($this->generateUrl('tuto_cat_paginated', array('slug' => $categorie->getSlug(), 'page' => $page)), 301);
    }

    /**
     * @Route("/projet", name="home_projet", defaults={"page" = 1})
     * @Route("/projet/{page}", name="home_projet_paginated", requirements={"page" : "\d+"})
     */
    public function indexProjetAction($page)
    {
        $infoSite = $this->container->get('app.infosite')->getInfoSite();

        $query = $this->getDoctrine()->getRepository('AppBundle:Projet')->findLastNotEmpty();
        $paginator = $this->get('knp_paginator');
        $projets = $paginator->paginate($query, $page, $infoSite['nbByPage']);
        $projets->setUsedRoute('home_projet_paginated');

        return $this->render(
                        'blog/projets.html.twig', ['projets' => $projets,
                    'page' => $page,
                    'current' => 'Projets'
        ]);
    }

    /**
     * @Route("/projet/{slug}", name="projet_voir")
     * @ParamConverter("projet", class="AppBundle:Projet", options={"slug" = "slug"})
     */
    public function projetVoirAction(Projet $projet)
    {
        if (!empty($projet)) {
            $links = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('AppBundle:Projet')
                    ->findLastNotEmpty();
        }

        return $this->render(
                        'blog/projet-detail.html.twig', [
                    'projet' => $projet,
                    'current' => 'Projets',
                    'links' => $links
        ]);
    }

    /**
     * @Route("/recherche/", name="search_form")
     */
    public function searchAction(Request $request)
    {
        return $this->redirect($this->generateUrl('page_recherche', array('motcles' => str_replace(' ', '+', $request->request->get('motCles')))));
    }

    /**
     * @Route("/recherche/{motcles}/", name="page_recherche", defaults={"page" = 1})
     * @Route("/recherche/{motcles}/{page}", name="page_recherche_paginated", requirements={"page" : "\d+"})
     */
    public function rechercheAction($motcles, $page)
    {
        $infoSite = $this->container->get('app.infosite')->getInfoSite();
        $motCle = explode(' ', str_replace('+', ' ', $motcles));

        $query = $this->getDoctrine()->getRepository('AppBundle:Article')->findArticleBySearch($motCle);
        $paginator = $this->get('knp_paginator');
        $articles = $paginator->paginate($query, $page, $infoSite['nbByPage']);
        $articles->setUsedRoute('page_recherche_paginated');

        $motCleJoin = implode(' - ', $motCle);

        return $this->render(
                        'blog/search.html.twig', [
                    'articles' => $articles,
                    'motCle' => $motCleJoin,
                    'page' => $page
                        ]
        );
    }

    /**
     * @Route("/error", name="error")
     */
    public function errorAction()
    {


        return $this->render('blog/error.html.twig');
    }
    
    /**
     * Generate the article feed
     *
     * @Route("/feeds/articles.rss", name="flux_rss")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function feedAction()
    {

        $articles = $this->getDoctrine()->getRepository('AppBundle:Article')->getLatest();

        $feed = $this->get('eko_feed.feed.manager')->get('article');
        $feed->addFromArray($articles);

        return new Response($feed->render('rss')); // or 'atom'
    }
    

    /**
     * @Route("/sitemap.xml", name="sitemap")
     */
    public function sitemapAction() 
    {        
        $urls = [];
        $request = Request::createFromGlobals();
        $hostname = $request->headers->get('host');
        
        // add some urls homepage
        $urls[] = ['loc' => $this->generateUrl('homepage'), 'changefreq' => 'weekly', 'priority' => '1.0'];
        $urls[] = ['loc' => $this->generateUrl('flux_rss'), 'changefreq' => 'weekly', 'priority' => '0.5'];
        $urls[] = ['loc' => $this->generateUrl('apropos'), 'changefreq' => 'weekly', 'priority' => '0.5'];
        $urls[] = ['loc' => $this->generateUrl('contact'), 'changefreq' => 'weekly', 'priority' => '0.5'];
        $urls[] = ['loc' => $this->generateUrl('mentions'), 'changefreq' => 'weekly', 'priority' => '0.5'];
        $urls[] = ['loc' => $this->generateUrl('fragmentation'), 'changefreq' => 'weekly', 'priority' => '0.5'];
        
        // multi-lang pages
//        foreach($languages as $lang) {
//            $urls[] = ['loc' => $this->get('router')->generate('home_contact', ['_locale' => $lang)), 'changefreq' => 'monthly', 'priority' => '0.3'];
//        }
        
        // urls from database
//        $urls[] = ['loc' => $this->get('router')->generate('home_product_overview', ['_locale' => 'en')), 'changefreq' => 'weekly', 'priority' => '0.7'];
        // service
        
        foreach ( $this->getDoctrine()->getRepository('AppBundle:Article')->getLatest() as $article) {
            $urls[] = ['loc' => $this->generateUrl('article_voir', 
                    ['slug' => $article->getSlug()]), 'priority' => '0.5'];
        }
        
        foreach ($this->getDoctrine()
                    ->getManager()
                    ->getRepository('AppBundle:Projet')
                    ->findLastNotEmpty() as $projet) {
            $urls[] = ['loc' => $this->generateUrl('projet_voir', 
                    ['slug' => $projet->getSlug()]), 'priority' => '0.5'];
        }
        
        return $this->render(
                        'blog/sitemap.xml.twig', [
                            'urls' => $urls, 
                            'hostname' => $hostname
                        ]
        );
    }
    

    /**
     * @Route("/web/blog/voir/{id}", name="old_article_voir")
     * @Route("/web/blog/voir/{id}?titre={titre}", name="old_article_voir")
     * @ParamConverter("article", class="AppBundle:Article", options={"id" = "id"})
     */
    public function redirectAction(Article $article)
    {

        if (empty($article)) {
            return $this->redirect($this->generateUrl('androiddev_accueil'), 301);
        }

        return $this->redirect($this->generateUrl('article_voir', array('slug' => $article->getSlug())), 301);
    }

    /**
     * @Route("/{slug}", name="article_voir")
     * @ParamConverter("article", class="AppBundle:Article", options={"slug" = "slug"})
     */
    public function voirAction(Article $article)
    {
        $menuCurrent = $this->getDoctrine()->getRepository('AppBundle:Type')->findMenuEquiv($article->getType());

        if (!empty($article)) {
            $category = $article->getCategorie();
            if (!empty($category)) {
                $links = $this->getDoctrine()
                        ->getManager()
                        ->getRepository('AppBundle:Article')
                        ->findLink($article, 12);
            } else {
                $links = null;
            }
        }

        return $this->render(
                        'blog/voir.html.twig', [
                    'article' => $article,
                    'current' => $menuCurrent,
                    'links' => $links
        ]);
    }

}

<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Article;
use AppBundle\Form\ArticleType;

/**
 * Article controller.
 *
 * @Route("/admin/article")
 */
class ArticleController extends Controller
{
    /**
     * Lists all Article entities.
     *
     * @Route("/", name="admin_article_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();        
        $articles = $em->getRepository('AppBundle:Article')
            ->findBy([], ['id' => 'DESC']);

        return $this->render('admin/article/index.html.twig', array(
            'articles' => $articles,
        ));
    }

    /**
     * Creates a new Article entity.
     *
     * @Route("/new", name="admin_article_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $article = new Article();
        $form = $this->createForm('AppBundle\Form\ArticleType', $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
//            $article->setAuteur($em->getRepository('AppBundle:User')->find(1));
            $slug = $this->get('app.slugger')->slugify($article->getTitre());
            $article->setSlug($slug);
            $em->persist($article);
            
            $motsCles = $request->request->get('motCle');

            if (!empty($motsCles)) {
                foreach ($motsCles as $cle => $textMotCle) {
                    $motCle = $em->getRepository('AppBundle:MotCle')->findByMotCle($textMotCle);
                    if (!empty($motCle)) {
                        $article->addMotCle($motCle);
                    } else {
                        $motCle = new \AppBundle\Entity\MotCle();
                        $motCle->setNom($textMotCle);
                        $slug = $this->get('app.slugger')->slugify($textMotCle);
                        $motCle->setSlug($slug);
                        $article->addMotCle($motCle);
                        $em->persist($motCle);
                    }
                }
            }

            $em->flush();

            return $this->redirectToRoute('admin_article_show', array('id' => $article->getId()));
        }

        return $this->render('admin/article/new.html.twig', array(
            'article' => $article,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Article entity.
     *
     * @Route("/{id}", name="admin_article_show")
     * @Method("GET")
     */
    public function showAction(Article $article)
    {
        $deleteForm = $this->createDeleteForm($article);

        return $this->render('admin/article/show.html.twig', array(
            'article' => $article,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Article entity.
     *
     * @Route("/{id}/edit", name="admin_article_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Article $article)
    {
        $originalMotCles = [];
        foreach ($article->getMotCles() as $motcle) {
            $originalMotCles[] = $motcle;
        }
        
        $deleteForm = $this->createDeleteForm($article);
        $editForm = $this->createForm('AppBundle\Form\ArticleType', $article);
        $editForm->handleRequest($request);
        
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $slug = $this->get('app.slugger')->slugify($article->getTitre());
            $article->setSlug($slug);
            
            $motsCles = $request->request->get('motCle');
            
            foreach ($article->getMotCles() as $motCle) {
                $trouve = false;
                if (!empty($motsCles)) {
                    foreach ($motsCles as $cle => $textMotCle) {
                        if ($textMotCle == $motCle->getNom()) {
                            $trouve = true;
                        }
                    }
                }
                if ($trouve == false) {
                    $article->removeMotcle($motCle);
                }
            }

            if (!empty($motsCles)) {
                foreach ($motsCles as $cle => $textMotCle) {
                    $motCle = $em->getRepository('AppBundle:MotCle')->findByMotCle($textMotCle);
                    if (!empty($motCle)) {
                        $article->addMotCle($motCle);
                    } else {
                        $motCle = new \AppBundle\Entity\MotCle();
                        $motCle->setNom($textMotCle);
                        $slug = $this->get('app.slugger')->slugify($textMotCle);
                        $motCle->setSlug($slug);
                        $article->addMotCle($motCle);
                        $em->persist($motCle);
                    }
                }
            }

            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('admin_article_edit', array('id' => $article->getId()));
        }

        return $this->render('admin/article/edit.html.twig', array(
            'article' => $article,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Article entity.
     *
     * @Route("/{id}", name="admin_article_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Article $article)
    {
        $form = $this->createDeleteForm($article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($article);
            $em->flush();
        }

        return $this->redirectToRoute('admin_article_index');
    }

    /**
     * Creates a form to delete a Article entity.
     *
     * @param Article $article The Article entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Article $article)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_article_delete', array('id' => $article->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

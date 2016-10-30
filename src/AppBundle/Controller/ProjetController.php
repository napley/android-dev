<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Projet;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Projet controller.
 *
 * @Route("admin/projet")
 */
class ProjetController extends Controller
{
    /**
     * Lists all projet entities.
     *
     * @Route("/", name="admin_projet_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $projets = $em->getRepository('AppBundle:Projet')->findAll();

        return $this->render('admin/projet/index.html.twig', array(
            'projets' => $projets,
        ));
    }

    /**
     * Creates a new projet entity.
     *
     * @Route("/new", name="admin_projet_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $projet = new Projet();
        $form = $this->createForm('AppBundle\Form\ProjetType', $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $slug = $this->get('app.slugger')->slugify($projet->getTitre());
            $projet->setSlug($slug);
            $em->persist($projet);
            
            $request = Request::createFromGlobals();
            $partProjects = $request->request->get('partProject');

            if (!empty($partProjects)) {
                foreach ($partProjects['id'] as $cle => $idPartProject) {
                    $article = $em->getRepository('AppBundle:Article')->find($idPartProject);
                    $PartProject = new \AppBundle\Entity\ArticleProjet($projet, $article, $partProjects['index'][$cle]);
                    $em->persist($PartProject);
                }
            }
            
            $em->flush();

            return $this->redirectToRoute('admin_projet_show', array('id' => $projet->getId()));
        }

        return $this->render('admin/projet/new.html.twig', array(
            'projet' => $projet,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a projet entity.
     *
     * @Route("/{id}", name="admin_projet_show")
     * @Method("GET")
     */
    public function showAction(Projet $projet)
    {
        $deleteForm = $this->createDeleteForm($projet);

        return $this->render('admin/projet/show.html.twig', array(
            'projet' => $projet,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing projet entity.
     *
     * @Route("/{id}/edit", name="admin_projet_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Projet $projet)
    {
        $deleteForm = $this->createDeleteForm($projet);
        $editForm = $this->createForm('AppBundle\Form\ProjetType', $projet);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $slug = $this->get('app.slugger')->slugify($projet->getTitre());
            $projet->setSlug($slug);
            
            $request = Request::createFromGlobals();
            $partProjects = $request->request->get('partProject');
            
            foreach ($projet->getArticles() as $part) {
                $trouve = false;
                foreach ($partProjects['id'] as $cle => $idPartProject) {
                    if ($idPartProject == $part->getArticle()->getId()) {
                        $trouve = true;
                    }
                }
                if ($trouve == false) {
                    $em->remove($part);
                }
            }

            if (!empty($partProjects)) {
                foreach ($partProjects['id'] as $cle => $idPartProject) {
                    $article = $em->getRepository('AppBundle:Article')->find($idPartProject);
                    $part = $em->getRepository('AppBundle:ArticleProjet')->findPartByProjetAndArticle($projet, $article);
                    
                    if (!empty($part)) {
                        $part->setRang($partProjects['index'][$cle]);
                    } else {
                        $PartProject = new \AppBundle\Entity\ArticleProjet($projet, $article, $partProjects['index'][$cle]);
                        $em->persist($PartProject);
                    }
                }
            }
            
            $em->flush();

            return $this->redirectToRoute('admin_projet_edit', array('id' => $projet->getId()));
        }

        return $this->render('admin/projet/edit.html.twig', array(
            'projet' => $projet,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a projet entity.
     *
     * @Route("/{id}", name="admin_projet_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Projet $projet)
    {
        $form = $this->createDeleteForm($projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($projet);
            $em->flush($projet);
        }

        return $this->redirectToRoute('admin_projet_index');
    }

    /**
     * Creates a form to delete a projet entity.
     *
     * @param Projet $projet The projet entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Projet $projet)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_projet_delete', array('id' => $projet->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

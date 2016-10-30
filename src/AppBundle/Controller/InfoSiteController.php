<?php

namespace AppBundle\Controller;

use AppBundle\Entity\InfoSite;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Infosite controller.
 *
 * @Route("admin/infosite")
 */
class InfoSiteController extends Controller
{
    /**
     * Lists all infoSite entities.
     *
     * @Route("/", name="admin_infosite_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $infoSites = $em->getRepository('AppBundle:InfoSite')->findAll();

        return $this->render('admin/infosite/index.html.twig', array(
            'infoSites' => $infoSites,
        ));
    }

    /**
     * Creates a new infoSite entity.
     *
     * @Route("/new", name="admin_infosite_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $infoSite = new Infosite();
        $form = $this->createForm('AppBundle\Form\InfoSiteType', $infoSite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($infoSite);
            $em->flush($infoSite);

            return $this->redirectToRoute('admin_infosite_show', array('id' => $infoSite->getId()));
        }

        return $this->render('admin/infosite/new.html.twig', array(
            'infoSite' => $infoSite,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a infoSite entity.
     *
     * @Route("/{id}", name="admin_infosite_show")
     * @Method("GET")
     */
    public function showAction(InfoSite $infoSite)
    {
        $deleteForm = $this->createDeleteForm($infoSite);

        return $this->render('admin/infosite/show.html.twig', array(
            'infoSite' => $infoSite,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing infoSite entity.
     *
     * @Route("/{id}/edit", name="admin_infosite_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, InfoSite $infoSite)
    {
        $deleteForm = $this->createDeleteForm($infoSite);
        $editForm = $this->createForm('AppBundle\Form\InfoSiteType', $infoSite);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_infosite_edit', array('id' => $infoSite->getId()));
        }

        return $this->render('admin/infosite/edit.html.twig', array(
            'infoSite' => $infoSite,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a infoSite entity.
     *
     * @Route("/{id}", name="admin_infosite_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, InfoSite $infoSite)
    {
        $form = $this->createDeleteForm($infoSite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($infoSite);
            $em->flush($infoSite);
        }

        return $this->redirectToRoute('admin_infosite_index');
    }

    /**
     * Creates a form to delete a infoSite entity.
     *
     * @param InfoSite $infoSite The infoSite entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(InfoSite $infoSite)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_infosite_delete', array('id' => $infoSite->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

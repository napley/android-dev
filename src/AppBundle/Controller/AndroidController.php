<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Android;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Android controller.
 *
 * @Route("admin/android")
 */
class AndroidController extends Controller
{
    /**
     * Lists all android entities.
     *
     * @Route("/", name="admin_android_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $androids = $em->getRepository('AppBundle:Android')->findAll();

        return $this->render('admin/android/index.html.twig', array(
            'androids' => $androids,
        ));
    }

    /**
     * Creates a new android entity.
     *
     * @Route("/new", name="admin_android_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $android = new Android();
        $form = $this->createForm('AppBundle\Form\AndroidType', $android);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($android);
            $em->flush($android);

            return $this->redirectToRoute('admin_android_show', array('id' => $android->getId()));
        }

        return $this->render('admin/android/new.html.twig', array(
            'android' => $android,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a android entity.
     *
     * @Route("/{id}", name="admin_android_show")
     * @Method("GET")
     */
    public function showAction(Android $android)
    {
        $deleteForm = $this->createDeleteForm($android);

        return $this->render('admin/android/show.html.twig', array(
            'android' => $android,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing android entity.
     *
     * @Route("/{id}/edit", name="admin_android_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Android $android)
    {
        $deleteForm = $this->createDeleteForm($android);
        $editForm = $this->createForm('AppBundle\Form\AndroidType', $android);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_android_edit', array('id' => $android->getId()));
        }

        return $this->render('admin/android/edit.html.twig', array(
            'android' => $android,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a android entity.
     *
     * @Route("/{id}", name="admin_android_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Android $android)
    {
        $form = $this->createDeleteForm($android);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($android);
            $em->flush($android);
        }

        return $this->redirectToRoute('admin_android_index');
    }

    /**
     * Creates a form to delete a android entity.
     *
     * @param Android $android The android entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Android $android)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_android_delete', array('id' => $android->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

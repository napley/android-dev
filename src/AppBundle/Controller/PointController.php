<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Point;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Point controller.
 *
 * @Route("admin/point")
 */
class PointController extends Controller
{
    /**
     * Lists all point entities.
     *
     * @Route("/", name="admin_point_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $points = $em->getRepository('AppBundle:Point')->findAll();

        return $this->render('admin/point/index.html.twig', array(
            'points' => $points,
        ));
    }

    /**
     * Creates a new point entity.
     *
     * @Route("/new", name="admin_point_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $point = new Point();
        $form = $this->createForm('AppBundle\Form\PointType', $point);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($point);
            $em->flush($point);

            return $this->redirectToRoute('admin_point_show', array('id' => $point->getId()));
        }

        return $this->render('admin/point/new.html.twig', array(
            'point' => $point,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a point entity.
     *
     * @Route("/{id}", name="admin_point_show")
     * @Method("GET")
     */
    public function showAction(Point $point)
    {
        $deleteForm = $this->createDeleteForm($point);

        return $this->render('admin/point/show.html.twig', array(
            'point' => $point,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing point entity.
     *
     * @Route("/{id}/edit", name="admin_point_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Point $point)
    {
        $deleteForm = $this->createDeleteForm($point);
        $editForm = $this->createForm('AppBundle\Form\PointType', $point);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_point_edit', array('id' => $point->getId()));
        }

        return $this->render('admin/point/edit.html.twig', array(
            'point' => $point,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a point entity.
     *
     * @Route("/{id}", name="admin_point_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Point $point)
    {
        $form = $this->createDeleteForm($point);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($point);
            $em->flush($point);
        }

        return $this->redirectToRoute('admin_point_index');
    }

    /**
     * Creates a form to delete a point entity.
     *
     * @param Point $point The point entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Point $point)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_point_delete', array('id' => $point->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

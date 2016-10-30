<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Quote;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Quote controller.
 *
 * @Route("admin/quote")
 */
class QuoteController extends Controller
{
    /**
     * Lists all quote entities.
     *
     * @Route("/", name="admin_quote_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $quotes = $em->getRepository('AppBundle:Quote')->findAll();

        return $this->render('admin/quote/index.html.twig', array(
            'quotes' => $quotes,
        ));
    }

    /**
     * Creates a new quote entity.
     *
     * @Route("/new", name="admin_quote_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $quote = new Quote();
        $form = $this->createForm('AppBundle\Form\QuoteType', $quote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($quote);
            $em->flush($quote);

            return $this->redirectToRoute('admin_quote_show', array('id' => $quote->getId()));
        }

        return $this->render('admin/quote/new.html.twig', array(
            'quote' => $quote,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a quote entity.
     *
     * @Route("/{id}", name="admin_quote_show")
     * @Method("GET")
     */
    public function showAction(Quote $quote)
    {
        $deleteForm = $this->createDeleteForm($quote);

        return $this->render('admin/quote/show.html.twig', array(
            'quote' => $quote,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing quote entity.
     *
     * @Route("/{id}/edit", name="admin_quote_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Quote $quote)
    {
        $deleteForm = $this->createDeleteForm($quote);
        $editForm = $this->createForm('AppBundle\Form\QuoteType', $quote);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_quote_edit', array('id' => $quote->getId()));
        }

        return $this->render('admin/quote/edit.html.twig', array(
            'quote' => $quote,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a quote entity.
     *
     * @Route("/{id}", name="admin_quote_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Quote $quote)
    {
        $form = $this->createDeleteForm($quote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($quote);
            $em->flush($quote);
        }

        return $this->redirectToRoute('admin_quote_index');
    }

    /**
     * Creates a form to delete a quote entity.
     *
     * @param Quote $quote The quote entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Quote $quote)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_quote_delete', array('id' => $quote->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

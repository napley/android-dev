<?php

namespace AppBundle\Controller;

use AppBundle\Entity\MotCle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Motcle controller.
 *
 * @Route("admin/motcle")
 */
class MotCleController extends Controller
{
    /**
     * Lists all motCle entities.
     *
     * @Route("/", name="admin_motcle_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $motCles = $em->getRepository('AppBundle:MotCle')->findAll();

        return $this->render('admin/motcle/index.html.twig', array(
            'motCles' => $motCles,
        ));
    }

    /**
     * Creates a new motCle entity.
     *
     * @Route("/new", name="admin_motcle_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $motCle = new Motcle();
        $form = $this->createForm('AppBundle\Form\MotCleType', $motCle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($motCle);
            $em->flush($motCle);

            return $this->redirectToRoute('admin_motcle_show', array('id' => $motCle->getId()));
        }

        return $this->render('admin/motcle/new.html.twig', array(
            'motCle' => $motCle,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a motCle entity.
     *
     * @Route("/{id}", name="admin_motcle_show")
     * @Method("GET")
     */
    public function showAction(MotCle $motCle)
    {
        $deleteForm = $this->createDeleteForm($motCle);

        return $this->render('admin/motcle/show.html.twig', array(
            'motCle' => $motCle,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing motCle entity.
     *
     * @Route("/{id}/edit", name="admin_motcle_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, MotCle $motCle)
    {
        $deleteForm = $this->createDeleteForm($motCle);
        $editForm = $this->createForm('AppBundle\Form\MotCleType', $motCle);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_motcle_edit', array('id' => $motCle->getId()));
        }

        return $this->render('admin/motcle/edit.html.twig', array(
            'motCle' => $motCle,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a motCle entity.
     *
     * @Route("/{id}", name="admin_motcle_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, MotCle $motCle)
    {
        $form = $this->createDeleteForm($motCle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($motCle);
            $em->flush($motCle);
        }

        return $this->redirectToRoute('admin_motcle_index');
    }

    /**
     * Creates a form to delete a motCle entity.
     *
     * @param MotCle $motCle The motCle entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(MotCle $motCle)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_motcle_delete', array('id' => $motCle->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    
    /**
     * Retourne les mots clés
     *
     * @Route("/list", name="admin_mot_cle_list")
     * @Method({"GET", "POST"})
     */
    public function listAction(Request $request)
    {
        $param = $request->request->get('q');
        
        $em = $this->getDoctrine()->getManager();
        $motCles = $em->getRepository('AppBundle:MotCle')->findAllByMotCle($param);
        
        $listeMotCle = [];
        foreach ($motCles as $key=>$motCle) {
            $listeMotCle[$key]['id'] = $motCle->getNom();
            $listeMotCle[$key]['label'] = $motCle->getNom();
            $listeMotCle[$key]['value'] = $motCle->getNom();
        }
        
        $response = new JsonResponse();
        $response->setData($listeMotCle);

        return $response;
        
    }
}

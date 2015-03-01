<?php

namespace Factu\AppBundle\Controller;

use Factu\AppBundle\Entity\Service;
use Factu\AppBundle\Form\ServiceType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ServiceController extends Controller
{

    public function indexAction()
    {
	    $listServices = $this->getDoctrine()
	      ->getManager()
	      ->getRepository('FactuAppBundle:Service')
	      ->findAll()
	    ;

    	return $this->render('FactuAppBundle:Service:index.html.twig', array(
	      'listServices' => $listServices
	    ));
    }

    public function viewAction($id)
    {
	    $service = $this->getDoctrine()
	      ->getManager()
	      ->getRepository('FactuAppBundle:Service')
	      ->find($id);

    	return $this->render('FactuAppBundle:Service:view.html.twig', array(
	      'service' => $service
	    ));
    }

	/**
	* @Security("has_role('ROLE_ADMIN')")
	*/
    public function addAction(Request $request)
    {
    	$service = new Service();

		$form = $this->get('form.factory')->create(new ServiceType, $service);

	    if ($form->handleRequest($request)->isValid()) {
	    	$service->setUpDate(new \Datetime());

	    	$em = $this->getDoctrine()->getManager();
		    $em->persist($service);
		    $em->flush();

		    $request->getSession()->getFlashBag()->add('notice', 'Service bien enregistré.');

		    return $this->redirect($this->generateUrl('service_view', array('id' => $service->getId())));
	    }

	    return $this->render('FactuAppBundle:Service:add.html.twig', array(
	      'form' => $form->createView(),
	    ));
    }

	/**
	* @Security("has_role('ROLE_ADMIN')")
	*/
	public function editAction($id, Request $request)
	{
	    $em = $this->getDoctrine()->getManager();
	    $service = $em->getRepository('FactuAppBundle:Service')->find($id);

	    // Si le service n'existe pas, on affiche une erreur 404
	    if ($service == null) {
	      throw $this->createNotFoundException("Le service d'id " . $id . " n'existe pas.");
	    }

		$form = $this->get('form.factory')->create(new ServiceType, $service);

		if ($form->handleRequest($request)->isValid()) {
	    	$service->setUpDate(new \Datetime());

	    	$em = $this->getDoctrine()->getManager();
		    $em->persist($service);
		    $em->flush();

		    $request->getSession()->getFlashBag()->add('notice', 'Service bien enregistré.');

		    return $this->redirect($this->generateUrl('service_view', array('id' => $service->getId())));
	    }

	    return $this->render('FactuAppBundle:Service:edit.html.twig', array(
	      'form' => $form->createView(),
	    ));
	 }

	/**
	* @Security("has_role('ROLE_ADMIN')")
	*/
	public function deleteAction($id, Request $request)
	{
		$em = $this->getDoctrine()->getManager();

		$service = $em->getRepository('FactuAppBundle:Service')->find($id);

		if (null === $service) {
		  throw new NotFoundHttpException("Le service d'id " . $id . " n'existe pas.");
		}

		$form = $this->createFormBuilder()->getForm();
		if ($form->handleRequest($request)->isValid()) {
		  $em->remove($service);
		  $em->flush();

		  $request->getSession()->getFlashBag()->add('info', "Le service a bien été supprimée.");

		  return $this->redirect($this->generateUrl('service_home'));
		}

		return $this->render('FactuAppBundle:Service:delete.html.twig', array(
		  'service' => $service,
		  'form'   => $form->createView()
		));
	}
}
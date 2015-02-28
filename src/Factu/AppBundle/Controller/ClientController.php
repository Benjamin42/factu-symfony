<?php

namespace Factu\AppBundle\Controller;

use Factu\AppBundle\Entity\Client;
use Factu\AppBundle\Form\ClientType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ClientController extends Controller
{

    public function indexAction()
    {
	    $listClients = $this->getDoctrine()
	      ->getManager()
	      ->getRepository('FactuAppBundle:Client')
	      ->findAll()
	    ;

    	return $this->render('FactuAppBundle:Client:index.html.twig', array(
	      'listClients' => $listClients
	    ));
    }

    public function viewAction($id)
    {
	    $client = $this->getDoctrine()
	      ->getManager()
	      ->getRepository('FactuAppBundle:Client')
	      ->find($id)
	    ;

    	return $this->render('FactuAppBundle:Client:view.html.twig', array(
	      'client' => $client
	    ));
    }

    public function addAction(Request $request)
    {
    	$client = new Client();
    	$france = $this->getDoctrine()
	      ->getManager()
	      ->getRepository('FactuAppBundle:Country')
	      ->findOneByCode('FR');
	    if ($france !== null) {
    		$client->setPays($france);
		}

    	// Init du numéro de client en prenant le max + 1
		$numClient = $this->getDoctrine()
	      ->getManager()
	      ->getRepository('FactuAppBundle:Client')
	      ->getNextNumClient();
	    $client->setNumClient($numClient);

		$form = $this->get('form.factory')->create(new ClientType, $client);

	    if ($form->handleRequest($request)->isValid()) {
	    	$em = $this->getDoctrine()->getManager();
		    $em->persist($client);
		    $em->flush();

		    $request->getSession()->getFlashBag()->add('notice', 'Client bien enregistré.');

		    //return $this->redirect($this->generateUrl('oc_platform_view', array('id' => $client->getId())));
			return $this->redirect($this->generateUrl('client_home'));
	    }

	    return $this->render('FactuAppBundle:Client:add.html.twig', array(
	      'form' => $form->createView(),
	    ));
    }

	public function editAction($id, Request $request)
	{
	    $em = $this->getDoctrine()->getManager();
	    $client = $em->getRepository('FactuAppBundle:Client')->find($id);

	    // Si le client n'existe pas, on affiche une erreur 404
	    if ($client == null) {
	      throw $this->createNotFoundException("Le client d'id " . $id . " n'existe pas.");
	    }

		$form = $this->get('form.factory')->create(new ClientType, $client);

		if ($form->handleRequest($request)->isValid()) {
	    	$em = $this->getDoctrine()->getManager();
		    $em->persist($client);
		    $em->flush();

		    $request->getSession()->getFlashBag()->add('notice', 'Client bien enregistré.');

		    return $this->redirect($this->generateUrl('client_view', array('id' => $client->getId())));
	    }

	    return $this->render('FactuAppBundle:Client:edit.html.twig', array(
	      'form' => $form->createView(), 'client' => $client
	    ));
	}

	public function cleanAction($id, Request $request)
	{
	    $em = $this->getDoctrine()->getManager();
	    $client = $em->getRepository('FactuAppBundle:Client')->find($id);

		$cleanService = $this->container->get('factu_app.clean_client_service');
		$cleanService->cleanClient($client);

	    // Si le client n'existe pas, on affiche une erreur 404
	    if ($client == null) {
	      throw $this->createNotFoundException("Le client d'id " . $id . " n'existe pas.");
	    }

		$form = $this->get('form.factory')->create(new ClientType, $client);

		if ($form->handleRequest($request)->isValid()) {
	    	$em = $this->getDoctrine()->getManager();
		    $em->persist($client);
		    $em->flush();

		    $request->getSession()->getFlashBag()->add('notice', 'Client bien enregistré.');

		    return $this->redirect($this->generateUrl('client_view', array('id' => $client->getId())));
	    }

	    return $this->render('FactuAppBundle:Client:edit.html.twig', array(
	      'form' => $form->createView(), 'client' => $client
	    ));
	}

	public function deleteAction($id, Request $request)
	{
		$em = $this->getDoctrine()->getManager();

		$client = $em->getRepository('FactuAppBundle:Client')->find($id);

		if (null === $client) {
		  throw new NotFoundHttpException("Le client d'id " . $id . " n'existe pas.");
		}

		// On crée un formulaire vide, qui ne contiendra que le champ CSRF
		// Cela permet de protéger la suppression d'annonce contre cette faille
		$form = $this->createFormBuilder()->getForm();

		if ($form->handleRequest($request)->isValid()) {
		  $em->remove($client);
		  $em->flush();

		  $request->getSession()->getFlashBag()->add('info', "Le client a bien été supprimée.");

		  return $this->redirect($this->generateUrl('client_home'));
		}

		return $this->render('FactuAppBundle:Client:delete.html.twig', array(
		  'client' => $client,
		  'form'   => $form->createView()
		));
	}
}
<?php

namespace Factu\AppBundle\Controller;

use Factu\AppBundle\Entity\Commande;
use Factu\AppBundle\Form\CommandeType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class CommandeController extends Controller
{

    public function indexAction()
    {
	    $listCommandes = $this->getDoctrine()
	      ->getManager()
	      ->getRepository('FactuAppBundle:Commande')
	      ->findAll();

    	return $this->render('FactuAppBundle:Commande:index.html.twig', array(
	      'listCommandes' => $listCommandes
	    ));
    }

    public function viewAction($id)
    {
	    $commande = $this->getDoctrine()
	      ->getManager()
	      ->getRepository('FactuAppBundle:Commande')
	      ->find($id)
	    ;

    	return $this->render('FactuAppBundle:Commande:view.html.twig', array(
	      'commande' => $commande
	    ));
    }

    public function viewFactuAction($id)
    {
	    $commande = $this->getDoctrine()
	      ->getManager()
	      ->getRepository('FactuAppBundle:Commande')
	      ->find($id)
	    ;

	    $parameterRepo = $this->getDoctrine()
	      ->getManager()
	      ->getRepository('FactuAppBundle:Parameter');

	    $priceRepo = $this->getDoctrine()
	      ->getManager()
	      ->getRepository('FactuAppBundle:Price');

    	return $this->render('FactuAppBundle:Commande:view_factu.html.twig', array(
	      'commande' => $commande, 'parameterRepo' => $parameterRepo, 'priceRepo' => $priceRepo
	    ));
    }

	/**
	* @Security("has_role('ROLE_ADMIN')")
	*/
    public function addAction($id, Request $request)
    {
    	$commande = new Commande();

    	$client = $this->getDoctrine()
	      ->getManager()
	      ->getRepository('FactuAppBundle:Client')
	      ->find($id);
    	if ($client != null) {
    		$commande->setClient($client);
    	}
		
    	// Init du numéro de factu en prenant le max + 1
		$numFactu = $this->getDoctrine()
	      ->getManager()
	      ->getRepository('FactuAppBundle:Commande')
	      ->getNextNumFactu();
	    $commande->setNumFactu($numFactu);
    	
		$form = $this->get('form.factory')->create(new CommandeType, $commande);

	    if ($form->handleRequest($request)->isValid()) {
	    	if ($commande->getDateDelivered() == null) {
	    		$commande->setIsDelivered(False);
	    	} else {
	    		$commande->setIsDelivered(True);
	    	}
	    	$em = $this->getDoctrine()->getManager();
		    $em->persist($commande);
		    $em->flush();

		    $request->getSession()->getFlashBag()->add('notice', 'Commande bien enregistrée.');

		    // On met a jour le badge compteur de nombre de commande à livrer
		    $nbCmdToDeliver = $this->getDoctrine()
		      ->getManager()
		      ->getRepository('FactuAppBundle:Commande')
		      ->getNbCommandeToDelivery();
		    $request->getSession()->set('nbCmdToDeliver', $nbCmdToDeliver);

			    return $this->redirect($this->generateUrl('commande_view', array('id' => $commande->getId())));
		    }

	    return $this->render('FactuAppBundle:Commande:add.html.twig', array(
	      'form' => $form->createView(),
	    ));
    }

	/**
	* @Security("has_role('ROLE_ADMIN')")
	*/
	public function editAction($id, Request $request)
	{
	    $em = $this->getDoctrine()->getManager();
	    $commande = $em->getRepository('FactuAppBundle:Commande')->find($id);

	    // Si la commande n'existe pas, on affiche une erreur 404
	    if ($commande == null) {
	      throw $this->createNotFoundException("La commande d'id " . $id . " n'existe pas.");
	    }

		$form = $this->get('form.factory')->create(new CommandeType, $commande);

		if ($form->handleRequest($request)->isValid()) {
			if ($commande->getDateDelivered() == null) {
	    		$commande->setIsDelivered(False);
	    	} else {
	    		$commande->setIsDelivered(True);
	    	}
	    	$em = $this->getDoctrine()->getManager();
		    $em->persist($commande);
		    $em->flush();

		    $request->getSession()->getFlashBag()->add('notice', 'Commande bien enregistré.');

		    // On met a jour le badge compteur de nombre de commande à livrer
		    $nbCmdToDeliver = $this->getDoctrine()
		      ->getManager()
		      ->getRepository('FactuAppBundle:Commande')
		      ->getNbCommandeToDelivery();
		    $request->getSession()->set('nbCmdToDeliver', $nbCmdToDeliver);

		    return $this->redirect($this->generateUrl('commande_view', array('id' => $commande->getId())));
	    }

	    return $this->render('FactuAppBundle:Commande:edit.html.twig', array(
	      'form' => $form->createView(), 'commande' => $commande
	    ));
	}

	/**
	* @Security("has_role('ROLE_ADMIN')")
	*/
	public function deleteAction($id, Request $request)
	{
		$em = $this->getDoctrine()->getManager();

		$commande = $em->getRepository('FactuAppBundle:Commande')->find($id);

		if (null === $commande) {
		  throw new NotFoundHttpException("La commande d'id " . $id . " n'existe pas.");
		}

		// On crée un formulaire vide, qui ne contiendra que le champ CSRF
		// Cela permet de protéger la suppression d'annonce contre cette faille
		$form = $this->createFormBuilder()->getForm();

		if ($form->handleRequest($request)->isValid()) {
		  $em->remove($commande);
		  $em->flush();

		  $request->getSession()->getFlashBag()->add('info', "La commande a bien été supprimée.");

		  return $this->redirect($this->generateUrl('commande_home'));
		}

		return $this->render('FactuAppBundle:Commande:delete.html.twig', array(
		  'commande' => $commande,
		  'form'   => $form->createView()
		));
	}
}
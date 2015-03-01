<?php

namespace Factu\AppBundle\Controller;

use Factu\AppBundle\Entity\Bdl;
use Factu\AppBundle\Form\BdlType;

use Factu\AppBundle\DTO\BdlCommandeDto;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class BdlController extends Controller
{

    public function indexAction()
    {
	    $listBdls = $this->getDoctrine()
	      ->getManager()
	      ->getRepository('FactuAppBundle:Bdl')
	      ->findAll();

    	return $this->render('FactuAppBundle:Bdl:index.html.twig', array(
	      'listBdls' => $listBdls
	    ));
    }

    public function viewAction($id)
    {
	    $bdl = $this->getDoctrine()
	      ->getManager()
	      ->getRepository('FactuAppBundle:Bdl')
	      ->find($id);


    	return $this->render('FactuAppBundle:Bdl:view.html.twig', array(
	      'bdl' => $bdl
	    ));
    }

    public function viewFactuAction($id)
    {
	    $bdl = $this->getDoctrine()
	      ->getManager()
	      ->getRepository('FactuAppBundle:Bdl')
	      ->find($id)
	    ;

	    $parameterRepo = $this->getDoctrine()
	      ->getManager()
	      ->getRepository('FactuAppBundle:Parameter');

	    $priceRepo = $this->getDoctrine()
	      ->getManager()
	      ->getRepository('FactuAppBundle:Price');

    	return $this->render('FactuAppBundle:Bdl:view_factu.html.twig', array(
	      'bdl' => $bdl, 'parameterRepo' => $parameterRepo, 'priceRepo' => $priceRepo
	    ));
    }

    public function viewCmdAjaxAction(Request $request) {      
  		$id = $request->query->get('id');

	    $bdl = $this->getDoctrine()
	      ->getManager()
	      ->getRepository('FactuAppBundle:Bdl')
	      ->find($id);

	    $listBdlDto = array();
	    foreach ($bdl->getCommandeProducts() as $commandeProduct) {
	    	$qty = $commandeProduct->getQty();
	    	$product = $commandeProduct->getProduct();
	    	if ($qty != null && $qty > 0) {
	    		$dto = new BdlCommandeDto($qty, $product);
	    		$listBdlDto[$product->getId()] = $dto;
	    	}
	    }


	    foreach ($bdl->getCommandes() as $commande) {
	    	foreach ($commande->getCommandeProducts() as $commandeProduct) {
		    	$qty = $commandeProduct->getQty();
		    	$product = $commandeProduct->getProduct();
		    	if ($qty != null && $qty > 0) {
		    		$dto = $listBdlDto[$product->getId()];
		    		$dto->add($qty);
		    	}	
	    	}
	    }

    	return $this->render('FactuAppBundle:Bdl:view_cmd.html.twig', array(
	      'listBdlDto' => $listBdlDto
	    ));
    }

	/**
	* @Security("has_role('ROLE_ADMIN')")
	*/
    public function addAction($id, Request $request)
    {
    	$bdl = new Bdl();

    	$client = $this->getDoctrine()
	      ->getManager()
	      ->getRepository('FactuAppBundle:Client')
	      ->find($id);
    	if ($client != null) {
    		$bdl->setClient($client);
    	}
		
    	// Init du numéro de factu en prenant le max + 1
		$numBdl = $this->getDoctrine()
	      ->getManager()
	      ->getRepository('FactuAppBundle:Bdl')
	      ->getNextNumBdl();
	    $bdl->setNumBdl($numBdl);
    	
		$form = $this->get('form.factory')->create(new BdlType, $bdl);

	    if ($form->handleRequest($request)->isValid()) {
	    	$em = $this->getDoctrine()->getManager();
		    $em->persist($bdl);
		    $em->flush();

		    $request->getSession()->getFlashBag()->add('notice', 'Bon de livraison bien enregistré.');

		    return $this->redirect($this->generateUrl('bdl_view', array('id' => $bdl->getId())));
	    }

	    return $this->render('FactuAppBundle:Bdl:add.html.twig', array(
	      'form' => $form->createView(),
	    ));
    }

	/**
	* @Security("has_role('ROLE_ADMIN')")
	*/
	public function editAction($id, Request $request)
	{
	    $em = $this->getDoctrine()->getManager();
	    $bdl = $em->getRepository('FactuAppBundle:Bdl')->find($id);

	    // Si le bon de livraison n'existe pas, on affiche une erreur 404
	    if ($bdl == null) {
	      throw $this->createNotFoundException("Le bon de livraison d'id " . $id . " n'existe pas.");
	    }

		$form = $this->get('form.factory')->create(new BdlType, $bdl);

		if ($form->handleRequest($request)->isValid()) {
	    	$em = $this->getDoctrine()->getManager();
		    $em->persist($bdl);
		    $em->flush();

		    $request->getSession()->getFlashBag()->add('notice', 'Bon de livraison bien enregistré.');

		    return $this->redirect($this->generateUrl('bdl_view', array('id' => $bdl->getId())));
	    }

	    return $this->render('FactuAppBundle:Bdl:edit.html.twig', array(
	      'form' => $form->createView(), 'bdl' => $bdl
	    ));
	}

	/**
	* @Security("has_role('ROLE_ADMIN')")
	*/
	public function deleteAction($id, Request $request)
	{
		$em = $this->getDoctrine()->getManager();

		$bdl = $em->getRepository('FactuAppBundle:Bdl')->find($id);

		if (null === $bdl) {
		  throw new NotFoundHttpException("Le bon de livraison d'id " . $id . " n'existe pas.");
		}

		$listCommande = $em->getRepository('FactuAppBundle:Commande')->getCommandeWithBdlId($id);
		if ($listCommande != null && count($listCommande) > 0) {
		  throw new NotFoundHttpException("Le bon de livraison est référencé par des commandes. Suppression impossible.");
		}

		$form = $this->createFormBuilder()->getForm();

		if ($form->handleRequest($request)->isValid()) {
		  $em->remove($bdl);
		  $em->flush();

		  $request->getSession()->getFlashBag()->add('info', "Le bon de livraison a bien été supprimée.");

		  return $this->redirect($this->generateUrl('bdl_home'));
		}

		return $this->render('FactuAppBundle:Bdl:delete.html.twig', array(
		  'bdl' => $bdl,
		  'form'   => $form->createView()
		));
	}
}
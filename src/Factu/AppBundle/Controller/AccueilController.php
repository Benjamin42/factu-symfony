<?php

namespace Factu\AppBundle\Controller;

use Factu\AppBundle\Entity\Bdl;
use Factu\AppBundle\Form\BdlType;

use Factu\AppBundle\DTO\BdlCommandeDto;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;

class AccueilController extends Controller
{

    public function indexAction(Request $request)
    {
	    $nbCmdToDeliver = $this->getDoctrine()
	      ->getManager()
	      ->getRepository('FactuAppBundle:Commande')
	      ->getNbCommandeToDelivery();
	    $request->getSession()->set('nbCmdToDeliver', $nbCmdToDeliver);

	    $listClient = $this->getDoctrine()
	      ->getManager()
	      ->getRepository('FactuAppBundle:Client')
	      ->getLastAdded();

	    $listBdl = $this->getDoctrine()
	      ->getManager()
	      ->getRepository('FactuAppBundle:Bdl')
	      ->getLastAdded();

	    $listCommande = $this->getDoctrine()
	      ->getManager()
	      ->getRepository('FactuAppBundle:Commande')
	      ->getLastAdded();

    	return $this->render('FactuAppBundle:Accueil:index.html.twig', array(
    		"listClient" => $listClient,
    		"listBdl" => $listBdl,
    		"listCommande" => $listCommande,

	    ));
    }
}
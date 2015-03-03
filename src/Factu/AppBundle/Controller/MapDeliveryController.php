<?php

namespace Factu\AppBundle\Controller;

use Factu\AppBundle\Entity\Client;
use Factu\AppBundle\Form\ClientType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MapDeliveryController extends Controller
{

    public function indexAction()
    {
	    $listCommandes = $this->getDoctrine()
	      ->getManager()
	      ->getRepository('FactuAppBundle:Commande')
	      ->getCommandeToDelivery();

	    $listBdls = $this->getDoctrine()
	      ->getManager()
	      ->getRepository('FactuAppBundle:Bdl')
	      ->getBdlToDelivery();

	    $parameterRepo = $this->getDoctrine()
	      ->getManager()
	      ->getRepository('FactuAppBundle:Parameter');

    	return $this->render('FactuAppBundle:MapDelivery:index.html.twig', array(
	      'listCommandes' => $listCommandes, 
	      'listBdls' => $listBdls,
	      'parameterRepo' => $parameterRepo
	    ));
    }

}
<?php

namespace Factu\AppBundle\Controller;

use Factu\AppBundle\Entity\Type;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TypeController extends Controller
{

    public function indexAction()
    {
	    $listTypes = $this->getDoctrine()
	      ->getManager()
	      ->getRepository('FactuAppBundle:Type')
	      ->findAll()
	    ;

    	return $this->render('FactuAppBundle:Type:index.html.twig', array(
	      'listTypes' => $listTypes
	    ));
    }

}
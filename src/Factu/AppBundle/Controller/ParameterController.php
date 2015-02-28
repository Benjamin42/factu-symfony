<?php

namespace Factu\AppBundle\Controller;

use Factu\AppBundle\Entity\Parameter;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ParameterController extends Controller
{

    public function indexAction()
    {
	    $listParameters = $this->getDoctrine()
	      ->getManager()
	      ->getRepository('FactuAppBundle:Parameter')
	      ->findAll()
	    ;

    	return $this->render('FactuAppBundle:Parameter:index.html.twig', array(
	      'listParameters' => $listParameters
	    ));
    }

}
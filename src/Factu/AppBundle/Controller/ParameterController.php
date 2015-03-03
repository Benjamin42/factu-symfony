<?php

namespace Factu\AppBundle\Controller;

use Factu\AppBundle\Entity\Parameter;
use Factu\AppBundle\Form\ParameterType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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

	/**
	* @Security("has_role('ROLE_ADMIN')")
	*/
	public function editAction($id, Request $request)
	{
	    $em = $this->getDoctrine()->getManager();
	    $parameter = $em->getRepository('FactuAppBundle:Parameter')->find($id);

	    // Si le parameter n'existe pas, on affiche une erreur 404
	    if ($parameter == null) {
	      throw $this->createNotFoundException("Le parametre d'id " . $id . " n'existe pas.");
	    }

		$form = $this->get('form.factory')->create(new ParameterType, $parameter);

		if ($form->handleRequest($request)->isValid()) {
	    	$em = $this->getDoctrine()->getManager();
		    $em->persist($parameter);
		    $em->flush();

		    $request->getSession()->getFlashBag()->add('notice', 'Parametre bien enregistré.');

		    return $this->redirect($this->generateUrl('parameter_home'));
	    }

	    return $this->render('FactuAppBundle:Parameter:edit.html.twig', array(
	      'form' => $form->createView(), 'parameter' => $parameter
	    ));
	}
}
<?php

namespace Factu\AppBundle\Controller;

use Factu\AppBundle\Entity\ProductCategory;
use Factu\AppBundle\Form\ProductCategoryType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class CategoryController extends Controller
{

    public function viewAction($id)
    {
	    $category = $this->getDoctrine()
	      ->getManager()
	      ->getRepository('FactuAppBundle:ProductCategory')
	      ->find($id);

    	return $this->render('FactuAppBundle:Category:view.html.twig', array(
	      'category' => $category
	    ));
    }

	/**
	* @Security("has_role('ROLE_ADMIN')")
	*/
    public function addAction(Request $request)
    {
    	$category = new ProductCategory();

		$form = $this->get('form.factory')->create(new ProductCategoryType, $category);

	    if ($form->handleRequest($request)->isValid()) {
	    	$em = $this->getDoctrine()->getManager();
		    $em->persist($category);
		    $em->flush();

		    $request->getSession()->getFlashBag()->add('notice', 'Catégorie bien enregistrée.');

		    return $this->redirect($this->generateUrl('product_home'));
	    }

	    return $this->render('FactuAppBundle:Category:add.html.twig', array(
	      'form' => $form->createView(),
	    ));
    }

	/**
	* @Security("has_role('ROLE_ADMIN')")
	*/
	public function editAction($id, Request $request)
	{
	    $em = $this->getDoctrine()->getManager();
	    $category = $em->getRepository('FactuAppBundle:ProductCategory')->find($id);

	    if ($category == null) {
	      throw $this->createNotFoundException("La catégorie d'id " . $id . " n'existe pas.");
	    }

		$form = $this->get('form.factory')->create(new ProductCategoryType, $category);

		if ($form->handleRequest($request)->isValid()) {
	    	$em = $this->getDoctrine()->getManager();
		    $em->persist($category);
		    $em->flush();

		    $request->getSession()->getFlashBag()->add('notice', 'Catégorie bien enregistrée.');

		    return $this->redirect($this->generateUrl('category_view', array('id' => $category->getId())));
	    }

	    return $this->render('FactuAppBundle:Category:edit.html.twig', array(
	      'form' => $form->createView(), 'category' => $category
	    ));
	 }

	/**
	* @Security("has_role('ROLE_ADMIN')")
	*/
	public function deleteAction($id, Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$category = $em->getRepository('FactuAppBundle:ProductCategory')->find($id);

		if (null === $category) {
		  throw new NotFoundHttpException("La catégorie d'id " . $id . " n'existe pas.");
		}

		$form = $this->createFormBuilder()->getForm();
		if ($form->handleRequest($request)->isValid()) {
		  $em->remove($category);
		  $em->flush();

		  $request->getSession()->getFlashBag()->add('info', "La catégorie a bien été supprimée.");

		  return $this->redirect($this->generateUrl('product_home'));
		}

		return $this->render('FactuAppBundle:Category:delete.html.twig', array(
		  'category' => $category,
		  'form'   => $form->createView()
		));
	}
}
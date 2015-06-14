<?php

namespace Factu\AppBundle\Controller;

use Factu\AppBundle\Entity\Product;
use Factu\AppBundle\Form\ProductType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ProductController extends Controller
{

    public function indexAction()
    {
	    $listProducts = $this->getDoctrine()
	      ->getManager()
	      ->getRepository('FactuAppBundle:Product')
	      ->findAll()
	    ;

	    $listPrices = array();

	    foreach ($listProducts as $product) {
	    	$savePrice = $this->getDoctrine()
		      ->getManager()
		      ->getRepository('FactuAppBundle:Price')
		      ->findMaxPriceJoinedToProduct($product->getId());
	    
		    if ($savePrice !== null) {
		       	$listPrices[$product->getId()] = $savePrice;
		    }
	    }

	    $listCategories = $this->getDoctrine()
	      ->getManager()
	      ->getRepository('FactuAppBundle:ProductCategory')
	      ->findAll()
	    ;

    	return $this->render('FactuAppBundle:Product:index.html.twig', array(
	      'listProducts' => $listProducts, 'listPrices' => $listPrices, 'listCategories' => $listCategories
	    ));
    }

    public function viewAction($id)
    {
	    $product = $this->getDoctrine()
	      ->getManager()
	      ->getRepository('FactuAppBundle:Product')
	      ->find($id);

	    $listPrices = $this->getDoctrine()
	      ->getManager()
	      ->getRepository('FactuAppBundle:Price')
	      ->findOrderedPricesJoinedToProduct($product->getId());

    	return $this->render('FactuAppBundle:Product:view.html.twig', array(
	      'product' => $product, 'listPrices' => $listPrices, 'listPricesInvert' => array_reverse($listPrices)
	    ));
    }

	/**
	* @Security("has_role('ROLE_ADMIN')")
	*/
    public function addAction(Request $request)
    {
    	$product = new Product();

		$form = $this->get('form.factory')->create(new ProductType, $product);

	    if ($form->handleRequest($request)->isValid()) {
	    	$product->setUpDate(new \Datetime());

	    	$em = $this->getDoctrine()->getManager();
		    $em->persist($product);
		    $em->flush();

		    $request->getSession()->getFlashBag()->add('notice', 'Produit bien enregistré.');

		    return $this->redirect($this->generateUrl('product_view', array('id' => $product->getId())));
	    }

	    return $this->render('FactuAppBundle:Product:add.html.twig', array(
	      'form' => $form->createView(),
	    ));
    }

	/**
	* @Security("has_role('ROLE_ADMIN')")
	*/
	public function editAction($id, Request $request)
	{
	    $em = $this->getDoctrine()->getManager();
	    $product = $em->getRepository('FactuAppBundle:Product')->find($id);

	    if ($product == null) {
	      throw $this->createNotFoundException("Le produit d'id " . $id . " n'existe pas.");
	    }

		$form = $this->get('form.factory')->create(new ProductType, $product);

		if ($form->handleRequest($request)->isValid()) {
	    	$product->setUpDate(new \Datetime());

	    	$em = $this->getDoctrine()->getManager();
		    $em->persist($product);
		    $em->flush();

		    $request->getSession()->getFlashBag()->add('notice', 'Produit bien enregistré.');

		    return $this->redirect($this->generateUrl('product_view', array('id' => $product->getId())));
	    }

	    return $this->render('FactuAppBundle:Product:edit.html.twig', array(
	      'form' => $form->createView(), 'product' => $product
	    ));
	 }

	/**
	* @Security("has_role('ROLE_ADMIN')")
	*/
	public function deleteAction($id, Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$product = $em->getRepository('FactuAppBundle:Product')->find($id);

		if (null === $product) {
		  throw new NotFoundHttpException("Le produit d'id " . $id . " n'existe pas.");
		}

		$form = $this->createFormBuilder()->getForm();
		if ($form->handleRequest($request)->isValid()) {
		  $em->remove($product);
		  $em->flush();

		  $request->getSession()->getFlashBag()->add('info', "Le produit a bien été supprimée.");

		  return $this->redirect($this->generateUrl('product_home'));
		}

		return $this->render('FactuAppBundle:Product:delete.html.twig', array(
		  'product' => $product,
		  'form'   => $form->createView()
		));
	}
}
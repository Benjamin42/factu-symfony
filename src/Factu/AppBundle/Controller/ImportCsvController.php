<?php

namespace Factu\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ImportCsvController extends Controller
{

    public function importAction(Request $request)
    {
		$form = $this->get('form.factory')
		             ->createBuilder('form')
		             ->add('fileClient',		'file', array('required' => false))
		             ->add('fileCommande',		'file', array('required' => false))
		             ->add('import',		'submit')
		             ->getForm();
		   
	    if ($request->getMethod() == 'POST') {
			$form->bind($request);
			
			if ($form['fileClient']->getData() != null) {
				// Import client
				$fileName = date("Ymd") . "_client.csv";
				$form['fileClient']->getData()->move("/tmp/", $fileName);

				$csvService = $this->container->get('factu_app.csv_client_service');
				$csvService->import("/tmp/" . $fileName);

		    	$request->getSession()->getFlashBag()->add('notice', 'Fichier CSV Client chargé avec succès.');
			}

			if ($form['fileCommande']->getData() != null) {
				// Import commande
				$fileName = date("Ymd") . "_commande.csv";
				$form['fileCommande']->getData()->move("/tmp/", $fileName);

				$csvService = $this->container->get('factu_app.csv_commande_service');
				$csvService->import("/tmp/" . $fileName);

		    	$request->getSession()->getFlashBag()->add('notice', 'Fichier CSV Commande chargé avec succès.');
			}

			//$fileName = date("Ymd") . "_client.csv";
			//$form['fileClient']->getData()->move("/tmp/", $fileName);

			//$csvService = $this->container->get('factu_app.csv_client_service');
			//$csvService->import("/tmp/" . $fileName);


	      return $this->render('FactuAppBundle:ImportCsv:result.html.twig');
	    }  else {
	      return $this->render('FactuAppBundle:ImportCsv:index.html.twig', array(
	          'form' => $form->createView(),
	      )); 
	  	}
    }

	public function cleanAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();

		$form = $this->createFormBuilder()->getForm();

		if ($form->handleRequest($request)->isValid()) {

		    $listCommandes = $this->getDoctrine()
		      ->getManager()
		      ->getRepository('FactuAppBundle:Commande')
		      ->findAll();

		    foreach ($listCommandes as $commande) {
		    	$em->remove($commande);
		    }
		    
			// TODO : clean Bon de livraison

		    /*$listProducts = $this->getDoctrine()
		      ->getManager()
		      ->getRepository('FactuAppBundle:Product')
		      ->findAll();

		    foreach ($listProducts as $product) {
		    	$em->remove($product);
		    }*/

		    $em->flush();

		  //$em->remove($client);
		  //$em->flush();

		  $request->getSession()->getFlashBag()->add('notice', "La base de données a été vidée.");

	      return $this->render('FactuAppBundle:ImportCsv:result.html.twig');
		}

		return $this->render('FactuAppBundle:ImportCsv:clean.html.twig', array(
		  'form'   => $form->createView()
		));
	}
}
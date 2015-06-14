<?php

namespace Factu\AppBundle\Controller;

use Factu\AppBundle\DTO\CommandeDto;

use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StatistiqueController extends Controller
{

    public function endMonthAction(Request $request)
    {
	    $today = new \DateTime('tomorrow');
	    $dateMonth = $today;

		$defaultsValues = array(
		    'dateMonth' => $today->format('m/Y'),
		);

		$form = $this->get('form.factory')
		             ->createBuilder('form', $defaultsValues)
		             ->add('dateMonth',		'text', array('required' => false))
		             ->add('search', 		'submit')
		             ->getForm();
		
		if ($request->getMethod() == 'POST') {
			$form->bind($request);
			
			if ($form['dateMonth']->getData() != null) {
				$dateMonthStr = $form['dateMonth']->getData();

				$dateMonth = \DateTime::createFromFormat('m/Y', $dateMonthStr);
			} else {
				$dateMonth = null;
			}
		}


	    $listProducts = $this->getDoctrine()
	      ->getManager()
	      ->getRepository('FactuAppBundle:Product')
	      ->getFollowedStatProduct();

	    $listCategories = $this->getDoctrine()
	      ->getManager()
	      ->getRepository('FactuAppBundle:ProductCategory')
	      ->getFollowedStatCategories();

	    // Commandes
	    $listCommandes = null;
	    if ($dateMonth != null) {
		    $month = date_format($dateMonth, 'm');
		    $year = date_format($dateMonth, 'Y');

		    $listCommandes = $this->getDoctrine()
		      ->getManager()
		      ->getRepository('FactuAppBundle:Commande')
		      ->getCommandesByYearMonthDay($month, $year);
		} else {
		    $listCommandes = $this->getDoctrine()
		      ->getManager()
		      ->getRepository('FactuAppBundle:Commande')
		      ->findAll();
		}

	    $listCommandeDto = array();
	    $commandeTotalDto = new CommandeDto();
	    foreach ($listCommandes as $commande) {
	    	if ($commande->getBdl() != null) {
	    		continue;
	    	}
	    	$commandeDto = new CommandeDto();

	    	$commandeDto->id = $commande->getId();
	    	$commandeDto->numFactu = $commande->getNumFactu();
	    	$commandeDto->dateFactu = $commande->getDateFactu();

			foreach ($commande->getCommandeProducts() as $commandeProduct) {
				if ($commandeProduct->getQty() != null && $commandeProduct->getQty() > 0) {
					$commandeDto->addProduct($commandeProduct->getProduct(), $commandeProduct->getQty());
					$commandeTotalDto->addProduct($commandeProduct->getProduct(), $commandeProduct->getQty());
				}
				if ($commandeProduct->getQtyGift() != null && $commandeProduct->getQtyGift() > 0) {
					$commandeDto->addProduct($commandeProduct->getProduct(), $commandeProduct->getQtyGift());
					$commandeTotalDto->addProduct($commandeProduct->getProduct(), $commandeProduct->getQtyGift());
				}
			}

	    	$listCommandeDto[] = $commandeDto;
	    }


	    // Bon de livraison
	    $listBdls = null;
	    if ($dateMonth != null) {
		    $month = date_format($dateMonth, 'm');
		    $year = date_format($dateMonth, 'Y');

		    $listBdls = $this->getDoctrine()
		      ->getManager()
		      ->getRepository('FactuAppBundle:Bdl')
		      ->getBdlsByYearMonthDay($month, $year);
		} else {
		    $listBdls = $this->getDoctrine()
		      ->getManager()
		      ->getRepository('FactuAppBundle:Bdl')
		      ->findAll();
		}

	    foreach ($listBdls as $bdl) {
	    	$commandeDto = new CommandeDto();

	    	$commandeDto->id = $bdl->getId();
	    	$commandeDto->numBdl = $bdl->getNumBdl();
	    	$commandeDto->dateFactu = $bdl->getDateBdl();

			foreach ($bdl->getCommandeProducts() as $commandeProduct) {
				if ($commandeProduct->getQty() != null && $commandeProduct->getQty() > 0) {
					$commandeDto->addProduct($commandeProduct->getProduct(), $commandeProduct->getQty());
					$commandeTotalDto->addProduct($commandeProduct->getProduct(), $commandeProduct->getQty());
				}
				if ($commandeProduct->getQtyGift() != null && $commandeProduct->getQtyGift() > 0) {
					$commandeDto->addProduct($commandeProduct->getProduct(), $commandeProduct->getQtyGift());
					$commandeTotalDto->addProduct($commandeProduct->getProduct(), $commandeProduct->getQtyGift());
				}
			}

	    	$listCommandeDto[] = $commandeDto;
	    }

    	return $this->render('FactuAppBundle:Statistique:end_month.html.twig', array(
	      'listProducts' => $listProducts, 
	      'listCategories' => $listCategories,
	      'listCommandes' => $listCommandeDto, 
	      'commandeTotalDto' => $commandeTotalDto, 
	      'form' => $form->createView()
	    ));
    }

    public function chartMonthAction(Request $request)
    {
	    $listProducts = $this->getDoctrine()
	      ->getManager()
	      ->getRepository('FactuAppBundle:Product')
	      ->findAll();

	    $listCommandes = $this->getDoctrine()
	      ->getManager()
	      ->getRepository('FactuAppBundle:Commande')
	      ->findAllOrderByDateFactuDesc();
		
		$listCommandeDto = new ArrayCollection();
		$currentCommandeDto = null;
		$currentMonthDate = "";
	    foreach ($listCommandes as $commande) {
	    	$monthDate = date_format($commande->getDateFactu(), 'Y-m');

	    	if ($currentCommandeDto == null || $currentMonthDate != $monthDate) {
	    		if ($currentCommandeDto != null) {
	    			$listCommandeDto[] = $currentCommandeDto;
				}
	    		$commandeDto = new CommandeDto();
				$commandeDto->dateFactu = $commande->getDateFactu();
				$currentCommandeDto = $commandeDto;
	    		$currentMonthDate = $monthDate;
	    	}

			foreach ($commande->getCommandeProducts() as $commandeProduct) {
				if ($commandeProduct->getQty() != null && $commandeProduct->getQty() > 0) {
					$commandeDto->addProduct($commandeProduct->getProduct(), $commandeProduct->getQty());
				}
			}

	    }
	    
		if ($currentCommandeDto != null) {
			$listCommandeDto[] = $currentCommandeDto;
		}

    	return $this->render('FactuAppBundle:Statistique:chart_month.html.twig', array('listProducts' => $listProducts, 'listCommandeDto' => $listCommandeDto
	    ));
    }

}
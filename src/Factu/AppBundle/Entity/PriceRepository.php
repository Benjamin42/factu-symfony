<?php

namespace Factu\AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * PriceRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PriceRepository extends EntityRepository
{
	public function findMaxPriceJoinedToProduct($idProduct)
	{
	    try {
	    	$listPrices = $this->findOrderedPricesJoinedToProduct($idProduct);
	    	
	        if ($listPrices !== null && count($listPrices) > 0) {
	        	return $listPrices[0];
	        } else {
	        	return null;
	        }
	    } catch (\Doctrine\ORM\NoResultException $e) {
	        return null;
	    }
	}

	public function findOrderedPricesJoinedToProduct($idProduct) {
	    $query = $this->getEntityManager()
	        ->createQuery('
	            SELECT price FROM FactuAppBundle:Price price
	            JOIN price.product product
	            WHERE product.id = :id
	            ORDER BY price.creaDate desc'
	        )->setParameter('id', $idProduct);

	    try {
	        return $query->getResult();
	    } catch (\Doctrine\ORM\NoResultException $e) {
	        return null;
	    }
	}

}

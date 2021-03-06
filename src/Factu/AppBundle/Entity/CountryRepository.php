<?php

namespace Factu\AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * CountryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CountryRepository extends EntityRepository
{

	public function getDefaultCountry() {
	    $query = $this->getEntityManager()
	        ->createQuery('
	            SELECT c FROM FactuAppBundle:Country c
	            WHERE c.code = (SELECT p.pValue FROM FactuAppBundle:Parameter p WHERE p.pName = "code_pays_defaut")'
	        );
	        
	    try {
	        return $query->getSingleResult();
	    } catch (\Doctrine\ORM\NoResultException $e) {
	        return null;
	    }
	}
}

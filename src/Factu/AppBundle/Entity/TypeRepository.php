<?php

namespace Factu\AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * TypeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TypeRepository extends EntityRepository
{

	public function findWithCodeAndGrp($code, $grp) {
	    $query = $this->getEntityManager()
	        ->createQuery('
	            SELECT t FROM FactuAppBundle:Type t
	            WHERE t.grp = :grp
	            	AND t.code = :code'
	        )->setParameters(array(
			    'grp' => $grp,
			    'code'  => $code
			));
	        
	    try {
	        return $query->getSingleResult();
	    } catch (\Doctrine\ORM\NoResultException $e) {
	        return null;
	    }
	}
}

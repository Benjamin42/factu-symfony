<?php

namespace Factu\AppBundle\DTO;

use Doctrine\Common\Collections\ArrayCollection;


class BdlCommandeDto
{
    public $product;

    public $qtyBefore;

	public $qty;

    public function __construct($qty, $product)
    {
    	$this->qtyBefore = $qty;
    	$this->product = $product;
    	$this->qty = $qty;
    }

	public function add($qty) {
		$this->qty -= $qty;
	}

}

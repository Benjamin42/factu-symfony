<?php

namespace Factu\AppBundle\DTO;

use Doctrine\Common\Collections\ArrayCollection;


class CommandeDto
{
    public $id;

    public $numFactu;

    public $numBdl;

    public $dateFactu;

	public $listProduct;

    public function __construct()
    {
        $this->listProduct = new ArrayCollection();
    }

	public function addProduct($idProduct, $qty) {
		if ($this->listProduct->containsKey($idProduct)) {
			$this->listProduct[$idProduct] += $qty;
		} else {
			$this->listProduct[$idProduct] = $qty;
		}
	}

}

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

	public $listCategory;

    public function __construct()
    {
        $this->listProduct = new ArrayCollection();
        $this->listCategory = new ArrayCollection();
    }

	public function addProduct($product, $qty) {
		$idProduct = $product->getId();

		if ($this->listProduct->containsKey($idProduct)) {
			$this->listProduct[$idProduct] += $qty;
		} else {
			$this->listProduct[$idProduct] = $qty;
		}

		if ($product->getCategory() != null) {
			$idCategory = $product->getCategory()->getId();

			if ($this->listCategory->containsKey($idCategory)) {
				$this->listCategory[$idCategory] += $qty;
			} else {
				$this->listCategory[$idCategory] = $qty;
			}
		}
	}

}

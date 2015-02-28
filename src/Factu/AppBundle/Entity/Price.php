<?php

namespace Factu\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Price
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Factu\AppBundle\Entity\PriceRepository")
 */
class Price
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="unit_price", type="float")
     */
    private $unitPrice;

    /**
     * @var float
     *
     * @ORM\Column(name="unit_price_liv", type="float")
     */
    private $unitPriceLiv;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="crea_date", type="datetime")
     */
    private $creaDate;

    /**
    * @ORM\ManyToOne(targetEntity="Factu\AppBundle\Entity\Product", inversedBy="prices")
    * @ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable=false)
    */
    protected $product;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set unitPrice
     *
     * @param float $unitPrice
     * @return Price
     */
    public function setUnitPrice($unitPrice)
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    /**
     * Get unitPrice
     *
     * @return float 
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    /**
     * Set unitPriceLiv
     *
     * @param float $unitPriceLiv
     * @return Price
     */
    public function setUnitPriceLiv($unitPriceLiv)
    {
        $this->unitPriceLiv = $unitPriceLiv;

        return $this;
    }

    /**
     * Get unitPriceLiv
     *
     * @return float 
     */
    public function getUnitPriceLiv()
    {
        return $this->unitPriceLiv;
    }

    /**
     * Set creaDate
     *
     * @param \DateTime $creaDate
     * @return Price
     */
    public function setCreaDate($creaDate)
    {
        $this->creaDate = $creaDate;

        return $this;
    }

    /**
     * Get creaDate
     *
     * @return \DateTime 
     */
    public function getCreaDate()
    {
        return $this->creaDate;
    }

    /**
     * Set product
     *
     * @param \Factu\AppBundle\Entity\Product $product
     * @return Price
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \Factu\AppBundle\Entity\Product 
     */
    public function getProduct()
    {
        return $this->product;
    }
}

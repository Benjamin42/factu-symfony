<?php

namespace Factu\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CommandeProduct
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Factu\AppBundle\Entity\CommandeProductRepository")
 */
class CommandeProduct
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
    * @ORM\ManyToOne(targetEntity="Factu\AppBundle\Entity\Commande", inversedBy="commandeProducts")
    * @ORM\JoinColumn(name="commande_id", referencedColumnName="id", nullable=true)
    */
    private $commande;

    /**
    * @ORM\ManyToOne(targetEntity="Factu\AppBundle\Entity\Bdl", inversedBy="commandeProducts")
    * @ORM\JoinColumn(name="bdl_id", referencedColumnName="id", nullable=true)
    */
    private $bdl;

    /**
    * @ORM\ManyToOne(targetEntity="Factu\AppBundle\Entity\Product")
    * @ORM\JoinColumn(nullable=false)
    */
    private $product;

    /**
     * @var integer
     *
     * @ORM\Column(name="qty", type="integer", nullable=true)
     */
    private $qty;

    /**
     * @var integer
     *
     * @ORM\Column(name="qty_gift", type="integer", nullable=true)
     */
    private $qtyGift;

    /**
     * @var float
     *
     * @ORM\Column(name="forced_price", type="float", nullable=true)
     */
    private $forcedPrice;

    public function __construct()
    {
        $this->qty = 0;
        $this->qtyGift = 0;
    }

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
     * Set qty
     *
     * @param integer $qty
     * @return CommandeProduit
     */
    public function setQty($qty)
    {
        $this->qty = $qty;

        return $this;
    }

    /**
     * Get qty
     *
     * @return integer 
     */
    public function getQty()
    {
        return $this->qty;
    }

    /**
     * Set qtyGift
     *
     * @param integer $qtyGift
     * @return CommandeProduit
     */
    public function setQtyGift($qtyGift)
    {
        $this->qtyGift = $qtyGift;

        return $this;
    }

    /**
     * Get qtyGift
     *
     * @return integer 
     */
    public function getQtyGift()
    {
        return $this->qtyGift;
    }

    /**
     * Set forcedPrice
     *
     * @param float $forcedPrice
     * @return CommandeProduit
     */
    public function setForcedPrice($forcedPrice)
    {
        $this->forcedPrice = $forcedPrice;

        return $this;
    }

    /**
     * Get forcedPrice
     *
     * @return float 
     */
    public function getForcedPrice()
    {
        return $this->forcedPrice;
    }

    /***************************************************************************
    *                               COMMANDE 
    ****************************************************************************/

    /**
     * Set commande
     *
     * @param \Factu\AppBundle\Entity\Commande $commande
     * @return CommandeProduct
     */
    public function setCommande($commande)
    {
        $this->commande = $commande;

        return $this;
    }

    /**
     * Get commande
     *
     * @return \Factu\AppBundle\Entity\Commande 
     */
    public function getCommande()
    {
        return $this->commande;
    }

    /***************************************************************************
    *                               BDL 
    ****************************************************************************/

    /**
     * Set bdl
     *
     * @param \Factu\AppBundle\Entity\Bdl $bdl
     * @return CommandeProduct
     */
    public function setBdl($bdl)
    {
        $this->bdl = $bdl;

        return $this;
    }

    /**
     * Get bdl
     *
     * @return \Factu\AppBundle\Entity\Bdl 
     */
    public function getBdl()
    {
        return $this->bdl;
    }

    /**
     * Set product
     *
     * @param \Factu\AppBundle\Entity\Product $product
     * @return CommandeProduct
     */
    public function setProduct(\Factu\AppBundle\Entity\Product $product)
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

    public function getLastPrice() {
        $date = null;
        
        if ($this->bdl != null) {
            $date = $this->bdl->getDateBdl();
        } else if ($this->commande != null) {
            if ($this->commande->getBdl() != null) {
                $date = $this->commande->getBdl()->getDateBdl();
            } else {
                $date = $this->commande->getDateFactu();
            }
        }

        $price = null;
        if ($this->forcedPrice != null) {
            $price = new Price();
            $price->setUnitPrice($this->forcedPrice);
            $price->setUnitPriceLiv($this->forcedPrice);   
        } else if ($this->commande->getBdl() != null) {
            foreach ($this->commande->getBdl()->getCommandeProducts() as $commandeProduct) {
                if ($commandeProduct->getProduct()->getId() == $this->product->getId() && $commandeProduct->getForcedPrice() != null) {
                    $price = new Price();
                    $price->setUnitPrice($commandeProduct->getForcedPrice());
                    $price->setUnitPriceLiv($commandeProduct->getForcedPrice()); 
                    break;
                }
            }
        }

        if ($price == null) {
            $price = $this->product->getLastPrice($date);
        }
        return $price;   
    }

    public function calcMontantTTC() {
        $price = $this->getLastPrice();

        $amt = 0;
        if ($price != null) {
            if ($this->commande != null) {
                if ($this->commande->getToDelivered() == True) {
                    $amt = $this->qty * $price->getUnitPriceLiv();
                } else {
                    $amt = $this->qty * $price->getUnitPrice();
                }
            } else if ($this->bdl != null) {
                if ($this->bdl->getToDelivered() == True) {
                    $amt = $this->qty * $price->getUnitPriceLiv();
                } else {
                    $amt = $this->qty * $price->getUnitPrice();
                }
            }

        }
        return $amt;
    }

}

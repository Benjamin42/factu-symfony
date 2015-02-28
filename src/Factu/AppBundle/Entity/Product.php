<?php

namespace Factu\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Product
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Factu\AppBundle\Entity\ProductRepository")
 */
class Product
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private $comment;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="crea_date", type="datetime")
     */
    private $creaDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="up_date", type="datetime")
     */
    private $upDate;

    /**
    * @ORM\OneToMany(targetEntity="Factu\AppBundle\Entity\Price", mappedBy="product", cascade={"persist", "remove"}, orphanRemoval=true)
    */
    private $prices;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_followed_stat", type="boolean")
     */
    private $isFollowedStat;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_col_csv", type="integer", nullable=true)
     */
    private $idColCsv;
    

    public function __construct()
    {
        $this->prices = new ArrayCollection();

        $this->creaDate = new \Datetime();
        $this->active = True;   
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
     * Set title
     *
     * @param string $title
     * @return Product
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set comment
     *
     * @param string $comment
     * @return Product
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Product
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set creaDate
     *
     * @param \DateTime $creaDate
     * @return Product
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
     * Set upDate
     *
     * @param \DateTime $upDate
     * @return Product
     */
    public function setUpDate($upDate)
    {
        $this->upDate = $upDate;

        return $this;
    }

    /**
     * Get upDate
     *
     * @return \DateTime 
     */
    public function getUpDate()
    {
        return $this->upDate;
    }

    /***************************************************************************
    *                               PRICES
    ****************************************************************************/

    /**
     * Add price
     *
     * @param \Factu\AppBundle\Entity\Price $price
     * @return Product
     */
    public function addPrice(\Factu\AppBundle\Entity\Price $price)
    {
        $this->prices[] = $price;
        $price->setProduct($this);
        return $this;
    }

    /**
     * Remove price
     *
     * @param \Factu\AppBundle\Entity\Price $price
     */
    public function removePrice(\Factu\AppBundle\Entity\Price $price)
    {
        $this->prices->removeElement($price);
        $price->setProduct(null);
    }

    public function getPrices()
    {
        return $this->prices;
    }

    public function getPriceDay() {
        return $this->getLastPrice(new \Datetime());
    }

    /**
    * 
    * Get price
    *
    * @return Price
    */
    public function getLastPrice($date) {
        $resultPrice = null;

        foreach ($this->prices as $price) {
            if ($price->getCreaDate() < $date && 
                ($resultPrice == null || $resultPrice->getCreaDate() < $price->getCreaDate())) {
                
                $resultPrice = $price;
            }
        }
        return $resultPrice;
    }

    /**
     * Set idColCsv
     *
     * @param integer $idColCsv
     * @return Product
     */
    public function setIdColCsv($idColCsv)
    {
        $this->idColCsv = $idColCsv;

        return $this;
    }

    /**
     * Get idColCsv
     *
     * @return integer 
     */
    public function getIdColCsv()
    {
        return $this->idColCsv;
    }

    /**
     * Set isFollowedStat
     *
     * @param boolean $isFollowedStat
     * @return Product
     */
    public function setIsFollowedStat($isFollowedStat)
    {
        $this->isFollowedStat = $isFollowedStat;

        return $this;
    }

    /**
     * Get isFollowedStat
     *
     * @return boolean 
     */
    public function getIsFollowedStat()
    {
        return $this->isFollowedStat;
    }
}

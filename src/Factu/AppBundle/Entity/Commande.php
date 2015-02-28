<?php

namespace Factu\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Commande
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Factu\AppBundle\Entity\CommandeRepository")
 */
class Commande
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
    * @ORM\ManyToOne(targetEntity="Factu\AppBundle\Entity\Bdl", inversedBy="commandes")
    * @ORM\JoinColumn(name="bdl_id", referencedColumnName="id", nullable=true)
    */
    private $bdl;

    /**
    * @ORM\ManyToOne(targetEntity="Factu\AppBundle\Entity\Client")
    * @ORM\JoinColumn(nullable=false)
    */
    private $client;

    /**
     * @var integer
     *
     * @ORM\Column(name="num_factu", type="integer")
     */
    private $numFactu;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_factu", type="datetime")
     */
    private $dateFactu;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_payed", type="boolean")
     */
    private $isPayed;

    /**
     * @var boolean
     *
     * @ORM\Column(name="to_delivered", type="boolean")
     */
    private $toDelivered;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_delivered", type="boolean")
     */
    private $isDelivered;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_payed", type="datetime", nullable=true)
     */
    private $datePayed;

    /**
    * @ORM\OneToMany(targetEntity="Factu\AppBundle\Entity\CommandeProduct", mappedBy="commande", cascade={"persist", "remove"}, orphanRemoval=true)
    */
    protected $commandeProducts;

    /**
    * @ORM\OneToMany(targetEntity="Factu\AppBundle\Entity\CommandeService", mappedBy="commande", cascade={"persist", "remove"}, orphanRemoval=true)
    */
    protected $commandeServices;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_delivered", type="datetime", nullable=true)
     */
    private $dateDelivered;

    public function __construct()
    {
        $this->commandeProducts = new ArrayCollection();
        $this->commandeServices = new ArrayCollection();
        
        $this->dateFactu = new \Datetime();
        $this->isPayed = False;
        $this->isDelivered = False;
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
     * Set numFactu
     *
     * @param integer $numFactu
     * @return Commande
     */
    public function setNumFactu($numFactu)
    {
        $this->numFactu = $numFactu;

        return $this;
    }

    /**
     * Get numFactu
     *
     * @return integer 
     */
    public function getNumFactu()
    {
        return $this->numFactu;
    }

    /**
     * Set dateFactu
     *
     * @param \DateTime $dateFactu
     * @return Commande
     */
    public function setDateFactu($dateFactu)
    {
        $this->dateFactu = $dateFactu;

        return $this;
    }

    /**
     * Get dateFactu
     *
     * @return \DateTime 
     */
    public function getDateFactu()
    {
        return $this->dateFactu;
    }

    /**
     * Set isPayed
     *
     * @param boolean $isPayed
     * @return Commande
     */
    public function setIsPayed($isPayed)
    {
        $this->isPayed = $isPayed;

        return $this;
    }

    /**
     * Get isPayed
     *
     * @return boolean 
     */
    public function getIsPayed()
    {
        return $this->isPayed;
    }

    /**
     * Set toDelivered
     *
     * @param boolean $toDelivered
     * @return Commande
     */
    public function setToDelivered($toDelivered)
    {
        $this->toDelivered = $toDelivered;

        return $this;
    }

    /**
     * Get toDelivered
     *
     * @return boolean 
     */
    public function getToDelivered()
    {
        return $this->toDelivered;
    }

    /**
     * Set isDelivered
     *
     * @param boolean $isDelivered
     * @return Commande
     */
    public function setIsDelivered($isDelivered)
    {
        $this->isDelivered = $isDelivered;

        return $this;
    }

    /**
     * Get isDelivered
     *
     * @return boolean 
     */
    public function getIsDelivered()
    {
        return $this->isDelivered;
    }

    /**
     * Set datePayed
     *
     * @param \DateTime $datePayed
     * @return Commande
     */
    public function setDatePayed($datePayed)
    {
        $this->datePayed = $datePayed;

        return $this;
    }

    /**
     * Get datePayed
     *
     * @return \DateTime 
     */
    public function getDatePayed()
    {
        return $this->datePayed;
    }

    /**
     * Set dateDelivered
     *
     * @param \DateTime $dateDelivered
     * @return Commande
     */
    public function setDateDelivered($dateDelivered)
    {
        $this->dateDelivered = $dateDelivered;

        return $this;
    }

    /**
     * Get dateDelivered
     *
     * @return \DateTime 
     */
    public function getDateDelivered()
    {
        return $this->dateDelivered;
    }

    /***************************************************************************
    *                               CLIENT
    ****************************************************************************/

    /**
     * Set client
     *
     * @param \Factu\AppBundle\Entity\Client $client
     * @return Commande
     */
    public function setClient(\Factu\AppBundle\Entity\Client $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \Factu\AppBundle\Entity\Client 
     */
    public function getClient()
    {
        return $this->client;
    }

    /***************************************************************************
    *                               BDL 
    ****************************************************************************/

    /**
     * Set bdl
     *
     * @param \Factu\AppBundle\Entity\Bdl $bdl
     * @return Commande
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

    /***************************************************************************
    *                               COMMANDE PRODUCT
    ****************************************************************************/

    /**
     * Add commandeProducts
     *
     * @param \Factu\AppBundle\Entity\CommandeProduct $commandeProducts
     * @return Commande
     */
    public function addCommandeProduct(\Factu\AppBundle\Entity\CommandeProduct $commandeProducts)
    {
        $this->commandeProducts[] = $commandeProducts;
        $commandeProducts->setCommande($this);
        return $this;
    }

    /**
     * Remove commandeProducts
     *
     * @param \Factu\AppBundle\Entity\CommandeProduct $commandeProducts
     */
    public function removeCommandeProduct(\Factu\AppBundle\Entity\CommandeProduct $commandeProduct)
    {
        $this->commandeProducts->removeElement($commandeProduct);
        $commandeProduct->setCommande(null);
    }

    /**
     * Get commandeProducts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCommandeProducts()
    {
        return $this->commandeProducts;
    }

    /***************************************************************************
    *                               COMMANDE SERVICE
    ****************************************************************************/

    /**
     * Add commandeServices
     *
     * @param \Factu\AppBundle\Entity\CommandeService $commandeService
     * @return Commande
     */
    public function addCommandeService(\Factu\AppBundle\Entity\CommandeService $commandeService)
    {
        $this->commandeServices[] = $commandeService;
        $commandeService->setCommande($this);
        return $this;
    }

    /**
     * Remove commandeService
     *
     * @param \Factu\AppBundle\Entity\CommandeService $commandeService
     */
    public function removeCommandeService(\Factu\AppBundle\Entity\CommandeService $commandeService)
    {
        $this->commandeServices->removeElement($commandeService);
        $commandeService->setCommande(null);
    }

    /**
     * Get commandeServices
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCommandeServices()
    {
        return $this->commandeServices;
    }

    public function calcMontantTTC() {
        $amt = 0;
        foreach ($this->commandeProducts as $commandeProduct) {
            $amt += $commandeProduct->calcMontantTTC();
        }
        foreach ($this->commandeServices as $commandeService) {
            $amt += $commandeService->calcMontantTTC();
        }
        return $amt;
    }
}

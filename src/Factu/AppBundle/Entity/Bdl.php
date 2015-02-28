<?php

namespace Factu\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Bdl
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Factu\AppBundle\Entity\BdlRepository")
 */
class Bdl
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
    * @ORM\ManyToOne(targetEntity="Factu\AppBundle\Entity\Client")
    * @ORM\JoinColumn(nullable=false)
    */
    private $client;

    /**
     * @var integer
     *
     * @ORM\Column(name="num_bdl", type="integer")
     */
    private $numBdl;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_bdl", type="datetime")
     */
    private $dateBdl;

    /**
    * @ORM\OneToMany(targetEntity="Factu\AppBundle\Entity\CommandeProduct", mappedBy="bdl", cascade={"persist", "remove"}, orphanRemoval=true)
    */
    protected $commandeProducts;

    /**
    * @ORM\OneToMany(targetEntity="Factu\AppBundle\Entity\CommandeService", mappedBy="bdl", cascade={"persist", "remove"}, orphanRemoval=true)
    */
    protected $commandeServices;

    /**
    * @ORM\OneToMany(targetEntity="Factu\AppBundle\Entity\Commande", mappedBy="bdl")
    */
    protected $commandes;

    public function __construct()
    {
        $this->commandeProducts = new ArrayCollection();
        $this->commandeServices = new ArrayCollection();
        $this->commandes = new ArrayCollection();
        
        $this->dateBdl = new \Datetime();
    }

    public function formatedLabel() {
        return $this->numBdl . " - " . $this->client->formatedLabel() . " - " . $this->title;
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
     * Set numBdl
     *
     * @param integer $numBdl
     * @return Bdl
     */
    public function setNumBdl($numBdl)
    {
        $this->numBdl = $numBdl;

        return $this;
    }

    /**
     * Get numBdl
     *
     * @return integer 
     */
    public function getNumBdl()
    {
        return $this->numBdl;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Bdl
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
     * Set dateBdl
     *
     * @param \DateTime $dateBdl
     * @return Bdl
     */
    public function setDateBdl($dateBdl)
    {
        $this->dateBdl = $dateBdl;

        return $this;
    }

    /**
     * Get dateBdl
     *
     * @return \DateTime 
     */
    public function getDateBdl()
    {
        return $this->dateBdl;
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
        $commandeProducts->setBdl($this);
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
        $commandeProduct->setBdl(null);
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
        $commandeService->setBdl($this);
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
        $commandeService->setBdl(null);
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

    /***************************************************************************
    *                               COMMANDE 
    ****************************************************************************/

    /**
     * Add commande
     *
     * @param \Factu\AppBundle\Entity\CommandeService $commande
     * @return Commande
     */
    public function addCommande(\Factu\AppBundle\Entity\Commande $commande)
    {
        $this->commande[] = $commande;
        $commande->setBdl($this);
        return $this;
    }

    /**
     * Remove commande
     *
     * @param \Factu\AppBundle\Entity\Commande $commande
     */
    public function removeCommande(\Factu\AppBundle\Entity\Commande $commande)
    {
        $this->commande->removeElement($commande);
        $commandeService->setBdl(null);
    }

    /**
     * Get commandes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCommandes()
    {
        return $this->commandes;
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

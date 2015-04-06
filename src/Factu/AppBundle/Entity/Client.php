<?php

namespace Factu\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Client
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Factu\AppBundle\Entity\ClientRepository")
 */
class Client
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
     * @var integer
     *
     * @ORM\Column(name="num_client", type="integer")
     */
    private $numClient;

    /**
     * @var string
     *
     * @ORM\Column(name="num_tva", type="string", length=255)
     */
    private $numTva;

    /**
     * @ORM\ManyToOne(targetEntity="Factu\AppBundle\Entity\Type")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $civilite;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_info", type="string", length=255, nullable=true)
     */
    private $nomInfo;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=255, nullable=true)
     */
    private $tel;

    /**
     * @var string
     *
     * @ORM\Column(name="portable", type="string", length=255, nullable=true)
     */
    private $portable;

    /**
     * @var string
     *
     * @ORM\Column(name="fax", type="string", length=255, nullable=true)
     */
    private $fax;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="commentaire", type="string", length=255, nullable=true)
     */
    private $commentaire;

    /**
     * @var string
     *
     * @ORM\Column(name="rue", type="string", length=255)
     */
    private $rue;

    /**
     * @var string
     *
     * @ORM\Column(name="bat", type="string", length=255, nullable=true)
     */
    private $bat;

    /**
     * @var string
     *
     * @ORM\Column(name="bp", type="string", length=255, nullable=true)
     */
    private $bp;

    /**
     * @var string
     *
     * @ORM\Column(name="code_postal", type="string", length=255, nullable=true)
     */
    private $codePostal;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=255)
     */
    private $ville;

    /**
     * @ORM\ManyToOne(targetEntity="Factu\AppBundle\Entity\Country")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $pays;

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
     * Set numClient
     *
     * @param integer $numClient
     * @return Client
     */
    public function setNumClient($numClient)
    {
        $this->numClient = $numClient;

        return $this;
    }

    /**
     * Get numClient
     *
     * @return integer 
     */
    public function getNumClient()
    {
        return $this->numClient;
    }

    /**
     * Set numTva
     *
     * @param string $numTva
     * @return Client
     */
    public function setNumTva($numTva)
    {
        $this->numTva = $numTva;

        return $this;
    }

    /**
     * Get numTva
     *
     * @return string 
     */
    public function getNumTva()
    {
        return $this->numTva;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Client
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set nomInfo
     *
     * @param string $nomInfo
     * @return Client
     */
    public function setNomInfo($nomInfo)
    {
        $this->nomInfo = $nomInfo;

        return $this;
    }

    /**
     * Get nomInfo
     *
     * @return string 
     */
    public function getNomInfo()
    {
        return $this->nomInfo;
    }

    public function formatedLabel() {
        return $this->nom . " " . $this->prenom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     * @return Client
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set tel
     *
     * @param string $tel
     * @return Client
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel
     *
     * @return string 
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set portable
     *
     * @param string $portable
     * @return Client
     */
    public function setPortable($portable)
    {
        $this->portable = $portable;

        return $this;
    }

    /**
     * Get portable
     *
     * @return string 
     */
    public function getPortable()
    {
        return $this->portable;
    }

    /**
     * Set fax
     *
     * @param string $fax
     * @return Client
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string 
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Client
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set commentaire
     *
     * @param string $commentaire
     * @return Client
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string 
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * Set civilite
     *
     * @param \Factu\AppBundle\Entity\Type $civilite
     * @return Client
     */
    public function setCivilite(\Factu\AppBundle\Entity\Type $civilite = null)
    {
        $this->civilite = $civilite;

        return $this;
    }


    /**
     * Set rue
     *
     * @param string $rue
     * @return Client
     */
    public function setRue($rue)
    {
        $this->rue = $rue;

        return $this;
    }

    /**
     * Get rue
     *
     * @return string 
     */
    public function getRue()
    {
        return $this->rue;
    }

    /**
     * Set bat
     *
     * @param string $bat
     * @return Client
     */
    public function setBat($bat)
    {
        $this->bat = $bat;

        return $this;
    }

    /**
     * Get bat
     *
     * @return string 
     */
    public function getBat()
    {
        return $this->bat;
    }

    /**
     * Set bp
     *
     * @param string $bp
     * @return Client
     */
    public function setBp($bp)
    {
        $this->bp = $bp;

        return $this;
    }

    /**
     * Get bp
     *
     * @return string 
     */
    public function getBp()
    {
        return $this->bp;
    }

    /**
     * Set codePostal
     *
     * @param string $codePostal
     * @return Client
     */
    public function setCodePostal($codePostal)
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * Get codePostal
     *
     * @return string 
     */
    public function getCodePostal()
    {
        return $this->codePostal;
    }

    /**
     * Set ville
     *
     * @param string $ville
     * @return Client
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string 
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Get civilite
     *
     * @return \Factu\AppBundle\Entity\Type 
     */
    public function getCivilite()
    {
        return $this->civilite;
    }

    /**
     * Set pays
     *
     * @param \Factu\AppBundle\Entity\Country $pays
     * @return Client
     */
    public function setPays(\Factu\AppBundle\Entity\Country $pays)
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * Get pays
     *
     * @return \Factu\AppBundle\Entity\Country 
     */
    public function getPays()
    {
        return $this->pays;
    }

    public function getlongAdresse() {

       $str = "";
       if ($this->rue != null) {
        $str .= $this->rue . " ";
       }

       if ($this->bat != null) {
        $str .= $this->bat . " ";
       }

       if ($this->bp != null) {
        $str .= $this->bp . " ";
       }

       if ($this->codePostal != null) {
        $str .= $this->codePostal . " ";
       }

       if ($this->ville != null) {
        $str .= $this->ville . " ";
       }

       if ($this->pays != null) {
        $str .= $this->pays->getName() . " ";
       }

       return $str;
    }

}

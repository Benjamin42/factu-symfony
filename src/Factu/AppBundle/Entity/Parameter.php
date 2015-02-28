<?php

namespace Factu\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Parameter
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Factu\AppBundle\Entity\ParameterRepository")
 */
class Parameter
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
     * @ORM\Column(name="p_name", type="string", length=255)
     */
    private $pName;

    /**
     * @var string
     *
     * @ORM\Column(name="p_value", type="string", length=255)
     */
    private $pValue;

    /**
     * @var string
     *
     * @ORM\Column(name="grp", type="string", length=255)
     */
    private $grp;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;


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
     * Set pName
     *
     * @param string $pName
     * @return Parameter
     */
    public function setPName($pName)
    {
        $this->pName = $pName;

        return $this;
    }

    /**
     * Get pName
     *
     * @return string 
     */
    public function getPName()
    {
        return $this->pName;
    }

    /**
     * Set pValue
     *
     * @param string $pValue
     * @return Parameter
     */
    public function setPValue($pValue)
    {
        $this->pValue = $pValue;

        return $this;
    }

    /**
     * Get pValue
     *
     * @return string 
     */
    public function getPValue()
    {
        return $this->pValue;
    }

    /**
     * Set grp
     *
     * @param string $grp
     * @return Parameter
     */
    public function setGrp($grp)
    {
        $this->grp = $grp;

        return $this;
    }

    /**
     * Get grp
     *
     * @return string 
     */
    public function getGrp()
    {
        return $this->grp;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Parameter
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }
}

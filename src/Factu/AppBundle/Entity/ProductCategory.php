<?php

namespace Factu\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductCategory
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Factu\AppBundle\Entity\ProductCategoryRepository")
 */
class ProductCategory
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
     * @var boolean
     *
     * @ORM\Column(name="isFollowedStat", type="boolean")
     */
    private $isFollowedStat;


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
     * @return ProductCategory
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
     * Set isFollowedStat
     *
     * @param boolean $isFollowedStat
     * @return ProductCategory
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

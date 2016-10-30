<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="Android")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AndroidRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Android
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
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=100)
     */
    private $code;

    /**
     * @ORM\Column(name="deletedAt", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @var datetime $created
     *
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @var datetime $updated
     *
     * @ORM\Column(type="datetime")
     */
    private $updated;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Point",
      mappedBy="Android")
     */
    private $Points;

    public function __construct()
    {
        $this->Points = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->titre . ' (' . $this->code . ')';
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
     * Set titre
     *
     * @param string $titre
     * @return Article
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set titre
     *
     * @param string $titre
     * @return Article
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string 
     */
    public function getTitre()
    {
        return $this->titre;
    }

    public function addPoint(\AppBundle\Entity\Point $Point)
    {
        $this->Points[] = $Point;
        return $this;
    }

    public function removePoint(\AppBundle\Entity\Point $Point)
    {
        $this->Points->removeElement($Point);
    }

    public function getPoints()
    {
        return $this->Points;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getUpdated()
    {
        return $this->updated;
    }


    /**
     * Gets triggered only on insert

     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->setCreated(new \DateTime("now"));
        $this->setUpdated(new \DateTime("now"));
    }

    /**
     * Gets triggered every time on update

     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->setUpdated(new \DateTime("now"));
    }
}

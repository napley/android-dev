<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Point
 *
 * @ORM\Table(name="Point")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PointRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Point
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
     * @ORM\Column(name="pct", type="float")
     */
    private $pct;

    /**
     * @var string
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(name="deletedAt", type="datetime", nullable=true)
     */
    public $deletedAt;

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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Android",
      inversedBy="Points")
     */
    private $Android;

    public function __construct()
    {
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
     * Set sousTitre
     *
     * @param string $sousTitre
     * @return Article
     */
    public function setPct($pct)
    {
        $this->pct = $pct;

        return $this;
    }

    /**
     * Get sousTitre
     *
     * @return string 
     */
    public function getPct()
    {
        return $this->pct;
    }

    /**
     * Set contenu
     *
     * @param string $contenu
     * @return Article
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get contenu
     *
     * @return string 
     */
    public function getDate()
    {
        return $this->date;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getUpdated()
    {
        return $this->updated;
    }

    function setCreated(\DateTime $created)
    {
        $this->created = $created;
    }

    function setUpdated(\DateTime $updated)
    {
        $this->updated = $updated;
    }
    

    /**
     * set Categorie
     * 
     * @param \AppBundle\Entity\Auteur $auteur
     */
    public function setAndroid(\AppBundle\Entity\Android $android)
    {
        if (!empty($this->Android)) {
            $this->removeAndroid();
        }
        $this->Android = $android;
    }

    /**
     * remove Categorie
     * 
     * @param \AppBundle\Entity\Auteur $auteur
     */
    public function removeAndroid()
    {
        $this->Android->removePoints($this);
    }

    /**
     * Get Categorie
     *
     * @return \AppBundle\Entity\Categorie
     */
    public function getAndroid()
    {
        return $this->Android;
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

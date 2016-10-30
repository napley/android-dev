<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Projet
 *
 * @ORM\Table(name="Projet")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjetRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Projet
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
     * @ORM\Column(name="vignette", type="string", length=255, nullable=true)
     */
    private $vignette;
    
    /**
     * @var string
     *
     * @ORM\Column(name="sousTitre", type="text")
     */
    private $sousTitre;

    /**
     * @var string
     *
     * @ORM\Column(name="contenu", type="text")
     */
    private $contenu;

    /**
     * @var boolean
     *
     * @ORM\Column(name="visible", type="boolean")
     */
    private $visible = false;

    /**
     * @var string
     *
     * @ORM\Column(name="contenuFin", type="text")
     */
    private $contenuFin;

    /**
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(name="deletedAt", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ArticleProjet",
      mappedBy="Projet")
     * @ORM\OrderBy({"rang" = "ASC"})
     */
    private $Articles;

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

    public function __construct()
    {
        $this->Articles = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Projet
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

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
    
    public function getNom()
    {
        return $this->titre;
    }

    /**
     * Get titre
     *
     * @return string 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Get titre
     *
     * @return string 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set sousTitre
     *
     * @param string $sousTitre
     * @return Projet
     */
    public function setSousTitre($sousTitre)
    {
        $this->sousTitre = $sousTitre;

        return $this;
    }

    /**
     * Get sousTitre
     *
     * @return string 
     */
    public function getSousTitre()
    {
        return $this->sousTitre;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getVignette()
    {
        if (!empty($this->vignette)) {
            return $this->vignette;
        }
        else{
            return '/images/no-img.jpg';
        }
    }

    /**
     * Set titre
     *
     * @param string $titre
     * @return Article
     */
    public function setVignette($vignette)
    {
        $this->vignette = $vignette;

        return $this;
    }
 
    /**
     * Set contenu
     *
     * @param string $contenu
     * @return Projet
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu
     *
     * @return string 
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * Set titre
     *
     * @param string $titre
     * @return Article
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Set contenuFin
     *
     * @param string $contenuFin
     * @return Projet
     */
    public function setContenuFin($contenuFin)
    {
        $this->contenuFin = $contenuFin;

        return $this;
    }

    /**
     * Get contenuFin
     *
     * @return string 
     */
    public function getContenuFin()
    {
        return $this->contenuFin;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Projet
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    public function addArticle(\AppBundle\Entity\Article $article)
    {
        $this->Articles[] = $article;
        return $this;
    }

    public function removeArticle(\AppBundle\Entity\Article $article)
    {
        $this->Articles->removeElement($article);
    }

    public function getArticles()
    {
        return $this->Articles;
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
    
    public function getPublishedAt()
    {
        return $this->created;
    }
    
    public function getFeedItemDescription()
    {
        return $this->getSousTitre();
    }

    public function getFeedItemLink()
    {
        return 'http://www.android-dev.fr/projet/' . $this->slug;
    }

    public function getFeedItemPubDate()
    {
        return $this->getCreated();
    }

    public function getFeedItemTitle()
    {
        return $this->getTitre();
    }
    
}

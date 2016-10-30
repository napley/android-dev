<?php
// src/AndroidDev/UserBundle/Entity/User.php
 
namespace AppBundle\Entity;
 
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
 
/**
 * Auteur
 *
 * @ORM\Table("Auteur")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
     /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
     protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="text", nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="text", nullable=true)
     */
    private $prenom;


    /**
     * @var string
     *
     * @ORM\Column(name="lienProfil", type="text", nullable=true)
     */
    private $lienProfil;

    /**
     * @var string
     *
     * @ORM\Column(name="textPresentation", type="text", nullable=true)
     */
    private $textPresentation;

    /**
     * @ORM\Column(name="deletedAt", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Article",
      mappedBy="Auteur")
     */
    private $Articles;

    
    public function __construct()
    {
        $this->Articles = new \Doctrine\Common\Collections\ArrayCollection();
        parent::__construct();
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
     * Set nom
     *
     * @param string $nom
     * @return Auteur
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
     * Set prenom
     *
     * @param string $prenom
     * @return Auteur
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
     * Set lienProfil
     *
     * @param string $lienProfil
     * @return Auteur
     */
    public function setLienProfil($lienProfil)
    {
        $this->lienProfil = $lienProfil;

        return $this;
    }

    /**
     * Get lienProfil
     *
     * @return string 
     */
    public function getLienProfil()
    {
        return $this->lienProfil;
    }

    /**
     * Set textPresentation
     *
     * @param string $textPresentation
     * @return Auteur
     */
    public function setTextPresentation($textPresentation)
    {
        $this->textPresentation = $textPresentation;

        return $this;
    }

    /**
     * Get textPresentation
     *
     * @return string 
     */
    public function getTextPresentation()
    {
        return $this->textPresentation;
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
}
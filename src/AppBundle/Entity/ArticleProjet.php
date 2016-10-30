<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ArticleProjet
 *
 * @ORM\Table(name="ArticleProjet")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArticleProjetRepository")
 */
class ArticleProjet
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
     * @ORM\Column(name="rang", type="integer")
     */
    private $rang;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Article", inversedBy="Projet", cascade={"persist"})
     * */
    private $Article;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Projet", cascade={"persist"},
      inversedBy="Articles")
     */
    private $Projet;

    public function __construct($projet, $article, $index)
    {
        $this->setArticle($article);
        $this->setProjet($projet);
        $this->setRang($index);
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
     * Set rang
     *
     * @param integer $rang
     * @return ArticleProjet
     */
    public function setRang($rang)
    {
        $this->rang = $rang;

        return $this;
    }

    /**
     * Get rang
     *
     * @return integer 
     */
    public function getRang()
    {
        return $this->rang;
    }

    /**
     * Set rang
     *
     * @param integer $rang
     * @return ArticleProjet
     */
    public function setArticle($article)
    {
        $this->Article = $article;

        return $this;
    }

    /**
     * Add Article
     * 
     * @param \AppBundle\Entity\Article $article
     * @return \AppBundle\Entity\ArticleProjet
     */
    public function AddArticles(\AppBundle\Entity\Article $article, $index)
    {
        $this->Articles[] = new ArticleProjet($article, $index);
    }

    /**
     * Get Article
     *
     * @return integer 
     */
    public function getArticle()
    {
        return $this->Article;
    }

    /**
     * Set rang
     *
     * @param integer $rang
     * @return ArticleProjet
     */
    public function setProjet($project)
    {
        $this->Projet = $project;

        return $this;
    }

    /**
     * Get Article
     *
     * @return integer 
     */
    public function getProjet()
    {
        return $this->Projet;
    }

    public function getPrevPart()
    {
        if ($this->getRang() > 1) {
            foreach ($this->getProjet()->getArticles() as $part) {
                if ($part->getRang() == $this->getRang() - 1) {
                    return $part;
                }
            }
        }
        return null;
    }

    public function getNextPart()
    {
        foreach ($this->getProjet()->getArticles() as $part) {
            if ($part->getRang() == $this->getRang() + 1) {
                return $part;
            }
        }
        return null;
    }

}

<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stat
 *
 * @ORM\Table(name="stat")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StatRepository")
 */
class Stat
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
     * @var int
     *
     * @ORM\Column(name="rank", type="integer")
     */
    private $rank;
    
    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Article")
     * @ORM\JoinColumn(name="article_id", referencedColumnName="id")
     */
    private $Article;


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
     * Set rank
     *
     * @param int $rank
     * @return \AppBundle\Entity\Stat
     */
    public function setRank($rank)
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set titre
     *
     * @param string $titre
     * @return Article
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get rank
     *
     * @return int 
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * set Article
     * 
     * @param \AppBundle\Entity\Article $article
     */
    public function setArticle(\AppBundle\Entity\Article $article)
    {
        $this->Article = $article;
    }

    /**
     * get Article
     * 
     * @return \AppBundle\Entity\Article $article
     */
    public function getArticle()
    {
        return $this->Article;
    }
}

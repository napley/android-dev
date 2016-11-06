<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ArticleRepository extends EntityRepository
{
    public function getLatest($type = null)
    {        
        if ($type == null) {
            return $this->getEntityManager()
            ->createQuery(
                'SELECT a FROM AppBundle:Article a '
                    . ' WHERE a.visible = 1 AND (a.publishedAt IS NULL OR a.publishedAt< CURRENT_TIMESTAMP()) '
                    . 'ORDER BY a.created DESC'
            )
            ->getResult();
        } else {
        
        return $this->getEntityManager()
            ->createQuery(
                'SELECT a FROM AppBundle:Article a '
                    . ' JOIN a.Type t'
                    . ' WHERE a.visible = 1 AND t.id = :type_id AND (a.publishedAt IS NULL OR a.publishedAt< CURRENT_TIMESTAMP()) '
                    . 'ORDER BY a.created DESC'
            )
            ->setParameter('type_id', $type)
            ->getResult();
        }
    }
    
    public function findTotal($type)
    {
        if (!empty($type)) {
            $join = ' JOIN a.Type at';
            $where = ' AND at.id = ' . $type;
        }
        
        return $this->getEntityManager()
            ->createQuery(
                'SELECT count(a) FROM AppBundle:Article a '
                    . $join
                    . ' WHERE a.visible = 1 AND (a.publishedAt IS NULL OR a.publishedAt< CURRENT_TIMESTAMP()) '
                    . $where
                    . ' ORDER BY a.created DESC'
            )
            ->getSingleScalarResult();
    }
    
    public function findArticleByCat($cat)
    {
        $query = $this->_em->createQuery('SELECT a FROM AppBundle:Article a JOIN a.Type at JOIN a.Categorie ac WHERE a.visible = 1 AND at.id = 1 AND ac.slug = :slug AND (a.publishedAt IS NULL OR a.publishedAt< CURRENT_TIMESTAMP()) ORDER BY a.created DESC');
        $query->setParameter('slug', $cat->getSlug());
        return $query->getResult();
    }
    
    public function findTutoByCat($cat)
    {
        $query = $this->_em->createQuery('SELECT a FROM AppBundle:Article a JOIN a.Type at JOIN a.Categorie ac WHERE a.visible = 1 AND at.id = 2 AND ac.slug = :slug AND (a.publishedAt IS NULL OR a.publishedAt< CURRENT_TIMESTAMP()) ORDER BY a.created DESC');
        $query->setParameter('slug', $cat->getSlug());
        return $query->getResult();
    }

    /**
     * retourne les articles liés pour un article
     * @param \AppBundle\Entity\Article $article 
     * @param int $max
     * @return type
     */
    public function findLink($article, $max)
    {
        $idMotCle = [];
        $listeArticles = [];
        $listeArticlesId = [];

        foreach ($article->getMotCles() as $motcle) {
            $idMotCle[] = $motcle->getId();
        }

        $query = $this->_em->createQuery('SELECT a FROM AppBundle:Article a JOIN a.MotCles am WHERE a.visible = 1 AND am.id IN (:motcles) AND a.id != :id  AND (a.publishedAt IS NULL OR a.publishedAt< CURRENT_TIMESTAMP()) ORDER BY a.created DESC');
        $query->setParameter('motcles', $idMotCle);
        $query->setParameter('id', $article->getId());
        $query->setMaxResults(6);
        $listeArticles = array_merge($listeArticles, $query->getResult());

        $listeArticlesId = [$article->getId()];
        foreach ($listeArticles as $articleliste) {
            $listeArticlesId[] = $articleliste->getId();
        }

        $query = $this->_em->createQuery('SELECT a FROM AppBundle:Article a JOIN a.Type at WHERE a.visible = 1 AND at.id = :type AND a.id NOT IN (:id)  AND (a.publishedAt IS NULL OR a.publishedAt< CURRENT_TIMESTAMP()) ORDER BY a.created DESC');
        $query->setParameter('type', $article->getType()->getId());
        $query->setParameter('id', $listeArticlesId);
        $query->setMaxResults(2);
        $listeArticles = array_merge($listeArticles, $query->getResult());

        $listeArticlesId = [$article->getId()];
        foreach ($listeArticles as $articleliste) {
            $listeArticlesId[] = $articleliste->getId();
        }

        $query = $this->_em->createQuery('SELECT a FROM AppBundle:Article a JOIN a.Categorie ac WHERE a.visible = 1 AND ac.id = :cat AND a.id NOT IN (:id)  AND (a.publishedAt IS NULL OR a.publishedAt< CURRENT_TIMESTAMP()) ORDER BY a.created DESC');
        $query->setParameter('cat', $article->getCategorie()->getId());
        $query->setParameter('id', $listeArticlesId);
        $query->setMaxResults(2);
        $listeArticles = array_merge($listeArticles, $query->getResult());

        $listeArticlesId = [$article->getId()];
        foreach ($listeArticles as $articleliste) {
            $listeArticlesId[] = $articleliste->getId();
        }

        $query = $this->_em->createQuery('SELECT a FROM AppBundle:Article a JOIN a.Categorie ac WHERE a.visible = 1 AND ac.id = :cat AND a.id NOT IN (:id)  AND (a.publishedAt IS NULL OR a.publishedAt< CURRENT_TIMESTAMP()) ORDER BY a.created DESC');
        $query->setParameter('cat', $article->getCategorie()->getId());
        $query->setParameter('id', $listeArticlesId);
        $query->setMaxResults(2);
        $listeArticles = array_merge($listeArticles, $query->getResult());

        $listeArticlesId = [$article->getId()];
        foreach ($listeArticles as $articleliste) {
            $listeArticlesId[] = $articleliste->getId();
        }

        $nbArticleReste = $max - count($listeArticles);
        
        if ($nbArticleReste > 0) {
            $query = $this->_em->createQuery('SELECT a FROM AppBundle:Article a WHERE a.visible = 1 AND a.id NOT IN (:id)  AND (a.publishedAt IS NULL OR a.publishedAt< CURRENT_TIMESTAMP()) ORDER BY a.created DESC');
            $query->setParameter('id', $listeArticlesId);
            $query->setMaxResults($nbArticleReste);
            $listeArticles = array_merge($listeArticles, $query->getResult());
        }

        return $listeArticles;
    }

    public function getArticleBySearch($motCles)
    {
        $requete = 'SELECT a FROM AppBundle:Article a
                        JOIN a.Type at
                        WHERE a.visible = 1 AND (a.publishedAt IS NULL OR a.publishedAt< CURRENT_TIMESTAMP())';

        foreach ($motCles as $cle => $motCle) {
            $requete .=" AND ( a.titre LIKE '%" . $motCle . "%' OR a.sousTitre LIKE '%" . $motCle . "%' OR a.contenu LIKE '%" . $motCle . "%' ) ";
        }
        $requete .=" ORDER BY a.created DESC ";

        $query = $this->_em->createQuery($requete);
        $articles = $query->getResult();

        return $articles;
    }
    

    public function findByMotCles($slug)
    {

        $query = $this->_em->createQuery('SELECT a FROM AppBundle:Article a JOIN a.MotCles am WHERE am.slug = :slug AND a.visible=1 AND (a.publishedAt IS NULL OR a.publishedAt< CURRENT_TIMESTAMP()) ORDER BY a.created DESC ');
        $query->setParameter('slug', $slug);
        return $query->getResult();
    }

    public function findByTop()
    {
        $query = $this->_em->createQuery('SELECT count(a) FROM AppBundle:Article a WHERE a.top = 1 AND a.visible=1 AND (a.publishedAt IS NULL OR a.publishedAt< CURRENT_TIMESTAMP())');
        $count = $query->getSingleScalarResult();

        $query = $this->_em->createQuery('SELECT a FROM AppBundle:Article a WHERE a.top = 1 AND a.visible=1 AND (a.publishedAt IS NULL OR a.publishedAt< CURRENT_TIMESTAMP())');
        $query->setFirstResult(rand(0, $count - 1));
        $query->setMaxResults(1);
        return $query->getOneOrNullResult();
    }

    public function findLastUpdateArticle($nb)
    {
        $query = $this->_em->createQuery('SELECT a 
            FROM AppBundle:Article a 
            JOIN a.Type t
            WHERE (t.id = 1 OR t.id = 2) AND a.visible = 1 AND (a.publishedAt IS NULL OR a.publishedAt< CURRENT_TIMESTAMP()) AND a.updated > a.created
            ORDER BY a.updated DESC');
        $query->setMaxResults($nb);
        return $query->getResult();
    }

    public function findArticleCatByIdKey($id, $nb)
    {
        $query = $this->_em->createQuery('SELECT a FROM AppBundle:Article a JOIN a.Type at JOIN a.MotCles am WHERE a.visible = 1 AND at.id = 1 AND am.id = :id AND (a.publishedAt IS NULL OR a.publishedAt< CURRENT_TIMESTAMP()) ORDER BY a.created DESC');
        $query->setParameter('id', $id);
        $query->setMaxResults($nb);
        return $query->getResult();
    }

    public function findArticleBySearch($motCles)
    {
        $requete = 'SELECT a FROM AppBundle:Article a
                        JOIN a.Type at
                        WHERE a.visible = 1 AND (a.publishedAt IS NULL OR a.publishedAt< CURRENT_TIMESTAMP())';

        foreach ($motCles as $motCle) {
            $requete .=" AND ( a.titre LIKE '%" . $motCle . "%' OR a.sousTitre LIKE '%" . $motCle . "%' OR a.contenu LIKE '%" . $motCle . "%' ) ";
        }
        $requete .=" ORDER BY a.created DESC ";

        $query = $this->_em->createQuery($requete);
        $articles = $query->getResult();

        return $articles;
    }

    public function findAllPartProject()
    {

        $query = $this->_em->createQuery('SELECT a FROM AppBundle:Article a JOIN a.Type t WHERE t.id = 3 AND a.visible = 1 AND (a.publishedAt IS NULL OR a.publishedAt< CURRENT_TIMESTAMP()) ORDER BY a.created DESC');
        return $query->getResult();
    }
    
}
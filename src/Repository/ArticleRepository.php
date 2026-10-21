<?php

namespace App\Repository;

use App\Model\Article;
use App\Model\Page;
use App\Model\Site;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;


class ArticleRepository implements IArticleRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $_em;

    /**
     * UserRepository constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->_em = $entityManager;
    }

    /**
     * @param Article $article
     */
    public function delete(Article $article)
    {
        $this->_em->remove($article);
        $this->_em->flush();
    }


    /**
     * @param Article $article
     */
    public function save(Article $article)
    {
        $this->_em->persist($article);
        $this->_em->flush();
    }

    /**
     * @param Site $site
     * @param int $pageNr
     * @param bool $publishedOnly
     * @return Paginator
     */
    public function getPaginator(Site $site, $pageNr = 1, $publishedOnly=true)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('article')
            ->from(Article::class, 'article')
            ->where($qb->expr()->eq('article.site', $site->getId()))
            ->orderBy('article.publishDate', 'desc');

        if($publishedOnly)
        {
            $qb->andWhere($qb->expr()->eq('article.isPublished', '1'));
        }

        return new Paginator($qb, $pageNr);
    }

    /**
     * @param $id
     * @return Page|object
     */
    public function findById($id)
    {
        return $this->_em->find(Article::class, $id);
    }

    /**
     * @return Article[]
     */
    public function getArticlesToPublish()
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('article')
            ->from(Article::class, 'article')
            ->where($queryBuilder->expr()->lte('article.publishDate', ':now'))
            ->andWhere($queryBuilder->expr()->eq('article.isPublished', '0'))
            ->setParameter('now', new DateTime());

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param Site $site
     * @param string $url
     * @param bool $publishedOnly
     * @return Article
     */
    public function getArticleByUrl(Site $site, $url, $publishedOnly=true)
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('article')
            ->from(Article::class, 'article')
            ->andWhere($queryBuilder->expr()->eq('article.url', ':url'))
            ->andWhere($queryBuilder->expr()->eq('article.site', $site->getId()))
            ->setParameter('url', $url);

        if($publishedOnly)
        {
            $queryBuilder->andWhere($queryBuilder->expr()->eq('article.isPublished', '1'));
        }

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    /**
     * @param Site $site
     * @param int $limit
     * @return Article[]
     */
    public function getMostRecentPublishedArticles(Site $site, $limit = 3)
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('article')
            ->from(Article::class, 'article')
            ->andWhere($queryBuilder->expr()->eq('article.isPublished', 1))
            ->andWhere($queryBuilder->expr()->eq('article.site', $site->getId()))
            ->orderBy('article.publishDate', 'desc')
            ->setMaxResults($limit);

        return $queryBuilder->getQuery()->getResult();
    }
}
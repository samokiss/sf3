<?php
/**
 * Created by PhpStorm.
 * User: samuelgomis
 * Date: 25/08/2017
 * Time: 13:54
 */

namespace AppBundle\Manager;


use AppBundle\Entity\Article;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

class ArticleManager extends BaseManager
{
    public function __construct(EntityManager $em, string $className)
    {
        parent::__construct($em, $className);
    }

    /**
     * @param Request $request
     * @param Article $article
     */
    public function getArticle(Request $request, Article $article)
    {
        $content = str_replace('"', '', $request->get('content'));
        
        if (is_null($article)) {
            $article = new Article();
            $article->setDate(new \DateTime());
        }

        $article->setTitle($request->get('title'));
        $article->setContent($content);
        
        return $article;
    }
}
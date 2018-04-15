<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 17-02-08
 * Time: 7:24 PM
 */

namespace AppBundle\Manager;

use AppBundle\Entity\Article;
use AppBundle\Entity\Tag;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

class TagManager extends BaseManager
{
    public function __construct(EntityManager $em, $className)
    {
        parent::__construct($em, $className);
    }

    /**
     * @param Request $request
     */
    public function addTag(Request $request, Article $article)
    {
        $tagGlobal = $this->em->getRepository($this->className)->findOneByTitle('Global');

        if (!$article->getTags()->contains($tagGlobal)) {
            $article->addTag($tagGlobal);
        }
        
        if (null !== $request->get('tags')) {
            foreach ($request->get('tags') as $key => $tag) {
                $tagToAdd = $this->em->getRepository($this->className)->findOneByTitle($tag['tag']);
                if (is_null($tagToAdd)) {
                    $tagToAdd = new Tag();
                    $tagToAdd->setTitle($tag['tag']);
                }
                if (!$article->getTags()->contains($tagToAdd)) {
                    $article->addTag($tagToAdd);
                }
            }
        }
    }

    /**
     * @param Request $request
     */
    public function removeTag(Request $request, Article $article)
    {
        if (null !== $request->get('deletedTags'))
        {
            foreach ($request->get('deletedTags') as $key => $tag)
            {
                $tagToRemove = $this->em->getRepository($this->className)->findOneByTitle($tag['tag']);
                $article->removeTag($tagToRemove);
            }
        }
    }

    public function getArticleByTag()
    {
        $tags = $this->em->getRepository($this->className)->findAll();

        $articleTags = [];
        foreach ($tags as $tag) {
            $articles = $this->em->getRepository('AppBundle:Article')->getArticleByTag($tag);
            if (!empty($articles)) {
                $articleTags[$this->getLightTagTitle($tag->getTitle())] = $articles;
            }
        }

        return $articleTags;
    }

    public function getLightTagTitle($title)
    {
        $title = str_replace('Symfony', 'sf', $title);
        $title = str_replace('Components', 'cmpt', $title);
        $title = str_replace('Fullstack', 'fs', $title);
        $title = str_replace('Object Oriented Programming', 'OOP', $title);

        return $title;
    }

    public function getFullTagTitle($title)
    {
        $title = str_replace('sf', 'Symfony', $title);
        $title = str_replace('cmpt', 'Components', $title);
        $title = str_replace('fs', 'Fullstack', $title);
        $title = str_replace('OOP', 'Object Oriented Programming', $title);

        return $title;
    }
    
}
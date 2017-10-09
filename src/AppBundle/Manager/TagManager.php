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



}
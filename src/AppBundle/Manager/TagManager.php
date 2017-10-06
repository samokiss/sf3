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
    private $tagUserVerseManager;

    public function __construct(EntityManager $em, $className)
    {
        parent::__construct($em, $className);
    }

    public function getArticleTag(Article $article)
    {
        $tags = $this->em->getRepository($this->className)->findBy([
            "article" => $article,
        ]);
        return $tags;
    }

    /**
     * @param $verses
     * @param Request $request
     */
    public function addTag($article, $request)
    {
        $verses = $this->em->getRepository('AppBundle:Verse')->findBy([
            'id' => explode(',', $verses),
        ]);
        
        $tags = $this->getTagList($article);
        
        if (null !== $request->get('tags')) {
            $this->createAndAddVerseToTag($request,$tags);
        }

        if (null !== $request->get('deletedTags')) {
            $this->deleteVerseTag($request,$user,$verses);
        }
    }
    
    public function getTagList($article)
    {
        $tagsList = $this->em->getRepository('AppBundle:TagUserVerse')->getTagByArticle($article);
        $tags = [];
        foreach ($tagsList as $key => $tag) {
            $tags[$key] = $tag->getTag();
        }
        
        return $tags;
    }

    public function createAndAddArticleTag(Request $request, $tags)
    {
        foreach ($request->get('tags') as $tag) {
            $tagGetted = $this->em->getRepository('AppBundle:Tag')->findOneByTitle($tag);
            if (!in_array($tagGetted, $tags)) {
                if (null === $tagGetted) {
                    $tagGetted = new Tag();
                    $tagGetted->setTitle(strtolower($tag['title']));
                }
                $tagVerse = new TagUserVerse();
                foreach ($verses as $vrs) {
                    $tagVerse->addVerse($vrs);
                }
                $tagVerse->setUser($user);
                $tagVerse->setTag($praylist);
                $this->tagUserVerseManager->save($tagVerse);
            }
            $tg = $this->em->getRepository('AppBundle:TagUserVerse')->getTagByUser($tag['tag'], $user, $verses);
            $tg = $tg[0];
            foreach ($verses as $vrs) {
                if (!$tg->getVerses()->contains($vrs)) {
                    $tg->addVerse($vrs);
                }
            }
            $tg->setUser($user);
            $tg->setTag($praylist);
            $this->tagUserVerseManager->save($tg);
        }
    }

    public function deleteVerseTag(Request $request, User $user, $verses)
    {
        foreach ($request->get('deletedTags') as $tag) {
            $tagVerses = $this->em->getRepository('AppBundle:TagUserVerse')->getTagByUser($tag['tag'], $user, $verses);
            foreach ($tagVerses as $tagVerse) {
                $this->tagUserVerseManager->delete($tagVerse);
            }
        }
    }

}
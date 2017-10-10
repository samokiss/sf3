<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Entity\Tag;
use AppBundle\Form\ArticleType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage", options={"expose"=true})
     */
    public function indexAction(Article $article = null)
    {
        $tags = $this->getDoctrine()->getRepository('AppBundle:Tag')->findAll();

        $articleTags = [];
        foreach ($tags as $tag) {
            $articles = $this->getDoctrine()->getRepository('AppBundle:Article')->getArticleByTag($tag);
            if (!empty($articles)) {
                $articleTags[$tag->getTitle()] = $articles;
            }
        }

        return $this->render(
            'default/index.html.twig',
            [
                'articleTags' => $articleTags,
                'articleList' => $articleTags['Global'],
            ]
        );
    }

    /**
     * @Route("/article/add", name="add_article", requirements={"id": "\d+"})
     * @Route("/article/{article}", name="edit_article", requirements={"id": "\d+"})
     */
    public function articleFormAction(Article $article = null)
    {
        $data = [];
        if (null == $article) {
            $article = new Article();
            $article->setDate(new \DateTime());
        } elseif (!is_null($article->getTags())) {
            foreach ($article->getTags() as $keys => $tag) {
                $data[]['tag'] = $tag->getTitle();
            }
        }

        $tags = $this->getDoctrine()->getRepository('AppBundle:Tag')->findAll();

        $articleTags = [];
        foreach ($tags as $tag) {
            $articles = $this->getDoctrine()->getRepository('AppBundle:Article')->getArticleByTag($tag);
            if (!empty($articles)) {
                $articleTags[$tag->getTitle()] = $articles;
            }
        }

        $form = $this->createForm(ArticleType::class, $article);


        return $this->render(
            'default/createOrEdit.html.twig',
            [
                'form' => $form->createView(),
                'articleTags' => $articleTags,
                'article' => $article,
                'articleTag' => json_encode($data),
            ]
        );
    }


    /**
     *
     * @Route("/tag/{tag}", name="tagged_article")
     * @Route("/tagtitle/{tagTitle}", name="tagged_article_title", options={"expose"=true})
     *
     * @param Tag $tag
     * @param Request $request
     * @return Response
     */
    public function taggedArticleAction(Tag $tag = null, Request $request)
    {

        if(is_string($request->get('tagTitle'))){
            $tagTitle = $request->get('tagTitle');
            $tag = $this->getDoctrine()->getRepository('AppBundle:Tag')->findOneByTitle($tagTitle);
        }

        $article = new Article();
        $article->setDate(new \DateTime());

        $articleTags[$tag->getTitle()] = $this->getDoctrine()->getRepository('AppBundle:Article')->getArticleByTag($tag);

        return $this->render(
            'default/index.html.twig',
            [
                'articleTags' => $articleTags,
                'articleList' => $articleTags[$tag->getTitle()],
                'articleTag' => json_encode([]),
            ]
        );
    }

    /**
     * @Route("/ajax/add", name="create_edit_article", options={"expose"=true})
     */
    public function createOrEditAction(Request $request)
    {
        $article = $this->getDoctrine()->getRepository('AppBundle:Article')->findOneById($request->get('id'));

        $article = $this->get('article.manager')->getArticle($request, $article);
        
        $this->get('tag.manager')->addTag($request, $article);
        $this->get('tag.manager')->removeTag($request, $article);
        $this->get('article.manager')->save($article);

        return new Response('enregistrement ok!');

    }

}

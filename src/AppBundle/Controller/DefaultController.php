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
     * @Route("/", name="homepage")
     * @Route("/{id}", name="edit_article", requirements={"id": "\d+"})
     */
    public function indexAction(Request $request, Article $article = null)
    {
        if (null == $article) {
            $article = new Article();
            $article->setDate(new \DateTime());
        }

        $form = $this->createForm(ArticleType::class, $article);

        $articles = $this->getDoctrine()->getRepository('AppBundle:Article')->findAll();

        return $this->render(
            'default/index.html.twig',
            [
                'form' => $form->createView(),
                'articles' => $articles,
            ]
        );
    }

    /**
     * @Route("/ajax/add", name="create_edit_article", options={"expose"=true})
     */
    public function createOrEditAction(Request $request)
    {
        $article = $this->getDoctrine()->getRepository('AppBundle:Article')->findOneByTitle($request->get('title'));

        if (is_null($article)) {
            $article = new Article();
            $article->setTitle($request->get('title'));
            $article->setContent($request->get('content'));
            $article->setDate(new \DateTime());
        }

        die(dump($article));

        if(null !== $request->get('tags')) {
            foreach ($request->get('tags') as $key => $tag) {
                $tagGetted = $this->getDoctrine()->getRepository('AppBundle:Tag')->findOneByTitle($tag['tag']);
                if(is_null($tagGetted)) {
                    $tagGetted = new Tag();
                    $tagGetted->setTitle($tag['tag']);
                }
                $article->addTag($tagGetted);
            }
        }

        $this->get('article.manager')->save($article);

        return new Response('enregistrement ok!');

    }
}

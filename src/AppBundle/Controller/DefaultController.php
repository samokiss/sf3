<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Form\ArticleType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
        $em = $this->getDoctrine()->getEntityManager();

        $form = $this->createForm(ArticleType::class, $article);

        $articles = $this->getDoctrine()->getRepository('AppBundle:Article')->findAll();

        $form->handleRequest($request);

        if ($form->isValid()) {

            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render(
            'default/index.html.twig',
            [
                'form' => $form->createView(),
                'articles' => $articles,
            ]
        );
    }

}

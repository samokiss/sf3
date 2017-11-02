<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Tag;
use AppBundle\Form\TagType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TagController extends Controller
{
    /**
     * @Route("/taglist", name="get_tag", options={"expose"=true})
     */
    public function getTagAction()
    {
        $tags = $this->getDoctrine()->getRepository('AppBundle:Tag')->findAll();
        $data = [];
        foreach ($tags as $keys => $tag) {
            $data[$tag->getTitle()] = null;
        }
        return new Response(json_encode($data));
    }
    
    /**
     * @Route("/tag-all", name="tag_all")
     * @Route("/tag/edit/{tag}", name="tag_edit")
     */
    public function editAction(Tag $tag = null, Request $request)
    {
        $tags = $this->getDoctrine()->getRepository('AppBundle:Tag')->findAll();
        $articleTags = $this->get('tag.manager')->getArticleByTag();


        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $this->get('tag.manager')->save($tag);
        }
        
        return $this->render('tag/edit.html.twig', [
            'form' => $form->createView(),
            'articleTags' => $articleTags,
            'tags' => $tags,
        ]);
    }

    /**
     * @Route("/tag/delete/{tag}", name="tag_delete")
     */
    public function deleteAction(Tag $tag)
    {
        $this->get('tag.manager')->remove($tag);

        return $this->redirectToRoute('tag_all');
    }

}

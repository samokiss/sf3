<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TagController extends Controller
{
    /**
     * @Route("/tag/list", name="get_tag", options={"expose"=true})
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

}

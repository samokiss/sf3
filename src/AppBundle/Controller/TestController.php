<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class TestController extends Controller
{
    /**
     * @Route("/test", name="test")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function testAction()
    {
        return new Response("test");
    }
}

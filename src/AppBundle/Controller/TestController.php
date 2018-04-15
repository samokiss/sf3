<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TestController extends Controller
{
    /**
     * @Route("/test", name="test")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function testAction()
    {
        set_time_limit(0);
        ini_set('memory_limit', -1);
        $this->get('extract.test')->extractTestNode();


        return new Response("test");
    }

    /**
     * @Route("/test2", name="test2")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function test2Action()
    {
        $a = $this->getDoctrine()->getRepository('AppBundle:Article')->findByLike(['content' => addslashes('<p>2.3 | 2.4 | 2.5 | 2.6 | 2.7 | 2.8 | 3.0 | 3.1 | Multiple choices</p><h5>Connecting Listeners - Priority</h5><p><strong>What\'s true about the addListener()\'s $priority value from Symfony\Component\EventDispatcher\EventDIspatcherInterface?</strong></p>')]);
        return new Response("test");
    }


    public  function contact()
    {
        $this->createForm(); // utilise le service form.factory
    }

}

<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FilesystemController extends Controller
{
    /**
     * @Route("/filesystem", name="filesystem")
     */
    public function filesystemAction(Request $request)
    {
        $fs = new Filesystem();

        $finder = new Finder();
        $finder->files()->in(__DIR__);
        $testDir = $this->get('kernel')->getRootDir().'/../web/tmp/random/dir/';

        try {
            /*string*/
//            $fs->mkdir($testDir.'string', 0600);
//            /*array*/
//            $fs->mkdir([
//                $testDir.'array',
//                $testDir.'array2',
//                ]);
            /*traversable*/
            $fs->copy($testDir.'array2',$testDir.'testC');
            /*exists*/
            dump('exist : ' . $fs->exists($testDir.'array2'));
        } catch (IOExceptionInterface $e) {
            echo "An error occurred while creating your directory at ".$e->getPath();
        }


        return new Response('ok');
    }
}

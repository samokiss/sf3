<?php

namespace AppBundle\Services;

use AppBundle\Entity\Article;
use AppBundle\Entity\Tag;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\Kernel;

class ExtractTest
{
    /**
     * @var Kernel
     */
    private $kernel;
    /**
     * @var EntityManager
     */
    private $em;

    private $i;

    public function __construct(Kernel $kernel, EntityManager $em)
    {
        $this->kernel = $kernel;
        $this->em = $em;
        $this->i = 0;
    }

    public function extractTestNode()
    {
        $finder = new Finder();
        $finder->files()->in($this->kernel->getRootDir() . '/../web/symfony_test/')->name('4.htm');

        $nodes = $tests = [];
        foreach ($finder as $file) {
            $crawler = new Crawler($file->getContents());
            $crawler = $crawler->filterXPath('//div[contains(@class, "ui card")]');
            foreach ($crawler as $domElement) {
                $nodes[] = $domElement->ownerDocument->saveHTML($domElement);
            }

            foreach ($nodes as $question => $node) {
                $crawler = new Crawler($node);
                $tests[$question]['tag'] = $crawler->filterXPath('//div[contains(@class, "ui card")]//div[contains(@class, "header")]')
                    ->extract(array('_text'));
                $tests[$question]['version'] = $crawler->filterXPath('//div[contains(@class, "ui card")]//span[contains(@class, "ui label")]')
                    ->extract(array('_text'));
                $tests[$question]['title'] = $crawler->filterXPath('//div[contains(@class, "ui card")]//h2')->extract(array('_text'));
                $tests[$question]['question'] = $crawler->filterXPath('//div[contains(@class, "ui card")]//p')->extract(array('_text'));
                $tests[$question]['answer'] = $crawler->filterXPath('//div[contains(@class, "ui card")]//li')->extract(array('_text'));
                $tests[$question]['code'] = $crawler->filterXPath('//div[contains(@class, "ui card")]//pre')->extract(array('_text'));
                $tests[$question]['doc'] = $crawler->filterXPath('//div[contains(@class, "ui card")]//a')->extract(array('_text'));

                $tests[$question] = $this->arrayPostTreatment($tests[$question]);
            }

        }

//        die(dump($tests));

        $this->saveTestQuestion($tests);
    }

    /**
     * @param array $tests
     * @return array
     */
    public function arrayPostTreatment($test)
    {
        $test['tag'] = trim($test['tag'][0]);
        $test['type'] = end($test['version']);
        array_pop($test['version']);
        $test['title'] = array_shift($test['title']);
        $test['question'] = array_shift($test['question']);
        $test['answer'] = array_unique($test['answer']);
        array_pop($test['doc']);
        $code = array_count_values($test['code']);

        if (!empty($code) && $code[key($code)] == 2) {
            $test['code'] = null;
        } elseif (!empty($code) && !array_search(2, $code)) {
            $test['code'] = implode(' and ', array_keys($code));
        } else {
            $test['code'] = array_shift($test['code']);
        }

        $test['full_title'] = trim(str_replace(' > ', ' ', substr($test['tag'], 3) . ' : ' . $test['title']));

        foreach ($test['answer'] as $key => $answer) {
            if (filter_var($answer, FILTER_VALIDATE_URL)) {
                unset($test['answer'][$key]);
            }
        }

        return $this->arrayToHtml($test);
    }

    /**
     * @param array $test
     * @return array
     */
    public function arrayToHtml($test)
    {
        $html = "";

        foreach ($test['answer'] as $key => $answer) {
            $test['answer'][$key] = '<li><input type="checkbox" id="' . $this->i . '_checkbox" value="' . $this->i . '_checkbox"/>';
            $test['answer'][$key] .= '<label for="' . $this->i . '_checkbox">' . $answer . '</label></li>';
            $this->i++;
        }


        $html .= '<p>' . implode(' | ', $test['version']) . ' | ' . $test['type'] . '</p>';
        $html .= '<h5>' . $test['title'] . '</h5>';
        $html .= '<p><strong>' . $test['question'] . '</strong></p>';
        if ($test['code'] !== null && strpos($test['question'], $test['code']) === false) {
            $test['code'] = htmlentities($test['code']);
            $html .= '<p><pre><code>' . $test['code'] . '</code></pre></p>';
        }
        $html .= '<ul>' . implode('', $test['answer']) . '</ul>';
        if (is_array($test['doc'])) {
            foreach ($test['doc'] as $key => $doc) {
                $test['doc'][$key] = '<li><a href="' . $doc . '">' . $doc . '</a></li>';
            }
            $html .= '<ul>' . implode('', $test['doc']) . '</ul>';
        }


        return [
            'html' => $html,
            'test' => $test
        ];
    }

    /**
     * @param array $tests
     */
    public function saveTestQuestion($tests)
    {
        foreach ($tests as $test) {
            $question = substr($test['html'], 0, strpos($test['html'], '<ul>'));
            $article = $this->em->getRepository('AppBundle:Article')->findOneBy(['title' => $test['test']['question']]);
            $html = $this->em->getRepository('AppBundle:Article')->findByLike(['content' => addslashes($question)]);

            if (is_null($article) && empty($html)) {
                $fullTitle = $this->em->getRepository('AppBundle:Article')->findByLike(['title' => $test['test']['full_title']]);
                if (count($fullTitle) > 0) {
                    $test['test']['full_title'] = $test['test']['full_title'] . ' ' . (count($fullTitle));
                }
                $article = new Article();
                $article->setTitle(trim($test['test']['full_title']));
                $article->setContent($test['html']);
                $article->setDate(new \DateTime());

                $tagTitle = trim(substr($test['test']['tag'], 3));
                $tag = $this->em->getRepository('AppBundle:Tag')->findOneBy(['title' => $tagTitle]);
                $tagCertif = $this->em->getRepository('AppBundle:Tag')->findOneBy(['title' => 'Certification']);
                if (is_null($tag)) {
                    $tag = new Tag();
                    $tag->setTitle($tagTitle);
                }
                $article->addTag($tag);
                $article->addTag($tagCertif);
                $this->em->persist($article);
                $this->em->flush();
            }
        }
    }
}
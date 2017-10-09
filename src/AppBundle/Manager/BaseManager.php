<?php
/**
 * Created by PhpStorm.
 * User: samuelgomis
 * Date: 25/08/2017
 * Time: 13:47.
 */

namespace AppBundle\Manager;

use Doctrine\ORM\EntityManager;

abstract class BaseManager
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var string
     */
    protected $className;

    /**
     * @param EntityManager $em
     * @param $className
     */
    public function __construct(EntityManager $em, string $className)
    {
        $this->em = $em;
        $this->className = $className;
    }

    public function save($entity)
    {
        $this->em->getRepository($this->className);
        $this->em->persist($entity);
        $this->em->flush();
    }
}

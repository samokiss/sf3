<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 17-11-27
 * Time: 1:20 PM
 */

namespace AppBundle\CompilerPass;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\VarDumper\VarDumper;
use Twig\Environment;

class DemoPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
//        if (!$container->hasDefinition('twig')) {
//            return;
//        }
//
//        $definition = $container->getDefinition('twig');
////        $definition->addMethodCall('addExtension');
//        VarDumper::dump($container->findTaggedServiceIds('twig.extension'));
//        VarDumper::dump($container->findTaggedServiceIds('kernel.event_listener'));
//
//        $def = new Definition();
//        $def->setClass(Environment::class);
//
//        //on peut mettre tout nos call
//        $container->setDefinition('demo',$definition);
//        /** on enregistre cette defintion, utilie pour ajoute un service complementaire, sans avoir besoin
//         * d'aller ecrire dans un ficher de coinfiguration
//         */

    }
}
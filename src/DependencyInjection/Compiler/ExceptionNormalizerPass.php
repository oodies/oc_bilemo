<?php
/**
 * This file is part of oc_bilemo project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/05
 */

namespace App\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class ExceptionNormalizerPass
 *
 * @package App\DependencyInjection\Compiler
 */
class ExceptionNormalizerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     *
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    public function process(ContainerBuilder $container)
    {
        $exceptionListenerDefinition = $container->findDefinition('app.exception_subscriber');

        $normalizers = $container->findTaggedServiceIds('app.normalizer');

        foreach ($normalizers as $id => $tags) {
            $exceptionListenerDefinition->addMethodCall('addNormalizer', [new Reference($id)]);
        }
    }
}

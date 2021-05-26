<?php

declare(strict_types=1);

namespace Imaximius\WorkflowBundle\DependencyInjection\Compiler;

use Imaximius\WorkflowBundle\EventListener\WorkflowStateListener;
use Imaximius\WorkflowBundle\Metadata\Resource\Factory\WorkflowOperationResourceMetadataFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class WorkflowPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $registry = $container->getDefinition('workflow.registry');
        $factory = $container->getDefinition(WorkflowOperationResourceMetadataFactory::class);
        $listener = $container->getDefinition(WorkflowStateListener::class);
        $arguments = [];

        foreach ($registry->getMethodCalls() as $methodCall) {
            $supportsStrategy = $methodCall[1][1];
            $arguments[] = $supportsStrategy->getArguments()[0];
        }

        $factory->setArgument(1, $arguments);
        $listener->setArgument(1, $arguments);
    }
}

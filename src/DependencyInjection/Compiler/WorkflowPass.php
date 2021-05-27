<?php

declare(strict_types=1);

namespace Imaximius\WorkflowBundle\DependencyInjection\Compiler;

use Imaximius\WorkflowBundle\EventListener\WorkflowStateListener;
use Imaximius\WorkflowBundle\Metadata\Resource\Factory\WorkflowOperationResourceMetadataFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use function GuzzleHttp\Psr7\str;

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
        $workflows = [];
        foreach ($registry->getMethodCalls() as $methodCall) {
            /** @var Reference $supportsWorkflow */
            $supportsWorkflow = $methodCall[1][0];
            $supportsStrategy = $methodCall[1][1];
            $arguments[] = $supportsStrategy->getArguments()[0];
            $workflows[$supportsStrategy->getArguments()[0]] = (string)$supportsWorkflow;
        }

        $factory->setArgument(1, $arguments);
        $listener->setArgument(
            2,
            [
                'supportsWorkflow' => $arguments,
                'configs' => $workflows,
            ]
        );
    }
}

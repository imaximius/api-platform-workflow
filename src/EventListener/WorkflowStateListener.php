<?php

declare(strict_types=1);

namespace Imaximius\WorkflowBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Workflow\Registry;

class WorkflowStateListener implements EventSubscriber
{
    const DEFAULT_FIELD_NAME='state';

    /**
     * WorkflowStateListener constructor.
     * @param Registry $workflows
     * @param array $supportsWorkflow
     */
    public function __construct(
        private Registry $workflows,
        private ContainerInterface $container,
        private array $arguments = []
    ) {
    }

    /**
     * Returns subscribed events.
     *
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [Events::preUpdate];
    }

    /**
     * Update User in the Index.
     */
    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $class = $args->getObject();
        if (!in_array(get_class($class), $this->arguments['supportsWorkflow'], true)) {
            return;
        }

        $stateField = $this->getWorkflowName($class);

        var_dump($stateField);

        if ($args->hasChangedField($stateField)) {
            $state = $class->{'get'.ucfirst($stateField)}();
            $class->{'set'.ucfirst($stateField)}($args->getOldValue($stateField));

            $this->workflows->get($class)->apply($class, $state);
        }
    }

    private function getWorkflowName($class): string
    {
        $workflowName = explode('.', $this->arguments['configs'][get_class($class)]);
        $workflowParameters = $this->container->getParameter('workflows');

        return $workflowParameters[end($workflowName)]['marking_store']['property'] ?? self::DEFAULT_FIELD_NAME;
    }
}

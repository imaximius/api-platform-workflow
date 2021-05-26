<?php

declare(strict_types=1);

namespace Imaximius\WorkflowBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Workflow\Registry;

class WorkflowStateListener implements EventSubscriber
{
    /**
     * WorkflowStateListener constructor.
     * @param Registry $workflows
     * @param array $supportsWorkflow
     */
    public function __construct(
        private Registry $workflows,
        private array $supportsWorkflow = []
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
        if (!in_array(get_class($class), $this->supportsWorkflow, true)) {
            return;
        }

        if ($args->hasChangedField('state')) {
            $state = $class->getState();
            $class->setState($args->getOldValue('state'));

            $this->workflows->get($class)->apply($class, $state);
        }
    }
}

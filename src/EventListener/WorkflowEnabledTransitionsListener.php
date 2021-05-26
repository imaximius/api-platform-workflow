<?php

declare(strict_types=1);

namespace Imaximius\WorkflowBundle\EventListener;

use ApiPlatform\Core\Util\RequestAttributesExtractor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Workflow\Registry;

final class WorkflowEnabledTransitionsListener
{
    public function __construct(
        private SerializerInterface $serializer,
        private Registry $workflows)
    {
    }

    public function onKernelView(ViewEvent $event)
    {
        $request = $event->getRequest();

        $attributes = RequestAttributesExtractor::extractAttributes($request);

        if (!$request->isMethod(Request::METHOD_GET)
            || !($attributes = RequestAttributesExtractor::extractAttributes($request))
            || !isset($attributes['item_operation_name'])
            || 'state_get' !== $attributes['item_operation_name']
        ) {
            return;
        }

        $class = $request->attributes->get('data');
        $workflow = $this->workflows->get($class);
        $event->setResponse(new Response($this->serializer->serialize($workflow->getEnabledTransitions($class), 'json')));
    }
}

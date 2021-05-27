<?php

declare(strict_types=1);

namespace Imaximius\WorkflowBundle\Metadata\Resource\Factory;

use ApiPlatform\Core\Metadata\Resource\Factory\ResourceMetadataFactoryInterface;
use ApiPlatform\Core\Metadata\Resource\ResourceMetadata;

final class WorkflowOperationResourceMetadataFactory implements ResourceMetadataFactoryInterface
{
    public function __construct(
        private ResourceMetadataFactoryInterface $decorated,
        private array $supportsWorkflow = []
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function create(string $resourceClass): ResourceMetadata
    {
        $resourceMetadata = $this->decorated->create($resourceClass);

        if (!in_array($resourceClass, $this->supportsWorkflow, true)) {
            return $resourceMetadata;
        }

        $operations = $resourceMetadata->getItemOperations();

        $operations['state_get'] = [
            'method' => 'GET',
            '_path_suffix' => '/state',
            'stateless' => null,
        ];

        return $resourceMetadata->withItemOperations($operations);
    }
}

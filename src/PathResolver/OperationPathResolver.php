<?php

declare(strict_types=1);

namespace Imaximius\WorkflowBundle\PathResolver;

use ApiPlatform\Core\PathResolver\OperationPathResolverInterface;

final class OperationPathResolver implements OperationPathResolverInterface
{
    public function __construct(
        private OperationPathResolverInterface $decorated)
    {
    }

    public function resolveOperationPath(string $resourceShortName, array $operation, $operationType/*, string $operationName = null*/): string
    {
        $path = $this->decorated->resolveOperationPath($resourceShortName, $operation, $operationType);

        if (!isset($operation['_path_suffix'])) {
            return $path;
        }

        return str_replace('{id}', '{id}'.$operation['_path_suffix'], $path);
    }
}

<?php

namespace Imaximius\WorkflowBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

class ImaximiusWorkflowExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.yaml');
    }

    public function prepend(ContainerBuilder $container)
    {
        foreach ($container->getExtensions() as $name => $extension) {
            switch ($name) {
                case 'framework':
                    $container->setParameter('workflows', $this->findConfig($container->getExtensionConfig($name), 'workflows'));
                    break;
            }
        }
    }

    /**
     * Search for config values
     *
     * @param array $array
     * @param string $needle
     * @return false|array
     */
    private function findConfig($array, $needle): false|array
    {
        foreach ($array as $key => $value) {
            if ($key == $needle) {
                return $value;
            }

            if (is_array($value)) {
                if( ($result = $this->findConfig($value, $needle)) !== false) {
                    return $result;
                }
            }
        }

        return false;
    }
}

<?php

namespace CQRS;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    private function configureRoutes(RoutingConfigurator $routes): void
    {
        $configDir = $this->getConfigDir();

        $routes->import($configDir.'/{routes}/'.$this->environment.'/*.{php,yaml}');
        $routes->import($configDir.'/{routes}/*.{php,yaml}');

        if (is_file($configDir.'/routes.yaml')) {
            $routes->import($configDir.'/routes.yaml');
        } else {
            $routes->import($configDir.'/{routes}.php');
        }

        if (false !== ($fileName = (new \ReflectionObject($this))->getFileName())) {
            $routes->import($fileName, 'annotation');
        }

        $this->prepareModuleRoutes($routes);
    }
    private function prepareModuleRoutes(RoutingConfigurator $routes)
    {
        $finder = new Finder();
        $finder->files()->in($this->getProjectDir().'/src/*/Routes')->name('*.yaml');
        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                $routes->import($file->getRealPath());
            }
        }
    }

    private function configureContainer(ContainerConfigurator $container, LoaderInterface $loader, ContainerBuilder $builder): void
    {
        $configDir = $this->getConfigDir();

        $container->import($configDir.'/{packages}/*.{php,yaml}');
        $container->import($configDir.'/{packages}/'.$this->environment.'/*.{php,yaml}');

        if (is_file($configDir.'/services.yaml')) {
            $container->import($configDir.'/services.yaml');
            $container->import($configDir.'/{services}_'.$this->environment.'.yaml');
        } else {
            $container->import($configDir.'/{services}.php');
        }

        $this->prepareModuleConfig($container);
    }

    private function prepareModuleConfig(ContainerConfigurator $container)
    {
        $finder = new Finder();
        $finder->files()->in($this->getProjectDir().'/src/*/Config')->name('*.yaml');
        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                $container->import($file->getRealPath());
            }
        }
    }
}

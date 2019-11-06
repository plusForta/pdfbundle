<?php


namespace PlusForta\PdfBundle\DependencyInjection;


use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class PlusFortaPdfExtension extends Extension
{

    /**
     * Loads a specific configuration.
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');


        $configuration = $this->getConfiguration($configs, $container);
        /** @psalm-suppress PossiblyNullArgument */
        $config = $this->processConfiguration($configuration, $configs);

        $definition = $container->getDefinition('PlusForta\PdfBundle\Pdf\MpdfRenderer');
        $definition->setArgument(1, $this->getMode($config));

        $definition = $container->getDefinition('html.renderer');
        $definition->setArgument(2, $this->getTemplateDirectoryPrefix($config));
        $definition->setArgument(3, $this->getFileExtension($config));

    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return 'plusforta_pdf';
    }

    private function getMode(array $config)
    {
        return $config['direct_mode'];
    }

    private function getTemplateDirectoryPrefix(array $config)
    {
        $directory = $config['template_directory'];
        if ($directory === false) {
            return '';
        }

        $templateDir = rtrim($directory, '/');
        return "$templateDir/";
    }

    private function getFileExtension(array $config)
    {
        $extension = $config['file_extension'];
        if ($extension === null) {
            return '';
        }

        $fileExtension = trim($extension, '.');
        return ".$fileExtension";
    }
}
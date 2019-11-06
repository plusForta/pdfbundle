<?php


namespace PlusForta\PdfBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('plusforta_pdf');
        /** @psalm-suppress PossiblyUndefinedMethod */
        $treeBuilder->getRootNode()->children()
                ->booleanNode('direct_mode')->defaultTrue()
                    ->info("direct_mode = true pipes the html directly to the pdf renderer\ndirect_mode = false uses experimental meta tags")
                ->end()
                ->enumNode('pdf_renderer')->values(['mpdf'])->defaultValue('mpdf')->end()
                ->enumNode('html_renderer')->values(['twig'])->defaultValue('mpdf')->end()
                ->scalarNode('template_directory')->info('template directory')->defaultNull()->end()
                ->scalarNode('file_extension')->info('custom file extension')->defaultNull()->end()
            ->end()
        ->end();

        return $treeBuilder;
    }


}
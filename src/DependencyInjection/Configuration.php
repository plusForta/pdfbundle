<?php


namespace PlusForta\PdfBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    protected const FONT_REGULAR = 'R';
    protected const FONT_BOLD = 'B';
    protected const FONT_ITALIC = 'I';
    protected const FONT_BOLD_ITALIC = 'BI';

    protected const FONT_TYPES = [
        self::FONT_REGULAR,
        self::FONT_BOLD,
        self::FONT_BOLD_ITALIC,
        self::FONT_ITALIC,
    ];

    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder(): TreeBuilder
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
                ->arrayNode('pdf')->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('fonts_directory')->defaultNull()->end()
                        ->arrayNode('fonts')->arrayPrototype()->info('font array')
                            ->children()
                                ->scalarNode('fontname')->defaultNull()->end()
                                ->scalarNode('filename')->defaultNull()->end()
                                ->enumNode('type')->values(self::FONT_TYPES)->defaultValue(self::FONT_REGULAR)
                                    ->info("R - Regular\nB - Bold\nI - Italic\nBI - Bold Italic")
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }


}
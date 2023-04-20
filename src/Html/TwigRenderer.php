<?php


namespace PlusForta\PdfBundle\Html;

use Psr\Log\LoggerInterface;
use Twig\Environment;

class TwigRenderer implements TemplateEngineInterface
{
    public function __construct(
        private LoggerInterface $logger,
        private Environment $twig,
        private string $templateDirPrefix,
        private string $fileExtension
    )
    {
    }

    /**
     * @param string $templateName
     * @param array $context
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function render(string $templateName, $context): string
    {
        return $this->twig->render($this->templateDirPrefix . $templateName . $this->fileExtension, $context);
    }
}
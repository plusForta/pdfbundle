<?php


namespace PlusForta\PdfBundle\Html;

use Psr\Log\LoggerInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

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
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function render(string $templateName, array $context): string
    {
        return $this->twig->render($this->templateDirPrefix . $templateName . $this->fileExtension, $context);
    }
}
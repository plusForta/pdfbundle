<?php


namespace PlusForta\PdfBundle\Html;

use Psr\Log\LoggerInterface;
use Twig\Environment;

class TwigRenderer implements TemplateEngineInterface
{

    /** @var Environment */
    private $twig;

    /** @var LoggerInterface */
    private $logger;
    /**
     * @var string
     */
    private $templateDirPrefix;
    /**
     * @var string
     */
    private $fileExtension;

    public function __construct(LoggerInterface $logger, Environment $twig, string $templateDirPrefix, string $fileExtension)
    {
        $this->twig = $twig;
        $this->logger = $logger;
        $this->templateDirPrefix = $templateDirPrefix;
        $this->fileExtension = $fileExtension;
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
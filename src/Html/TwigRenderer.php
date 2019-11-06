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

    public function render(string $templateName, $context)
    {
        return $this->twig->render($this->templateDirPrefix . $templateName . $this->fileExtension, $context);
    }
}
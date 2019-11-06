<?php


namespace PlusForta\PdfBundle;


use PlusForta\PdfBundle\Html\TemplateEngineInterface;
use PlusForta\PdfBundle\Pdf\PdfRendererInterface;
use Psr\Log\LoggerInterface;
use Twig\Environment;

class PlusFortaPdfRenderer
{
    
    /** @var PdfRendererInterface */
    private $pdf;
    
    /** @var TemplateEngineInterface */
    private $templateEngine;

    public function __construct(PdfRendererInterface $pdf, TemplateEngineInterface $templateEngine)
    {
        $this->pdf = $pdf;
        $this->templateEngine = $templateEngine;
    }

    public function render(string $templateName, array $context)
    {
        $template = $this->templateEngine->render($templateName, $context);
        return $this->pdf->render($template);
    }

}
<?php


namespace PlusForta\PdfBundle;


use PlusForta\PdfBundle\Html\TemplateEngineInterface;
use PlusForta\PdfBundle\Pdf\PdfRendererInterface;

class PlusFortaPdfRenderer
{
    
    /** @var PdfRendererInterface */
    private $pdf;
    
    /** @var TemplateEngineInterface */
    private $templateEngine;

    /** @var string[] */
    private $prependedPdfs = [];

    /** @var string[] */
    private $appendedPdfs = [];

    public function __construct(PdfRendererInterface $pdf, TemplateEngineInterface $templateEngine)
    {
        $this->pdf = $pdf;
        $this->templateEngine = $templateEngine;
    }

    public function prependPdf(array $files): void
    {
        $this->prependedPdfs = $files;
    }

    public function appendPdf(array $files): void
    {
        $this->appendedPdfs = $files;
    }

    public function render(string $templateName, array $context):string
    {
        $template = $this->renderHtml($templateName, $context);
        # Find all stylesheets in the rendered HTML
        $stylesheets = [];
        preg_match('/<link rel="stylesheet" href="([^"]+)"/i', $template, $stylesheets);
        if (isset($stylesheets[1])) {
            syslog(LOG_INFO, sprintf("Found stylesheet: %s", $stylesheets[1]));
        }
        $this->pdf->prependPdf($this->prependedPdfs);
        $this->pdf->appendPdf($this->appendedPdfs);
        return $this->pdf->render($template);
    }

    public function renderHtml(string $templateName, array $context): string
    {
        return $this->templateEngine->render($templateName, $context);
    }

}
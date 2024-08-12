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
        $this->pdf->prependPdf($this->prependedPdfs);
        $this->pdf->appendPdf($this->appendedPdfs);
        return $this->pdf->render($template);
    }

    public function renderHtml(string $templateName, array $context): string
    {
        return $this->templateEngine->render($templateName, $context);
    }

}
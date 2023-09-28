<?php


namespace PlusForta\PdfBundle;


use PlusForta\PdfBundle\Html\TemplateEngineInterface;
use PlusForta\PdfBundle\Pdf\PdfRendererInterface;

class PlusFortaPdfRenderer
{
    /** @var string[] */
    private array $prependedPdfs = [];

    /** @var string[] */
    private array $appendedPdfs = [];

    public function __construct(
        private PdfRendererInterface $pdf,
        private TemplateEngineInterface $templateEngine
    )
    {
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
<?php


namespace PlusForta\PdfBundle;


use PlusForta\PdfBundle\Html\TemplateEngineInterface;
use PlusForta\PdfBundle\Pdf\PdfRendererInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

class PlusFortaPdfRenderer implements LoggerAwareInterface
{
    use LoggerAwareTrait;
    
    /** @var PdfRendererInterface */
    private $pdfRenderer;
    
    /** @var TemplateEngineInterface */
    private $templateEngine;

    /** @var string[] */
    private $prependedPdfs = [];

    /** @var string[] */
    private $appendedPdfs = [];

    public function __construct(PdfRendererInterface $pdfRenderer, TemplateEngineInterface $templateEngine)
    {
        $this->pdfRenderer = $pdfRenderer;
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

    /**
     * render the PDF from the given template and context
     */
    public function render(string $templateName, array $context):string
    {
        // get the HTML source for the filled out template
        $html = $this->renderHtml($templateName, $context);
        // prepend and append the PDFs
        $this->pdfRenderer->prependPdf($this->prependedPdfs);
        $this->pdfRenderer->appendPdf($this->appendedPdfs);

        // render the PDF(with prepended PDFs) and return it
        return $this->pdfRenderer->render($html);
    }

    /**
     * create an HTML document from the twig templates
     **/
    public function renderHtml(string $templateName, array $context): string
    {
        $filledHtml = $this->templateEngine->render($templateName, $context);
        # find all stylesheets in the HTML
        # <link rel="stylesheet" href="http://web/build/aarealPdf.css">
        $matches = [];
        if (preg_match('/<link rel="stylesheet" href="([^"]+)">/', $filledHtml, $matches)) {
            if (count($matches) > 1) {
                $this->logger->info(sprintf('Found stylesheet: %s', $matches[1]));
            }
        }

        return $filledHtml;
    }
}
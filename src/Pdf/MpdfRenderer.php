<?php


namespace PlusForta\PdfBundle\Pdf;


use Mpdf\HTMLParserMode;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use PlusForta\PdfBundle\Pdf\DocumentModel\Document;
use PlusForta\PdfBundle\Pdf\DocumentModel\Page;
use PlusForta\PdfBundle\Pdf\DocumentModel\Pagegroup;
use Psr\Log\LoggerInterface;

class MpdfRenderer implements PdfRendererInterface
{

    /** @var Mpdf */
    private $pdf;

    /** @var LoggerInterface */
    private $logger;

    /** @var bool */
    private $directMode;

    public function __construct(LoggerInterface $logger, bool $directMode)
    {
        $logger->debug('direct_mode', ['direct_mode' => $directMode]);
        $this->pdf = new Mpdf();
        $this->logger = $logger;
        $this->directMode = $directMode;
    }


    /** @throws \Mpdf\MpdfException */
    public function render(string $html)
    {
        if ($this->directMode) {
            $this->pdf->WriteHTML($html);
            return $this->pdf->Output();
        }

        $document = new Document($html);
        $this->renderChildren($document->getChildren());

        $this->pdf->WriteHTML($html);
        return $this->pdf->Output();
    }

    /**
     * @param Page $page
     * @throws \Mpdf\MpdfException
     */
    protected function renderPage(Page $page): void
    {
        $this->logger->debug('renderPage', [
            'header' => $page->getHeader(),
            'footer' => $page->getFooter(),
            'style' => $page->getStyle(),
            'content' => $page->toHtml(),
        ]);
        $this->pdf->WriteHTML($page->getStyle(), HTMLParserMode::HEADER_CSS);
        $this->pdf->SetHTMLHeader($page->getHeader());
        $this->pdf->SetHTMLFooter($page->getFooter());
        $this->pdf->addPage();
        $this->pdf->WriteHTML($page->toHtml());
    }

    /**
     * @param array $children
     * @throws \Mpdf\MpdfException
     */
    protected function renderChildren(array $children): void
    {
        foreach ($children as $child) {
            switch (get_class($child)) {
                case Page::class:
                    /** @var Page $child */
                    $this->renderPage($child);
                    break;
                case Pagegroup::class:
                    $this->renderChildren($child->getChildren());
                    break;
            }
        }
    }
}
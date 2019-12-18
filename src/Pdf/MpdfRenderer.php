<?php


namespace PlusForta\PdfBundle\Pdf;


use Mpdf\Config\ConfigVariables;
use Mpdf\HTMLParserMode;
use Mpdf\Mpdf;
use Mpdf\Config\FontVariables;
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

    public function __construct(LoggerInterface $logger, bool $directMode, array $customFonts = null,string $customFontDirectory = null)
    {
        $logger->debug('direct_mode', ['direct_mode' => $directMode]);

        $config = [];
        if ($customFonts) {
            $config['fontdata'] = $this->getFonts($customFonts);
        }

        if ($customFontDirectory) {
            $config['fontDir'] = $this->getFontDirectories($customFontDirectory);
        }

        $this->pdf = new Mpdf($config);
        $this->logger = $logger;
        $this->directMode = $directMode;
    }

    private function getFonts(array $customFonts): array
    {
        $fontVars = new FontVariables();
        $fontDefaults = $fontVars->getDefaults();
        return array_merge($fontDefaults['fontdata'], $customFonts);
    }

    private function getFontDirectories(string $customDirectory): array
    {
        $commonVars = new ConfigVariables();
        $commonDefaults = $commonVars->getDefaults();
        $commonDefaults['fontDir'][] = $customDirectory;
        return $commonDefaults['fontDir'];
    }


    /** @throws \Mpdf\MpdfException */
    public function render(string $html): string
    {
        if ($this->directMode) {
            $this->pdf->WriteHTML($html);
            return $this->pdf->Output();
        }

        $document = new Document($html);
        $this->renderChildren($document->getChildren());

        $this->pdf->WriteHTML($html);
        return $this->pdf->Output(Destination::STRING_RETURN);
    }

    /**
     * @param Page $page
     * @throws \Mpdf\MpdfException
     */
    protected function renderPage(Page $page): void
    {
        $style = $page->getStyle();
        $header = $page->getHeader();
        $footer = $page->getFooter();
        $this->logger->debug('renderPage', [
            'header' => $header,
            'footer' => $footer,
            'style' => $style,
            'content' => $page->getHtml(),
        ]);
        if ($style !== null) {
            $this->pdf->WriteHTML($style, HTMLParserMode::HEADER_CSS);
        }

        if ($header !== null) {
            $this->pdf->SetHTMLHeader($header);
        }

        if ($footer !== null) {
            $this->pdf->SetHTMLFooter($footer);
        }

        $this->pdf->addPage();
        $this->pdf->WriteHTML($page->getHtml());
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
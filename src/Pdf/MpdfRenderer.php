<?php


namespace PlusForta\PdfBundle\Pdf;


use Mpdf\Config\ConfigVariables;
use Mpdf\Mpdf;
use Mpdf\Config\FontVariables;
use Mpdf\MpdfException;
use Mpdf\Output\Destination;
use Psr\Log\LoggerInterface;

class MpdfRenderer implements PdfRendererInterface
{
    private Mpdf $pdf;

    private LoggerInterface $logger;

    private bool $directMode;

    /** @var string[] */
    private array $prependedPdfs = [];

    /** @var string[] */
    private array $appendedPdfs = [];

    /** @var array */
    private array $config;

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

        $this->config = $config;
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


    /** @throws MpdfException */
    public function render(string $html): string
    {
        $this->pdf = new Mpdf($this->config);
        $this->prependPages();
        $this->pdf->WriteHTML($html);
        $this->appendPages();

        return $this->pdf->Output('', Destination::STRING_RETURN);
    }

    public function prependPdf(array $files): void
    {
        $this->prependedPdfs = $files;
    }

    public function appendPdf(array $files): void
    {
        $this->appendedPdfs = $files;
    }

    private function prependPages(): void
    {
        if (empty($this->prependedPdfs)) {
            return;
        }

        foreach ($this->prependedPdfs as $file) {
            $this->addPages($file);
        }

        $this->pdf->AddPage();
    }

    private function appendPages(): void
    {
        foreach ($this->appendedPdfs as $file) {
            $this->addPages($file);
        }
    }

    private function addPages(string $file): void
    {
        $pagecount = $this->pdf->SetSourceFile($file);
        for ($pageNum = 1; $pageNum <= $pagecount; $pageNum++) {
            $importedPage = $this->pdf->ImportPage($pageNum);
            $this->pdf->AddPage();
            $this->pdf->UseTemplate($importedPage);
        }
    }
}
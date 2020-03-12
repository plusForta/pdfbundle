<?php


namespace PlusForta\PdfBundle\Pdf;


use Mpdf\Config\ConfigVariables;
use Mpdf\Mpdf;
use Mpdf\Config\FontVariables;
use Mpdf\Output\Destination;
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
        $this->pdf->WriteHTML($html);
        return $this->pdf->Output('', Destination::STRING_RETURN);
    }

}
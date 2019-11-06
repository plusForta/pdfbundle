<?php


namespace PlusForta\PdfBundle\Pdf\DocumentModel;


use Symfony\Component\DomCrawler\Crawler;

abstract class ContainerElement implements HtmlElement
{
    public const ELEMENT_NAME = 'no name given';

    const PDF_HEADER = 'pdf-header';

    const PDF_FOOTER = 'pdf-footer';

    const PDF_STYLE = 'style';

    const ALL = '*';

    protected const SUPPORTED_CHILDREN = [];

    /** @var array<HtmlElement>  */
    protected $children = [];

    /** @var string */
    protected $header;

    /** @var string */
    protected $footer;

    /** @var string */
    protected $html;

    /** @var string */
    protected $style;


    public function __construct(string $html, $header = null, $footer = null, string $style = null)
    {
        $this->html = $this->surroundWithSelf($html);
        $this->header = $this->extractHeader() ?? $header;
        $this->footer = $this->extractFooter() ?? $footer;
        $this->style = $this->extractStyle() ?? $style;
        $this->extractChildren();
    }

    public function getHeader()
    {
        return $this->header;
    }

    public function getFooter()
    {
        return $this->footer;
    }

    public function getStyle()
    {
        return $this->style;
    }

    public function getChildren(): array
    {
        return $this->children;
    }

    protected function extractHeader(): ?string
    {
        $crawler = $this->extract(self::PDF_HEADER);
        return $crawler->count() === 0 ? null : $crawler->html();
    }

    protected function extractFooter(): ?string
    {
        $crawler = $this->extract(self::PDF_FOOTER);
        return $crawler->count() === 0 ? null : $crawler->html();
    }

    protected function extractStyle(): ?string
    {
        $crawler = $this->extract(self::PDF_STYLE);
        return $crawler->count() === 0 ? null : $crawler->html();
    }

    protected function extractChildren(): void
    {

        $children = $this->extract(self::ALL);
        foreach ($children as $child) {
            $nodeName = $child->nodeName;
            if (in_array($nodeName, static::SUPPORTED_CHILDREN)) {
                $crawler = $this->extract($nodeName);

                switch ($nodeName) {
                    case Page::ELEMENT_NAME:
                        $this->children[] = new Page($crawler->html(), $this->header, $this->footer, $this->style);
                       break;

                    case Pagegroup::ELEMENT_NAME:
                        $this->children[] = new Pagegroup($crawler->html(), $this->header, $this->footer, $this->style);
                        break;
                }
            }
        }
    }

    protected function extract(string $elementName): Crawler
    {
        $crawler = new Crawler($this->html);
        return $crawler->filter(static::ELEMENT_NAME . ' > ' . $elementName);
    }

    /**
     * @param string $html
     * @return string
     */
    protected function surroundWithSelf(string $html): string
    {
        $elementName = static::ELEMENT_NAME;
        return "<$elementName>$html</$elementName>";
    }



}
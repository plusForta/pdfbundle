<?php


namespace PlusForta\PdfBundle\Pdf\DocumentModel;


class Page extends ContainerElement
{
    public const ELEMENT_NAME = 'pdf-page';

    public function toHtml(): string
    {
        return $this->html;
    }
}
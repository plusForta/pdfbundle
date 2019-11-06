<?php


namespace PlusForta\PdfBundle\Pdf\DocumentModel;


class Pagegroup extends ContainerElement
{
    public const ELEMENT_NAME = 'pdf-pagegroup';

    protected const SUPPORTED_CHILDREN = [Page::ELEMENT_NAME];

    public function toHtml(): string
    {
        // TODO: Implement toHtml() method.
    }
}
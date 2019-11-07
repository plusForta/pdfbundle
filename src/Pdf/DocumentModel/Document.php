<?php


namespace PlusForta\PdfBundle\Pdf\DocumentModel;


use Symfony\Component\DomCrawler\Crawler;

class Document extends ContainerElement
{

    public const ELEMENT_NAME = 'pdf-document';
    protected const SUPPORTED_CHILDREN = [Page::ELEMENT_NAME, Pagegroup::ELEMENT_NAME];

}
<?php


namespace PlusForta\PdfBundle\Pdf\DocumentModel;


interface HtmlElement
{
    public function toHtml(): string;
}
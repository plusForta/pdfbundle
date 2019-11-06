<?php


namespace PlusForta\PdfBundle\Pdf;


interface PdfRendererInterface
{

    public function render(string $template);
}
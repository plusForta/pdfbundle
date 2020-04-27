<?php


namespace PlusForta\PdfBundle\Pdf;


interface PdfRendererInterface
{

    public function prependPdf(array $files): void;


    public function appendPdf(array $files): void;

    public function render(string $template): string;

}
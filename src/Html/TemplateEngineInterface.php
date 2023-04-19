<?php


namespace PlusForta\PdfBundle\Html;


interface TemplateEngineInterface
{
    public function render(string $templateName, array $context): string;
}
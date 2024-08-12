# PlusForta PDF Bundle

## Installation

You can install this package directly with composer:

```shell
composer require plusforta/pdfbundle
```


## Versions

1.0.0 - Initial version, Symfony 5
1.1.0 - Added configuration as a setting
1.1.1 - composer upgrade
1.2.0 - Added return types
1.3.0 - README added, symfony upgraded to version 6
1.3.1 - Move to packagist - added LICENSE, translated README to english, moved symfony back to 5.4?
1.3.5 - Add syslog for asset link for generation of pdfs

## Usage

The service `PlusForta\PdfBundle\PlusFortaPdfRenderer` can be used via dependency injection (public service).
To render a template (currently only Twig and mPdf are supported):

```php
public function render(PlusForta\PdfBundle\PlusFortaPdfRenderer $pdf)
{
    $pdf->render($templateName, $context);
}
``` 

`$templateName` is the path to a Twig template (e.g. `pdf/application.html.twig`).
`$context` is the context that is passed on to the Twig template (e.g. `['firstName' => 'Max', 'name' => 'Mustermann']`).

## Settings 

The settings can be set in `plusforta_pdf.yaml`:

```
plusforta_pdf:
    template_directory: <relative path to templates>
    file_extension: <template extension>
    direct_mode: <true|false>
```

Currently, 3 main settings are possible:

- template_directory
- file_extension
- direct_mode


### template_directory

With template_directory you can specify a relative path where the templates (current twig templates) can be found.
The path is relative to the default_path of Twig.


### file_extension

With file_extension the file extension of the template files can be defined (e.g. html.twig).

With the combination of template_directory and file_extension, templates can be reduced to the name

instead of
```
$pdf->render('pdf/application.html.twig');
```

with the following settings

```
template_dir = pdf
file_extension = html.twig
```

then the following works identically:

```
$pdf->render('application'); 
```

### direct_mode

Currently direct mode has no effect.  

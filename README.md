# PlusForta PDF Bundle

## Installation

Zuerst muss das github-Repository zur composer.json hinzugefügt werden. 

**composer.json**
````json
"repositories": [
    ...
    {
        "type": "git",
        "url": "https://github.com/plusForta/pdfbundle.git"
    },
  ],
````


```shell
composer require plusforta/pdfbundle:dev-master
```

## Verwendung

Der Service `PlusForta\PdfBundle\PlusFortaPdfRenderer` kann per Dependency Injection verwendet werden (public Service).
Um ein Template zu rendern (aktuell wird nur Twig und mPdf unterstüzt):

```php
pubclic function render(PlusForta\PdfBundle\PlusFortaPdfRenderer $pdf)
{
    $pdf->render($templateName, $context);
}
``` 

`$templateName` ist der Pfad zu einem Twig Tamplate (bsp. `pdf/application.html.twig`).
`$context` ist der Context, der an das Twig Template weitergereicht wird (bsp. `['firstName' => 'Max', 'name' => 'Mustermann']`).

## Settings 

Die Settings können in `plusforta_pdf.yaml` gesetzt werden:

```
plusforta_pdf:
    template_directory: <relative path to templates>
    file_extension: <template extension>
    direct_mode: <true|false>
```

Aktuell sind 3 wesentliche Eintstellungen möglich: 

- template_directory
- file_extension
- direct_mode


### template_directory

Mit template_directory lässt sich ein relativer Pfad angeben, wo die Templates (aktuelle twig Templates) zu finden sind.
Der Pfad ist relativ zum default_path von Twig.


### file_extension

Mit file_extension läasst sich die Dateiendnung der Template Dateien festlegen (bsp. html.twig).

Mit der Kombination aus template_directory und file_extension können so Templates auf den Namen reduziert werden

```
statt
$pdf->render('pdf/application.html.twig');


template_dir = pdf
file_extension = html.twig

kann dann folgendes geschrieben werden
$pdf->render('application'); 

```

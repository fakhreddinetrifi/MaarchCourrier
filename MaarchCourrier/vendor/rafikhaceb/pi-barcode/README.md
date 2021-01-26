# PiBarCode

Composer ready for [pi_barcode](https://www.pitoo.com/codes-a-barres-p3).

A php script that allows you to generate images or insert barcodes into your web pages in the following formats :

- Code 128 (Set B et C) 
- Code 25 standard et code 25 Entrelacé 
- Code MSI 
- Code 39 
- Code 11 
- Code KIX 
- Code CMC7 
- Code PostFix 
- Codabar 
- Code UPC / EAN 8 et 13

Installation
-----

The best way to add the library to your project is using [composer](http://getcomposer.org).

	$ composer require rafikhaceb/pi-barcode

Usage
-----
```php
// initialisation
$bc = new PiBarCode();
  
// Code to generate
$bc->setCode('123456789012');

// Set code type : EAN, UPC, C39...
$bc->setType('EAN');

// Image size (height, width, quiet areas)
//    min Height = 15px
//    image width (can not be less than the space needed for the barcode)
//    quiet areas (mini = 10px) to the left and to the right of barcode
$bc->setSize(80, 150, 10);
  
// Text under the bars :
//    'AUTO' : displays the barcode value
//    '' : does not display text under the code
//    'text to display' : displays a free textu nder the bars
$bc->setText('AUTO');
  
// If called, this method disables code type printing (EAN, C128...)
$bc->hideCodeType();
  
// Colors of the Bars and the Background in the format '#rrggbb'
$bc->setColors('#123456', '#F9F9F9');

// File type: GIF or PNG (default)
$bc->setFiletype('PNG');
  
// Send the image to a file
$bc->writeBarcodeFile('barcode.png');

// Or send the image to the browser
// $bc->showBarcodeImage();
```
<?php

require_once 'tesseract-ocr-for-php-master/TesseractOCR/TesseractOCR.php';
//or require_once 'vendor/autoload.php' if you are using composer

$tesseract = new TesseractOCR('u23.png');
$tesseract->setLanguage('eng'); //same 3-letters code as tesseract training data packages
echo $tesseract->recognize();


?>
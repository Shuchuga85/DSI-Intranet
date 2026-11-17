<?php
// if you are using composer, just use this
/*if (!file_exists(__DIR__.'/vendor/autoload.php')) echo 'autoload.php mevcut değil!';
else require __DIR__.'/vendor/autoload.php';

use NcJoes\OfficeConverter\OfficeConverter;

echo 'Converting...<br>';
$input = __DIR__."/result.docx";
$converter = new OfficeConverter($input,__DIR__."/results");
$output = $converter->convertTo('result.pdf'); 

echo 'Saved at:' . $output . '<br>';*/
//$string = '"C:/Program Files/LibreOffice/program/soffice.exe" --headless --convert-to pdf C:/xampp/htdocs/intranet/result.docx --outdir C:/xampp/htdocs/intranet/';
$string ='"C:\Program Files\PDFCreator\pdfCreator-cli.exe" PrintFile /File="C:\xampp\htdocs\intranet\result.docx" /OutputFile="C:\result.pdf"';
exec($string);
exit;
// pdfCreator.exe /printfile="C:\xampp\htdocs\intranet\result.docx" /outputfile="C:\result.pdf"
// "C:\Program Files\PDFCreator\pdfCreator-cli.exe" PrintFile /File="C:\xampp\htdocs\intranet\result.docx" /OutputFile="C:\xampp\htdocs\intranet\"
// "winword.exe" "C:\xampp\htdocs\intranet\result.docx" /mFilePrintDefault 
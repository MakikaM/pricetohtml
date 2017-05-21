**pricetohtml** is excel pricelist to HTML file converter.

How it works:
 * Check uploaded on server excel file on open
 * Moves current pricelist excel file to defined folder for old files
 * Moves new one into the place of previous
 * Delete oldest files to keep defined number of old pricelists
 * Reads excel file, passes defined number of rows(of headers, contacts, logos,etc.)
 * Read defined columns, passes rows if defined NOT EMPTY columns is empty
 * Genereates HTML file from defined header and footer HTML files and formatted rows from Excel files between.

### Requirements:
 * PHP 7.0
 * Nuovo Spreadsheet-reader https://github.com/nuovo/spreadsheet-reader
 
 
 ### Usage:
   Create instance of UploadedPriceToHTML class, set it's properties and call method 'Do'. For example look at tests/UploadedPriceToHTMLTest.php

 ### Licensing
 All of the code in this library is licensed under the MIT license as included in the LICENSE file, however, for now spreadsheet-reader library
relies on php-excel-reader library for XLS file parsing which is licensed under the PHP license.

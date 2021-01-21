<?php
namespace libraries;

use Mpdf\Mpdf;

/**
 * Class Pdf
 * @package libraries
 */
class Pdf
{
    public function __construct($configs = [])
    {
    }

    public function read($file) {
        $pdf = new Mpdf();
    }

    public function write($data, $file) {}
}
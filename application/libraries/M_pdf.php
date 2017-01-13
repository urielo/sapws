<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
include_once APPPATH.'/third_party/vendor/autoload.php';
//include_once APPPATH.'/third_party/mpdf60/mpdf.php';

class M_pdf {
 
    public $param;
    public $pdf;
 
    public function __construct($param = '"pt-BR","A4"')
    {
        $this->param =$param;
        $this->pdf = new mPDF($this->param);
    }
}
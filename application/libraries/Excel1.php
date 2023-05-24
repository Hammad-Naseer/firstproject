<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

//require_once APPPATH."/PHPExcel/PHPExcel.php";
require_once(dirname(__FILE__) . '/PHPExcel/PHPExcel.php');
require_once(dirname(__FILE__) . '/PHPExcel/PHPExcel/IOFactory.php');

 
class Excel extends PHPExcel
{
    public function __construct()
    {
        parent::__construct();
        PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
    }
}
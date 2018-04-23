<?php
namespace Tochka\ReportMaker\Examples;

require_once($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/src/CsvReport.php');

use Tochka\ReportMaker\CsvReport;

$file = new CsvReport(
    [
        ['r1c1', 'r1c2', 'r1c3'],
        ['r2c1', 'r2c2', 'r2c3'],
        ['r3c1', 'r3c2', 'r3c3'],
    ]
);
var_dump($file->generate());
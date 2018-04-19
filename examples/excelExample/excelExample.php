<?php
namespace Tochka\ReportMaker\Examples;

require_once($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/src/ReportFactory.php');

use Tochka\ReportMaker\ReportFactory;

$file = new ReportFactory(
    'Xlsx',
    [
        '{{test}}' => 'test!!!',
        '{{data}}' => date('d.m.Y'),
        '{{jan}}' => 1000,
        '{{feb}}' => 2000,
        '{{mar}}' => 3000,
        '{{apr}}' => 4000,
        '{{may}}' => 5000,
        '{{june}}' => 6000,
        '{{july}}' => 7000,
        '{{aug}}' => 8000,
        '{{sep}}' => 9000,
        '{{oct}}' => 8500,
        '{{nov}}' => 8000,
        '{{dec}}' => 9000
    ],
    __DIR__ . '/IPTemplate.xlsx',
    [
        'output_path' => __DIR__ . '/excelExample.xlsx'
    ]);

$file->generate();
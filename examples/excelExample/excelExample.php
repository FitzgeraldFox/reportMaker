<?php
namespace Tochka\ReportMaker\Examples;

require_once( '../../vendor/autoload.php');
require_once('../../src/ExcelReport.php');

use Tochka\ReportMaker\ExcelReport;

$file = new ExcelReport(
    [
        'headers' => [
            'colHeader1', 'colHeader2', 'colHeader3'
        ],
        'data' => [
            [
                'col1row1', 'col2row1', 'col3row1'
            ],
            [
                'col1row2', 'col2row2', 'col3row2'
            ],
            [
                'col1row3', 'col2row3', 'col3row3'
            ]
        ]
    ],
    __DIR__ . '/excelExample.xlsx');

$file->generate();
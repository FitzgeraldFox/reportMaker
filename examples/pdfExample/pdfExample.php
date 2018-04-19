<?php
namespace Tochka\ReportMaker\Examples;

require_once($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/src/ReportFactory.php');

use Tochka\ReportMaker\reportFactory;

$file = new ReportFactory(
    'Pdf',
    [
        'test' => 'test!!!',
        'monthAmount' => [
            'Плановый Эквайринговый Оборот, руб' => '185 000',
            'Срок Кредита, дней' => '149 000',
            'Сумма Кредита, руб' => '200 000',
            'Удержание, %' => '30,0',
            'Величина Процентов %' => '12,5%',
            'Сумма Процентов, руб.' => '24 493,15'
        ]
    ],
    __DIR__
);
file_put_contents(__DIR__ . '/pdfExample.pdf', $file->generate());
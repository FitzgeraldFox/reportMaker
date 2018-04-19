<?php

namespace Tochka\ReportMaker;

require_once($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php');

use Dompdf\Dompdf;
use Dompdf\Options;
use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Twig_Environment;
use Twig_Loader_Filesystem;

class ReportFactory
{
    private $type;
    private $data;
    private $templatePath;
    private $outputPath;
    private $twigOptions;
    private $pdfOptions;
    private $csvDelimiter;
    private const TYPES = [
        'Pdf',
        'Xls',
        'Xlsx',
        'Ods',
        'Csv',
        'Html',
        'Tcpdf',
        'Dompdf',
        'Mpdf',
    ];

    public function __construct(string $type, array $data, string $template_path, array $options = [])
    {
        $this->data = $data;

        if (!in_array($type, static::TYPES)) {
            throw new Exception("ReportFactory failed: wrong type argument (type = $type)");
        }

        $this->type = $type;
        $this->templatePath = $template_path;
        $this->twigOptions = [];
        $this->pdfOptions = [];

        if (!empty($options)) {
            if (!empty($options['twig'])) {
                $this->twigOptions = $options['twig'];
            }
            if (!empty($options['output_path'])) {
                $this->outputPath = $options['output_path'];
            } else {
                if ($type == 'Xls' || $type == 'Xlsx') {
                    throw new Exception('ReportFactory failed: options["output_path"] is required when type = Xls || Xlsx');
                }
            }
            if (!empty($options['pdf'])) {
                $this->pdfOptions = $options['pdf'];
            }
            if (!empty($options['csv_delimiter'])) {
                $this->csvDelimiter = $options['csv_delimiter'];
            }
        } else {
            if ($type == 'Xls' || $type == 'Xlsx') {
                throw new Exception('ReportFactory failed: options["output_path"] is required when type = Xls || Xlsx');
            }
        }
    }

    public function generate()
    {
        $fileContent = null;
        switch ($this->type) {
            case 'Pdf':
                $fileContent = $this->generatePdf();
                break;
            case 'Xls':
            case 'Xlsx':
                $this->generateXls();
                break;
            case 'Csv':
                $fileContent = $this->generateCsv();
                break;
        }

        if (!empty($fileContent) && !empty($this->outputPath)) {
            if (file_put_contents($this->outputPath, $fileContent) !== false) {
                return 'File saved to ' . $this->outputPath;
            }
        }
        return $fileContent;
    }
    protected function generateHTML()
    {
        $loader = new Twig_Loader_Filesystem($this->templatePath);
        $twig = new Twig_Environment($loader, $this->twigOptions);
        return $twig->render('index.twig', $this->data);
    }
    protected function generatePdf()
    {
        $templateBody = $this->generateHTML();

        $dompdf = new Dompdf();

        $pdfOptionsArray = [
            'isHtml5ParserEnabled' => true,
            'defaultPaperSize' => 'A4',
            'chroot' => $this->templatePath,
        ];

        foreach ($this->pdfOptions as $key => $value) {
            $pdfOptionsArray[$key] = $value;
        }
        $options = new Options($pdfOptionsArray);

        $dompdf->setOptions($options);

        $dompdf->loadHtml($templateBody);
        $dompdf->render();

        return $dompdf->output();
    }
    protected function generateXls()
    {
        $spreadsheet = IOFactory::load($this->templatePath);
        $worksheet = $spreadsheet->getActiveSheet();

        foreach ($worksheet->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(FALSE);
            foreach ($cellIterator as $cell) {
                foreach ($this->data as $dataKey => $dataValue) {
                    if (!empty($cell->getValue()) && $cell->getValue() == $dataKey) {
                        $cell->setValue($dataValue);
                        break;
                    }
                }
            }
        }

        $writer = IOFactory::createWriter($spreadsheet, $this->type);
        $writer->save($this->outputPath);
    }
    protected function generateCsv(): string
    {
        $csvContent = '';
        $delimiter = ',';
        if (!empty($this->csvDelimiter)) {
            $delimiter = $this->csvDelimiter;
        }
        foreach ($this->data as $row) {
            $csvContent .= implode($delimiter, $row) . "\n";
        }
        return $csvContent;
    }
}
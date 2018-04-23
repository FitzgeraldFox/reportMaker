<?php

namespace Tochka\ReportMaker;

require_once('AbstractReport.php');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ExcelReport extends AbstractReport
{
    protected $type;
    protected $outputPath;
    protected $templatePath;

    public function __construct(
        array $data,
        string $output_path,
        string $type = 'Xlsx')
    {
        $this->data = $data;
        if ($type != 'Xls' && $type != 'Xlsx') {
            throw new \Exception('ExcelReport failed: wrong type argument (type = ' . $type . ')');
        }
        $this->type = $type;
        $this->outputPath = $output_path;
    }

    public function generate()
    {
        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();

        foreach ($this->data['headers'] as $index => $value) {
            $worksheet->getCellByColumnAndRow($index + 1, 1)
                ->setValue($value)
                ->getStyle()->getFont()->setBold(true);
            $worksheet->getCellByColumnAndRow($index + 1, 1);
            $worksheet->getColumnDimensionByColumn($index + 1)->setAutoSize(true);
        }

        foreach ($this->data['data'] as $colIndex => $rowValues) {
            foreach ($rowValues as $rowIndex => $value) {
                $worksheet->getCellByColumnAndRow($rowIndex + 1,  $colIndex+ 2)->setValue($value);
            }
        }

        $writer = IOFactory::createWriter($spreadsheet, $this->type);
        $writer->save($this->outputPath);
    }
}
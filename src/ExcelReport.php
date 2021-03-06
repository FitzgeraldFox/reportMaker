<?php

namespace Tochka\ReportMaker;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ExcelReport implements ISaveable
{
    protected $data;
    protected $type;
    protected $outputPath;
    protected $templatePath;

    public function __construct(
        array $data,
        string $template_path = '',
        string $output_path,
        string $type = 'Xlsx')
    {
        $this->data = $data;
        if ($type != 'Xls' && $type != 'Xlsx') {
            throw new \Exception('ExcelReport failed: wrong type argument (type = ' . $type . ')');
        }
        $this->type = $type;
        $this->outputPath = $output_path;
        $this->templatePath = $template_path;
    }

    public function save()
    {
        if (empty($this->templatePath)) {
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
        } else {
            $spreadsheet = IOFactory::load($this->templatePath);
            foreach ($this->data as $sheetIndex => $sheetCells) {
                $sheet = $spreadsheet->getSheet($sheetIndex);
                foreach ($sheetCells as $cellCoord => $value) {
                    $sheet->getCell($cellCoord)->setValue($value);
                }
            }
        }

        $writer = IOFactory::createWriter($spreadsheet, $this->type);
        $writer->save($this->outputPath);
    }
}
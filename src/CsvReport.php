<?php

namespace Tochka\ReportMaker;

class CsvReport implements IGeneratable
{
    protected $data;
    protected $csvDelimiter;

    public function __construct(
        array $data,
        string $csv_delimiter = ',')
    {
        $this->data = $data;
        $this->csvDelimiter = $csv_delimiter;
    }

    public function generate(): string
    {
        $csvContent = '';
        foreach ($this->data as $row) {
            $csvContent .= implode($this->csvDelimiter, $row) . "\n";
        }
        return $csvContent;
    }
}
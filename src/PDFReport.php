<?php

namespace Tochka\ReportMaker;

require_once('AbstractReport.php');

use Dompdf\Dompdf;
use Dompdf\Options;
use Twig_Environment;
use Twig_Loader_Filesystem;

class PDFReport extends AbstractReport
{
    protected $twigOptions;
    protected $pdfOptions;
    protected $templatePath;

    public function __construct(
        array $data,
        string $template_path,
        array $twig_options = [],
        array $pdf_options = [])
    {
        $this->data = $data;
        $this->templatePath = $template_path;
        $this->twigOptions = $twig_options;
        $this->pdfOptions = $pdf_options;
    }

    public function generate()
    {
        $templateBody = $this->generateHTML();
        file_put_contents(__DIR__ . '/pdfExample.html', $templateBody);

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

    protected function generateHTML()
    {
        $loader = new Twig_Loader_Filesystem($this->templatePath);
        $twig = new Twig_Environment($loader, $this->twigOptions);
        return $twig->render('index.twig', $this->data);
    }
}
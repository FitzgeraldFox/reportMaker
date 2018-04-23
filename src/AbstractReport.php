<?php
/**
 * Created by PhpStorm.
 * User: menshenin
 * Date: 20.04.2018
 * Time: 11:54
 */

namespace Tochka\ReportMaker;


abstract class AbstractReport
{
    protected $data;
    abstract public function generate();
}
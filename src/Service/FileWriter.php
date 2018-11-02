<?php

declare(strict_types=1);

namespace App\Service;


use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Symfony\Component\HttpKernel\KernelInterface;

class FileWriter
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @param array $arr
     * @throws Exception
     */
    public function write(array $arr)
    {
        $spreadsheet = new Spreadsheet();
        try {
            $sheet = $spreadsheet->getActiveSheet();
        } catch (Exception $e) {
            throw  $e;
        }

        for ($i = 0; $i < count($arr); $i++) {
            $sheet->setCellValue('A' . (string)($i + 1), $arr[$i]);
        }

        $sheet->setTitle("Codes");
        $writer = new Xls($spreadsheet);
        $publicDirectory = $this->kernel->getRootDir();
        $excelFilepath =  $publicDirectory . '/codes.xls';

        $writer->save($excelFilepath);
    }
}
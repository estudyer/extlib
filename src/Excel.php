<?php
namespace libraries;

use libraries\exception\ExcelException;
use libraries\messages\Codes;
use PhpOffice\PhpSpreadsheet\{IOFactory, Reader\Exception, Spreadsheet};

/**
 * Class Excel
 * @package libraries
 */
class Excel
{
    protected $savePath = __DIR__ . '/files/excel/';

    public function __construct($configs = [])
    {
        if(isset($configs['savePath']) && !empty($configs['savePath']) && is_dir($configs['savePath']))
            $this->savePath = $configs['savePath'];

        if(!is_dir($this->savePath)) mkdir($this->savePath, 0777, true);
    }

    /**
     * @param $file
     * @param int $getSheet
     * @param string $type
     * @return array
     * @throws ExcelException
     */
    public function read($file, $getSheet = 0, $type = 'xlsx') {
        if(!is_file($file))
            throw new ExcelException(lmsg(Codes::EXCEL_FILE_NOTEXISTS));

        try {
            $reader = IOFactory::createReader(ucfirst(strtolower($type)));
            $excel = $reader->load($file);
            $data = [];
            if($getSheet === 0) {
                $names = $excel->getSheetNames();
                $sheets = $excel->getAllSheets();
                foreach ($sheets as $index => $sheet) {
                    $data[$names[$index]] = $sheet->toArray();
                }
            } else {
                $data = $excel->getActiveSheet($getSheet)->toArray();
            }
        } catch (Exception $exception) {
            throw new ExcelException(lmsg(Codes::EXCEL_READFAIL, ['error' => $exception->getMessage()]));
        }

        return $data;
    }

    /**
     * @param $data
     * @param $file
     * @param array $extend
     * @param int $storeLevel
     * @return string
     * @throws ExcelException
     */
    public function write($data, $file, $extend = [], $storeLevel = 1) {
        try{
            $excel = new Spreadsheet();
            $sheet = $excel->getActiveSheet();
            if(isset($extend['title']) && !empty($extend['title']))
                $sheet->setTitle($extend['title']);

            $sheet->fromArray($data);
            $type = ucfirst(strtolower(array_get($excel, 'type', 'xlsx')));
            $writer = IOFactory::createWriter($excel, $type);
            if($storeLevel === 1) {
                $writer->save($this->savePath . $file);
                return $this->savePath . $file;
            } else if($storeLevel === 2) {
                if ($type == 'Xlsx') {
                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                } elseif ($type == 'Xls') {
                    header('Content-Type: application/vnd.ms-excel');
                }
                header("Content-Disposition: attachment;filename=" . $file);
                header('Cache-Control: max-age=0');
                $writer->save('php://output');
            } else if($storeLevel === 3) {
                $writer->save($this->savePath . $file);
                if ($type == 'Xlsx') {
                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                } elseif ($type == 'Xls') {
                    header('Content-Type: application/vnd.ms-excel');
                }
                header("Content-Disposition: attachment;filename=" . $file);
                header('Cache-Control: max-age=0');
                $writer->save('php://output');
                return $this->savePath . $file;
            }
        } catch (Exception $exception) {
            throw new ExcelException(lmsg(Codes::EXCEL_SAVEFAIL, ['error' => $exception->getMessage()]));
        }

        return '';
    }
}
<?php

namespace Kf\KitBundle\Utils;

use Symfony\Component\PropertyAccess\PropertyAccess;

class ExportHelper
{
    const INDEX_ALPHA     = true;
    const DEFAULT_FORMAT  = 'xls';
    const THROW_EXCEPTION = false;

    private static $writers = array(
        'xls' => 'Excel5',
        'csv' => 'CSV'
    );
    private static $mimes = array(
        'xls' => 'application/vnd.ms-excel',
        'csv' => 'text/csv'
    );

    protected static $headers = array();
    protected static $fields = array();

    public function execute($source, $format = static::DEFAULT_FORMAT, $filename = null)
    {
        $filename = $this->processFilename($filename, $format);
        $obj = $this->createObject();
        $this->writeHeader($format, $filename);
        $this->fillRows($source, $obj);
        $objWriter = \PHPExcel_IOFactory::createWriter($obj, $this->getWriterName($format));
        $objWriter->save('php://output');
        exit;
    }
    protected function processFilename($filename, $format = null){
        if ($filename == null) {
            $filename = 'export{DATE}{TIME}.' . $format;
        }
        if(strpos($filename),'{DATE}'){
            $filename = str_replace('{DATE}', date('Y-m-d'));
        }
        if(strpos($filename),'{TIME}'){
            $filename = str_replace('{TIME}', date('H:i:s'));
        }
 
        return $filename;    
    }
    /**
     * @return \PHPExcel
     */
    protected function createObject()
    {
        $obj = new \PHPExcel();
        $obj->setActiveSheetIndex(0);
        $headers = static::$headers;

        if (empty($headers)) {
            $headers = static::$fields;
        }
        if (static::INDEX_ALPHA) {
            $headers = StringUtils::indexNumAlpha($headers);
        }
        foreach ($headers as $k => $v) {
            $obj->getActiveSheet()->setCellValue($k . '1', $v);
        }

        return $obj;
    }

    /**
     * @param \PHPExcel $obj
     * @param array     $source
     */
    protected function fillRows($source, \PHPExcel $obj)
    {
        $rowCount = 2;
        $accessor = PropertyAccess::createPropertyAccessor();
        $fields   = static::$fields;
        if (static::INDEX_ALPHA) {
            $fields = StringUtils::indexNumAlpha($fields);
        }
        foreach ($source as $entity) {
            foreach ($fields as $k => $v) {
               $val = (string)$val;
                $obj->getActiveSheet()->SetCellValue($k . $rowCount, $val);
            }
            $rowCount++;
        }
    }

    protected function getValue($entity, $key){
        try {
            $val = $accessor->getValue($entity, $key);
        } catch (\Exception $e) {
            $val = '';
        }
        return $this->processVal($val, $key); 
    }

    protected function processval($val, $key){
        if ($val instanceof \DateTime) {
            $val = $val->format('d/m/Y h:i');
        }
        return $val;
    }

    protected function writeHeader($format, $filename)
    {
        header('Content-Type: ' . self::$mimes[$format]);
        header("Content-type: text/csv");
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
    }

    protected function getWriterName($format)
    {
        return self::$writers[$format];
    }
}  

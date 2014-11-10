<?php

namespace Kf\KitBundle\Utils;

use Symfony\Component\PropertyAccess\PropertyAccess;

class ExportHelper
{
    const INDEX_ALPHA     = true;
    const DEFAULT_FORMAT  = 'xls';
    const THROW_EXCEPTION = true;

    private static $writers = array(
        'xls' => 'Excel5',
        'csv' => 'CSV',
        'html' => 'HTML',
        'pdf' => 'PDF',
        'xlsx' => 'Excel2007'
    );
    private static $mimes = array(
        'xls' => 'application/vnd.ms-excel',
        'csv' => 'text/csv',
        'html' => 'text/html',
        'pdf' => 'application/pdf',
        'xlsx' => 'application/vnd.ms-excel'
    );

    protected $headers = array();
    protected $fields = array();

    protected $accessor;


    public function execute($source, $format = null, $filename = null)
    {

        $this->accessor = PropertyAccess::createPropertyAccessor();
        if(!isset($format)){
            $format = static::DEFAULT_FORMAT;
        }
        $filename = $this->processFilename($filename, $format);
        $obj = $this->createObject();
        $this->fillRows($source, $obj);
        $objWriter = \PHPExcel_IOFactory::createWriter($obj, $this->getWriterName($format));

        $this->writeHeader($format, $filename);
        $objWriter->save('php://output');
        exit;
    }
    protected function processFilename($filename, $format = null){
        if ($filename == null) {
            $filename = 'export{DATE}-{TIME}.' . $format;
        }
        if(strpos($filename,'{DATE}') !== false){
            $filename = str_replace('{DATE}', date('Y-m-d'), $filename);
        }
        if(strpos($filename,'{TIME}') !== false){
            $filename = str_replace('{TIME}', date('H-i-s'), $filename);
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
        $headers = $this->headers;

        if (empty($headers)) {
            $headers = $this->fields;
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
        $fields   = $this->fields;
        if (static::INDEX_ALPHA) {
            $fields = StringUtils::indexNumAlpha($fields);
        }

        foreach ($source as $entity) {
            foreach ($fields as $k => $v) {
                $val = $this->getValue($entity, $v);
                $obj->getActiveSheet()->SetCellValue($k . $rowCount, $val);
            }
            $rowCount++;
        }
    }

    protected function getValue($entity, $key){
        if(StringUtils::startsWith($key,'@')){
            $val = $entity;
        }else{
            try {
                $val = $this->accessor->getValue($entity, $key);
            } catch (\Exception $e) {
                $val = '';
            }
        }
        return $this->processValue($val, $key); 
    }

    protected function processValue($val, $key){
        if ($val instanceof \DateTime) {
            $val = $val->format('d/m/Y h:i');
        }
        return (string) $val;
    }

    protected function writeHeader($format, $filename)
    {
        header('Content-Type: ' . self::$mimes[$format]);
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
    }

    protected function getWriterName($format)
    {
        return self::$writers[$format];
    }
}  

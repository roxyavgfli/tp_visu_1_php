<?php
/**
 * Created by PhpStorm.
 * User: aym
 * Date: 13/10/2017
 * Time: 16:15
 */

/**
 * Returns Array of data from file located at $filePath
 * @param $filePath
 * @return array
 */
function readDataFromFile ($filePath) {
    $array = Array();
    $lines = file($filePath);
    foreach($lines as $line){
        array_push($array, floatval($line));
    }
    return $array;
}

/**
 * Create and fills file with data contained in $dataArray
 * @param $dataArray
 * @param $fileName
 */
function writeDataFromArray ($dataArray, $fileName) {
    $file = fopen("../" . $fileName);
    foreach ($dataArray as $row){
        fputs($file, $row . "\n");
    }
    fclose($file);
}

function object_to_array($data)
{
    if(is_array($data) || is_object($data))
    {
        $result = array();

        foreach($data as $key => $value) {
            $result[$key] = $this->object_to_array($value);
        }

        return $result;
    }

    return $data;
}
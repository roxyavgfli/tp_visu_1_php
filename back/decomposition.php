<?php
/**
 * Created by PhpStorm.
 * User: aym
 * Date: 13/10/2017
 * Time: 16:29
 */

require_once 'fileUtils.php';

Class Decomposition {

    private $x = Array();
    private $y = Array();
    private $details;
    private $resolution;
    private $iterations = 0;

    function __construct(Array $dataArray, $details, $resolution) {
        $this->details = $details;
        error_log("details : " + $this->details);
        //print_r($this->details);
        $this->resolution = $resolution;
        //$this->data_array = $dataArray;
        $this->x = $dataArray;
    }

    public function getDetails() {
        return $this->details;
    }

    public function getArray() {
        return array_merge($this->x, $this->y);
    }

    public function decompose_step() {
        $new_x = Array();
        for ($i = 0; $i < sizeof($this->x)/2; $i++){
            array_push($new_x, ($this->x[2*$i] + $this->x[2*$i+1])/2);
            if (abs($this->x[2*$i] - $new_x[$i]) < $this->details){
                array_unshift($this->y, $this->x[2*$i] - $new_x[$i]);
            }else{
                error_log("skipped");
                array_unshift($this->y, 0);
            }
        }
        $this->x = $new_x;
        $this->iterations++;
    }

    public function recompose_step() {
        $new_x = Array();
        for($i = 0; $i < sizeof($this->x); $i++){
            if ($this->resolution > 0){
                $new_x[$i * 2] = $this->x[$i] + $this->y[$i];
                $new_x[$i * 2 + 1] = $this->x[$i] - $this->y[$i];
                array_shift($this->y);
                $this->x = $new_x;
                $this->resolution --;
            }
        }

    }

    public function decompose_full() {
        while (sizeof($this->x) > 1){
            $this->decompose_step();
        }
    }

    public function recompose_full() {
        while (sizeof($this->x) > 0 && $this->resolution > 0){
            $this->recompose_step();
        }
    }

    public function insertX ($val) {
        array_push($x, $val);
    }

    public function insertY ($val) {
        array_push($y, $val);
    }

    /**
     * @return array
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @param array $x
     */
    public function setX($x)
    {
        $this->x = $x;
    }

    /**
     * @return array
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @param array $y
     */
    public function setY($y)
    {
        $this->y = $y;
    }

}

$details = null;
$resolution = null;

if(isset($_POST['detail'])){
    $details = $_POST['detail'];
}

if(isset($_POST['resolution'])){
    $resolution = $_POST['resolution'];
}

//print_r($details);

$dataArray = readDataFromFile("../data/data_origin.txt");
$decomp = new Decomposition($dataArray, $details, $resolution);

$decomp->decompose_full();




//$decomp->recompose_step();

//print_r($decomp);
$decomp->recompose_full();

echo json_encode($decomp->getX());



/**
 * Executes the "décomposition"
 * @param $dataArray
 * @param $resolution
 * @param $details
 */
function decomposition ($dataArray, $resolution, $details) {
    $decomp = new Decomposition((Array)$dataArray, $details, $resolution);
}
<?php
/**
 * Created by PhpStorm.
 * User: aym
 * Date: 13/10/2017
 * Time: 16:29
 */

require_once 'fileUtils.php';

Class Decomposition {

    private $data_origin;
    private $data_final;
    private $x = Array();
    private $y = Array();
    private $details;
    private $resolution;
    private $iterations = 0;
    private $error = 0;

    function __construct(Array $dataArray, $details, $resolution) {
        $this->data_origin = $dataArray;
        $this->details = $details;
        //error_log("details : " + $this->details);
        //print_r($this->details);
        $this->resolution = $resolution;
        //$this->data_array = $dataArray;
        $this->x = $dataArray;
    }

    public function getError(){
        return $this->error;
    }

    public function quadError(){
        $error = 0;
        for($i=0; $i < sizeof($this->data_final); $i++){
            $error = $error + (($this->data_origin[$i] - $this->data_final[$i]) * ($this->data_origin[$i] - $this->data_final[$i]));
        }
        $this->error = $error;
    }

    public function getDetails() {
        return $this->details;
    }

    public function getArray() {
        return array_merge($this->x, $this->y);
    }

    public function decompose_step() {
        $new_x = Array();
        $sub_y = Array();
        for ($i = 0; $i < sizeof($this->x)/2; $i++){
            array_push($new_x, ($this->x[2*$i] + $this->x[2*$i+1])/2);
            if ($this->details == null || abs($this->x[2*$i] - $new_x[$i]) > $this->details){
                array_push($sub_y, $this->x[2*$i] - $new_x[$i]);
            }else{
                //error_log("skipped");
                array_push($sub_y, 0);
            }
        }
        $this->x = $new_x;
        array_unshift($this->y, $sub_y);
        $this->iterations++;
    }

    public function recompose_step($increment) {
        $new_x = Array();
        for ($i = 0; $i < sizeof($this->y[$increment]); $i++) {
            $new_x[2*$i]= $this->x[$i]+$this->y[$increment][$i];
            $new_x[2*$i+1]= $this->x[$i]-$this->y[$increment][$i];
        }
        $this->x=$new_x;
    }

    public function decompose_full() {
        while (sizeof($this->x) > 1){
            $this->decompose_step();
        }
    }

    public function recompose_full() {
        for ($i = 0; $i < sizeof($this->y) && $i < $this->resolution; $i++){
            $this->recompose_step($i);
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

    /**
     * @return mixed
     */
    public function getDataFinal()
    {
        return $this->data_final;
    }

    /**
     * @param mixed $data_final
     */
    public function setDataFinal($data_final): void
    {
        $this->data_final = $data_final;
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

//print_r($decomp);


//$decomp->recompose_step();

//print_r($decomp);
$decomp->recompose_full();

$decomp->setDataFinal($decomp->getX());
$decomp->quadError();



//print_r($decomp);

$to_send = Array();
array_push($to_send, $decomp->getDataFinal());
array_push($to_send, $decomp->getError());

echo json_encode($to_send);



/**
 * Executes the "décomposition"
 * @param $dataArray
 * @param $resolution
 * @param $details
 */
function decomposition ($dataArray, $resolution, $details) {
    $decomp = new Decomposition((Array)$dataArray, $details, $resolution);
}
<?php
/**
 * Created by PhpStorm.
 * User: aym
 * Date: 14/10/2017
 * Time: 10:28
 */

require_once 'fileUtils.php';

$file = $_FILES['file'];

if (file_exists("../data/data_origin.txt")){
    unlink("../data/data_origin.txt");
}

$file = move_uploaded_file($file['tmp_name'], "../data/data_origin.txt");

if ($file) {
    echo json_encode(readDataFromFile("../data/data_origin.txt"));
} else {
    echo ("Erreur téléchargement");
}
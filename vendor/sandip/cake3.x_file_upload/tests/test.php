<?php
require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload
use FileUpload\Controller\Component\FileUploadComponent;

error_reporting(E_ALL);
ini_set('display_errors', 1);

$fileObj = new FileUploadComponent();

echo print_r($fileObj);exut;

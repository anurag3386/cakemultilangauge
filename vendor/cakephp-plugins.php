<?php
$baseDir = dirname(dirname(__FILE__));
return [
    'plugins' => [
        'Bake' => $baseDir . '/vendor/cakephp/bake/',
        'DebugKit' => $baseDir . '/vendor/cakephp/debug_kit/',
        'FileUpload' => $baseDir . '/vendor/sandip/cake3.x_file_upload/',
        'Migrations' => $baseDir . '/vendor/cakephp/migrations/'     
    ]
];
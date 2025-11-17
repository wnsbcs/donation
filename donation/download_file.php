<?php
$batch = $_GET['batch'] ?? '';
$file  = $_GET['file'] ?? '';
$path = __DIR__ . '/uploads/' . $batch . '/' . $file;

if (!file_exists($path)) die('Not found');
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="'.basename($file).'"');
readfile($path);
exit;

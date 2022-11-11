<?php
$file =  explode('=', $_SERVER['REQUEST_URI'])[1];
$file =  explode('%20', $file);
$file = 'Files_Pdf_modified/'. $file[0] .' ' . $file[1];
$filename = 'Arquivo assinado.pdf';
header('Content-type: application/pdf');
header('Content-Disposition: inline; filename="' . $filename . '"');
header('Content-Transfer-Encoding: binary');
header('Content-Length: ' . filesize($file));
header('Accept-Ranges: bytes');
@readfile($file);
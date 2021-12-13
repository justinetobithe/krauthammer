<?php
include('../../config.php');
include('../../admin/libraries/FileUploadHandler.php');
/*
Uploadify v2.1.4
Release Date: November 8, 2010

Copyright (c) 2010 Ronnie Garcia, Travis Nickels

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/
if (!empty($_FILES)) {
	// $fhandler = new FileUploadHandler();
	$tempFile = $_FILES['Filedata']['tmp_name'];
    $tempType = $_FILES['Filedata']['type'];
    $base_dir = __DIR__ . "/";

    $allowedTypes = array();
    if(defined('IMAGETYPE_GIF')) $allowedTypes[] = IMAGETYPE_GIF;
    if(defined('IMAGETYPE_JPEG')) $allowedTypes[] = IMAGETYPE_JPEG;
    if(defined('IMAGETYPE_PNG')) $allowedTypes[] = IMAGETYPE_PNG;
    if(defined('IMAGETYPE_SWF')) $allowedTypes[] = IMAGETYPE_SWF;
    if(defined('IMAGETYPE_PSD')) $allowedTypes[] = IMAGETYPE_PSD;
    if(defined('IMAGETYPE_BMP')) $allowedTypes[] = IMAGETYPE_BMP;
    if(defined('IMAGETYPE_TIFF_II')) $allowedTypes[] = IMAGETYPE_TIFF_II;
    if(defined('IMAGETYPE_TIFF_MM')) $allowedTypes[] = IMAGETYPE_TIFF_MM;
    if(defined('IMAGETYPE_JPC')) $allowedTypes[] = IMAGETYPE_JPC;
    if(defined('IMAGETYPE_JP2')) $allowedTypes[] = IMAGETYPE_JP2;
    if(defined('IMAGETYPE_JPX')) $allowedTypes[] = IMAGETYPE_JPX;
    if(defined('IMAGETYPE_JB2')) $allowedTypes[] = IMAGETYPE_JB2;
    if(defined('IMAGETYPE_SWC')) $allowedTypes[] = IMAGETYPE_SWC;
    if(defined('IMAGETYPE_IFF')) $allowedTypes[] = IMAGETYPE_IFF;
    if(defined('IMAGETYPE_WBMP')) $allowedTypes[] = IMAGETYPE_WBMP;
    if(defined('IMAGETYPE_XBM')) $allowedTypes[] = IMAGETYPE_XBM;
    if(defined('IMAGETYPE_ICO')) $allowedTypes[] = IMAGETYPE_ICO;
    if(defined('IMAGETYPE_WEBP')) $allowedTypes[] = IMAGETYPE_WEBP;

	if($tempFile != null){
        $detectedType = @exif_imagetype($tempFile);
        if (!in_array($detectedType, $allowedTypes)) {
        	echo "Invalid File!"; exit();
        }
    }else{
        echo "Empty Image."; exit();
    }

    $name = strtolower($_FILES['Filedata']['name']);
    $name = preg_replace("/[^a-z0-9.]+/i", " ", $name);
    $name = preg_replace("/[\s-]+/", " ", $name);
    $name = preg_replace("/[\s_]/", "-", $name);

    $targetPath = date('Y/m/d/') . "tinymce/";
    $targetFile = $targetPath . md5(time()).'-'.$name;

    @mkdir($base_dir . $targetPath, 0755, true);
    move_uploaded_file($tempFile, __DIR__."/".$targetFile);

    echo rtrim(URL,'/').'/images/uploads/'.$targetFile;
}
?>
<?php
header("content-type: application/json"); 
/**
 * Create a thumbnail
 *
 * @author Brett @ Mr PHP
 */
// require_once __DIR__ . '/../cms-include.php';
// define allowed image sizes
error_reporting(E_ALL ^ E_DEPRECATED);
$sizes = array(
        '200x120',
        '203x153',
        '205x154',
        '176x167',
        '600x600',
        '84x73',
        '327x175',
        '234x155',
        '388x294',
        '78x66',
        '143x89',
        '388x294',
        '660x356'

    );

// $sizes = array_merge($sizes, $cmsThumbnailAllowedSizes);

// ensure there was a thumb in the URL
if (!$_GET['thumb']) {
    error('no thumb');
}

// get the thumbnail from the URL
$thumb = strip_tags(htmlspecialchars($_GET['thumb']));

// get the image and size
$thumb_array = explode('/', $thumb);

$size = array_shift($thumb_array);
$image = __DIR__ . '/../uploads/image/' . implode('/', $thumb_array);

list($width, $height) = explode('x', $size);

// ensure the size is valid
if (!in_array($size, $sizes)) {
    error('invalid size');
}

// ensure the image file exists
if (!file_exists($image)) {
    error('no source image');
}
//echo $image;
// generate the thumbnail
// print_r(__DIR__ . '/../../includes/img-resize/phpthumb.class.php');
require(__DIR__ . '/../../includes/img-resize/phpthumb.class.php');
// exit();
$phpThumb = new phpThumb();
$phpThumb->setSourceFilename($image);
$phpThumb->setParameter('w', $width);
$phpThumb->setParameter('h', $height);
$phpThumb->setParameter('zc', true);
$phpThumb->setParameter('f', substr($thumb, -3, 3)); // set the output format
//$phpThumb->setParameter('far','C'); // scale outside
//$phpThumb->setParameter('bg','FFFFFF'); // scale outside
if (!$phpThumb->GenerateThumbnail()) {
    error('cannot generate thumbnail');
}

// make the directory to put the image
if (!mkpath(dirname($thumb), true)) {
    error('cannot create directory');
}

// write the file
if (!$phpThumb->RenderToFile(__DIR__ . '/' . $thumb)) {
    error('cannot save thumbnail' . $thumb);
}

// redirect to the thumb
// note: you need the '?new' or IE wont do a redirect
// header('Location: ' . dirname($_SERVER['SCRIPT_NAME']) . '/' . $thumb);
// print_r(__DIR__ . "/{$thumb}"); exit();

$file = __DIR__ . "/{$thumb}";
$file_type = mime_content_type ( $file );


if (file_exists($file)) {
    header('Content-Type: ' . ($file_type != "" ? $file_type : 'application/octet-stream'));
    readfile($file);
    exit;
}


// basic error handling
function error($error) {
    header("HTTP/1.0 404 Not Found");
    echo '<h1>Not Found</h1>';
    echo '<p>The image you requested could not be found.</p>';
    echo "<p>An error was triggered: <b>$error</b></p>";
    exit();
}

//recursive dir function
function mkpath($path, $mode) {
    is_dir(dirname($path)) || mkpath(dirname($path), $mode);
    return is_dir($path) || @mkdir($path, 0777, $mode);
}

<?php
$new_width = 200;

// list($width,$height) = getimagesize($_FILES['file']['tmp_name']);


$file = $_FILES['file']['tmp_name'];

list($width,$height) =getimagesize($file);

$rate= $new_width / $width;
$new_height = $rate * $height;

$canvas = imagecreatetruecolor($new_width,$new_height);

switch (exif_imagetype($file)){
  case IMAGETYPE_JPEG:
    $image=imagecreatefromjpag($file);
    imagecopyresampled($canvas,$image,0,0,0,0,$new_width,$new_height,$width,$height);
    imagejpag($canvas,'images/new_image.jpg');
    break;

    case IMAGETYPE_GIF:
    $image=imagecreatefromgif($file);
    imagecopyresampled($canvas,$image,0,0,0,0,$new_width,$new_height,$width,$height);
    imagegif($canvas,'images/new_image.gif');
    break;

    case IMAGETYPE_PNG:
    $image=imagecreatefrompng($file);
    imagecopyresampled($canvas,$image,0,0,0,0,$new_width,$new_height,$width,$height);
    imagepng($canvas,'images/new_image.png');
    break;

    dafault:
    exit();
}

imagedestroy($image);
imagedestroy($canvas);

?>
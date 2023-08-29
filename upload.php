<?php
  include 'includes/login.php';
  $msg = null;  // アップロード状況を表すメッセージ
  $alert = null;  // メッセージのデザイン用
  $name= $_SESSION['name'];
  function addCopyright ($image,$name){
      $day = date('Y/m/d');
      $stamp = imagecreatetruecolor(100,70);
      imagefilledrectangle($stamp,0,0,99,69,0x0000FF);
      imagefilledrectangle($stamp,9,9,90,60,0xFFFFFF);
      imagettftext($stamp,12,0,15,30,0x0000FF,'./font/KH-Dot-Akihabara-16.ttf',$name);
      imagestring($stamp,3,15,40,$day,0x0000ff);
      $marge_right = 10;
      $marge_bottom = 10;
      $sx = imagesx($stamp);
      $sy = imagesy($stamp);

      imagecopymerge($image,$stamp,imagesx($image) - $sx - $marge_right,imagesy($image) - $sy - $marge_bottom,0,0,imagesx($stamp),imagesy($stamp),50);

      return $image;
  }


  // アップロード処理
  if (isset($_FILES['image']) && is_uploaded_file($_FILES['image']['tmp_name'])){
    $old_name = $_FILES['image']['tmp_name'];
    $new_name = $_FILES['image']['name'];

    $new_width = 200;

      // list($width,$height) = getimagesize($_FILES['file']['tmp_name']);


      $file = $_FILES['image']['tmp_name'];

      list($width,$height) =getimagesize($file);

      $rate= $new_width / $width;
      $new_height = $rate * $height;

      $canvas = imagecreatetruecolor($new_width,$new_height);

      switch (exif_imagetype($file)){
        case IMAGETYPE_JPEG:
          $image=imagecreatefromjpeg($file);
          $stamp = imagecreatetruecolor(100,70);
          imagecopyresampled($canvas,$image,0,0,0,0,$new_width,$new_height,$width,$height);
          $canvas = addCopyright($canvas,$name);
          imagejpeg($canvas,'images/'.$new_name);
          break;

          case IMAGETYPE_GIF:
          $image=imagecreatefromgif($file);

          imagecopyresampled($canvas,$image,0,0,0,0,$new_width,$new_height,$width,$height);
          $canvas = addCopyright($canvas,$name);
          imagegif($canvas,'images/'.$new_name);
          break;

          case IMAGETYPE_PNG:
          $image=imagecreatefrompng($file);

          imagecopyresampled($canvas,$image,0,0,0,0,$new_width,$new_height,$width,$height);
          $canvas = addCopyright($canvas,$name);
          imagepng($canvas,'images/'.$new_name);
          break;

          dafault:
          exit();
      }



      imagedestroy($image);
      imagedestroy($canvas);

    if (move_uploaded_file($old_name, 'album/'.$new_name)){
      $msg = 'アップロードしました。';
      $alert = 'success'; // Bootstrapで緑色のボックスにする
    } else {
      $msg = 'アップロードできませんでした。';
      $alert = 'danger';  // Bootstrapで赤いボックスにする
    }
  }



?>
<!doctype html>
<html lang="ja" >
  <head>
    <title>サークルサイト</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  </head>
  <body>

    <?php include('navbar.php'); ?>

    <main role="main" class="container" style="padding:60px 15px 0">
      <div>
        <!-- ここから「本文」-->

        <h1>画像アップロード</h1>
        <?php
          if ($msg){
            echo '<div class="alert alert-'.$alert.'" role="alert">'.$msg.'</div>';
          }
        ?>
        <form action="upload.php" method="post" enctype="multipart/form-data">
          <div class="form-group">
            <label>アップロードファイル</label>
            <input type="file" name="image" class="form-control-file">
          </div>
          <input type="submit" value="アップロードする" class="btn btn-primary">
        </form>

        <!-- 本文ここまで -->
      </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="/docs/4.5/assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
  </body>
</html>

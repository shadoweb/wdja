<?php
header("Content-type:text/html;charset=utf-8");
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
header("Access-Control-Allow-Methods: POST, OPTIONS");
return;
}
require('common/incfiles/autoload.php');
$admc_name = ii_get_safecode($_SESSION[APP_NAME . 'admin_username']);
$admc_pword = ii_get_safecode($_SESSION[APP_NAME . 'admin_password']);
if (!(wdja_cms_cklogin($admc_name, $admc_pword))) header('location: ' . ii_get_actual_route(ADMIN_FOLDER));

wdja_cms_init('');

if($_FILES) echo upload_images();

function upload_images(){
    reset ($_FILES);
    $temp = current($_FILES);
    if (is_uploaded_file($temp['tmp_name'])){
    $itype = strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION));
    if (!in_array($itype, array("gif", "jpg", "png", "bmp"))) {
        header("HTTP/1.1 400 Invalid extension.");
        return;
    }
      /*这里设置开关,是否启用OSS上传
          $res['location'] = mm_oss_upload_data($tmp_file,$itype);
          echo json_encode($res);
          exit;
      */
      $runds = date('YmdHms').'_'.mt_rand(100,999);
      $imgPath = 'upload/'.date('Y').'/'.date('m');
      if (!is_dir($imgPath.'/'))
      {
        mkdir($imgPath, 0755,true);
        chmod($imgPath, 0755);
      }
      $file_name = $imgPath.'/'.$runds.'.'.$itype;
    
      move_uploaded_file($temp['tmp_name'], $file_name);
      return json_encode(array('location' => '/'.$file_name));
    } else {
      header("HTTP/1.1 500 Server Error");
    }
}

function check_image_type($image)
{
  $bits = array(
    'jpg' => "\xFF\xD8\xFF",
    'gif' => "GIF",
    'png' => "\x89\x50\x4e\x47\x0d\x0a\x1a\x0a",
    'bmp' => 'BMP',
  );
  foreach ($bits as $type => $bit) {
    if (substr($image, 0, strlen($bit)) === $bit) {
      return $type;
    }
  }
  return 'jpg';
}
?>
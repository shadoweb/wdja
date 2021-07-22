<?php
header("Content-type:text/html;charset=utf-8");
require('common/incfiles/autoload.php');
wdja_cms_init('');
if ($_FILES) echo wdja_cms_upload();
function wdja_cms_upload() {
  $admc_name = ii_get_safecode($_SESSION[APP_NAME . 'admin_username']);
  $admc_pword = ii_get_safecode($_SESSION[APP_NAME . 'admin_password']);
  if (!(wdja_cms_cklogin($admc_name, $admc_pword))) header('location: ' . ii_get_actual_route(ADMIN_FOLDER));
  $file = $_FILES['file'];
  $tmp_file = file_get_contents($file['tmp_name']);//读取临时图片为二进制数据
  $itype = check_image_type($tmp_file);
  /*这里设置开关,是否启用OSS上传
      $res['location'] = mm_upload_data($tmp_file,$itype);
      echo json_encode($res);
      exit;
  */
  $error = $file['error'];
  $runds = date('YmdHms').'_'.mt_rand(100,999);
  $imgPath = 'upload/'.date('Y').'/'.date('m');
  if (!is_dir($imgPath.'/'))
  {
    mkdir($imgPath, 0755,true);
    chmod($imgPath, 0755);
  }
  $file_name = $imgPath.'/'.$runds.'.'.$itype;
  if ($error == 0) {
    $tp = fopen($file_name, 'w');
    fwrite($tp, $tmp_file);//图片二进制数据写入图片文件
    fclose($tp);
    if (file_exists($file_name))
    {
      $res['location']='/'.$file_name;
      echo json_encode($res);
    }
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
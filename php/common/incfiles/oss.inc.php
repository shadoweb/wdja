<?php
require(__DIR__.'/AliOss/AliyunOss.php');

function mm_upload_File($file,$type,$oss_dir = '',$path = __DIR__.'/temp') {
    $AliyunOss = new AliyunOss();
    $newfile = mm_get_shopnum().'.'.$type;
    $result = $AliyunOss->upload_file($file,$newfile);
    if ($result) {
        return '/img.php?imgpath='.$result['local_path'];//这里设置OSS图片加密跳转文件
    }
}

function mm_signed_File($file) {
    $AliyunOss = new AliyunOss();
    return $AliyunOss->signedUrl($file);
}

function mm_upload_data($data,$type) {
    $AliyunOss = new AliyunOss();
    $newfile = mm_get_shopnum().'.'.$type;
    $result = $AliyunOss->uploadObject($data, $newfile);
    if ($result) {
        return '/img.php?imgpath='.$result['local_path'];//这里设置OSS图片加密跳转文件
    }
}


?>
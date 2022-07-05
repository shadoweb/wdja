<?php
require('common/incfiles/autoload.php');
$imgPath = $_GET['imgpath'];
$request_uri = mm_oss_signed_File($imgPath);
header('HTTP/1.1 301 Moved Permanently');
header('Location: '.$request_uri);
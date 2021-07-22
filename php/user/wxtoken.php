<?php
require('../common/incfiles/autoload.php');
require('../common/incfiles/wxlogin.inc.php');
global $nwxtoken,$nwxappid,$nwxappsecret,$nwxnotifyurl;
define("WEIXIN_TOKEN", $nwxtoken);
$wechatObj = new wechatCallbackapiTest();
$wechatObj->valid();
?>
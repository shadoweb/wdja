<?php
header("Content-type:text/html;charset=utf-8");
require('common/incfiles/autoload.php');
wdja_cms_init('');
$admc_name = ii_get_safecode($_SESSION[APP_NAME . 'admin_username']);
$admc_pword = ii_get_safecode($_SESSION[APP_NAME . 'admin_password']);
if (!(wdja_cms_cklogin($admc_name, $admc_pword))){
    header('location: ' . ii_get_actual_route(ADMIN_FOLDER));
}else{
    $url = $_GET['url'];
    echo json_encode(collects($url));
}
?>
<?php @header("location:install/index.php");?><?php
require('common/incfiles/autoload.php');
ap_user_init();
$myhtml = wdja_cms_module();
echo $myhtml;
?>
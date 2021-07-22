<?php
require('../common/incfiles/autoload.php');
ap_user_init();
wdja_cms_module_action();
$myhtml = wdja_cms_module();
echo $myhtml;
?>
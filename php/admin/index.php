<?php
require('../common/incfiles/autoload.php');
$mybody = wdja_cms_login();
$myhead = wdja_cms_web_head($admin_head);
$myfoot = wdja_cms_web_foot($admin_foot);
$myhtml = $myhead . $mybody . $myfoot;
echo $myhtml;
?>
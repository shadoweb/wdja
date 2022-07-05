<?php
require('../common/incfiles/autoload.php');
wdja_cms_islogin();
$source = ii_get_safecode($_GET['source']);
$ctype = ii_get_safecode($_GET['ctype']);
if(!ii_isnull($source)) $mybody = wdja_cms_pop_list($source,$ctype);
else $mybody = wdja_cms_pop_upload();
$myhead = wdja_cms_web_head('pop_head');
$myfoot = wdja_cms_web_foot('pop_foot');
$myhtml = $myhead . $mybody . $myfoot;
echo $myhtml;
?>
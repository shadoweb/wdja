<?php
/*
判断是否作为网站首页使用
*/
if (ii_strlen(dirname($_SERVER['PHP_SELF']))>1) {
  $nroute = 'child';
  $ngenre = ii_get_actual_genre(__FILE__, $nroute);
}else{
  $nroute = '';
  $ngenre = 'aboutus/contactus';
}
wdja_cms_init($nroute);
$nhead = $variable[ii_cvgenre($ngenre) . '.nhead'];
$nfoot = $variable[ii_cvgenre($ngenre) . '.nfoot'];
if (ii_isnull($nhead)) $nhead = $default_head;
if (ii_isnull($nfoot)) $nfoot = $default_foot;
$nuppath = $variable[ii_cvgenre($ngenre) . '.nuppath'];
$nuptype = $variable[ii_cvgenre($ngenre) . '.nuptype'];
$nclstype = $variable[ii_cvgenre($ngenre) . '.nclstype'];
$nsaveimages = $variable[ii_cvgenre($ngenre) . '.nsaveimages'];
$nupsimg = $variable[ii_cvgenre($ngenre) . '.thumbnail.upsimg'];
$nupsimgs = $variable[ii_cvgenre($ngenre) . '.thumbnail.upsimgs'];
?>
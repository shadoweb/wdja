<?php
$nroute = 'child';
$ngenre = ii_get_actual_genre(__FILE__, $nroute);
wdja_cms_init($nroute);
$nhead = $variable[ii_cvgenre($ngenre) . '.nhead'];
$nfoot = $variable[ii_cvgenre($ngenre) . '.nfoot'];
if (ii_isnull($nhead)) $nhead = $default_head;
if (ii_isnull($nfoot)) $nfoot = $default_foot;
$nbackuppath = $variable[ii_cvgenre($ngenre) . '.nbackuppath'];
$npagesize = $variable[ii_cvgenre($ngenre) . '.npagesize'];
$nlisttopx = $variable[ii_cvgenre($ngenre) . '.nlisttopx'];
$nurltype = $variable[ii_cvgenre($ngenre) . '.nurltype'];
$ncreatefolder = $variable[ii_cvgenre($ngenre) . '.ncreatefolder'];
$ncreatefiletype = $variable[ii_cvgenre($ngenre) . '.ncreatefiletype'];
?>
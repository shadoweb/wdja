<?php
$nroute = 'child';
$ngenre = ii_get_actual_genre(__FILE__, $nroute);
wdja_cms_init($nroute);
$nuppath = $variable[ii_cvgenre($ngenre) . '.nuppath'];
$nuptype = $variable[ii_cvgenre($ngenre) . '.nuptype'];
$ndatabase = $variable[ii_cvgenre($ngenre) . '.ndatabase'];
$nidfield = $variable[ii_cvgenre($ngenre) . '.nidfield'];
$nfpre = $variable[ii_cvgenre($ngenre) . '.nfpre'];
$nupsimg = $variable[ii_cvgenre($ngenre) . '.thumbnail.upsimg'];
$nupsimgs = $variable[ii_cvgenre($ngenre) . '.thumbnail.upsimgs'];
$npagesize = $variable[ii_cvgenre($ngenre) . '.npagesize'];
?>

<?php
$nroute = 'child';
$ngenre = ii_get_actual_genre(__FILE__, $nroute);
wdja_cms_init($nroute);
wdja_cms_admin_init();
$ndatabase = $sort_database;
$nidfield = $sort_idfield;
$nfpre = $sort_fpre;
$npagesize = $variable[ii_cvgenre($ngenre) . '.npagesize'];
$nuppath = $variable[ii_cvgenre($ngenre) . '.nuppath'];
$nuptype = $variable[ii_cvgenre($ngenre) . '.nuptype'];
$nupsimg = $variable[ii_cvgenre($ngenre) . '.thumbnail.upsimg'];
?>

<?php
$nroute = 'child';
$ngenre = ii_get_actual_genre(__FILE__, $nroute);
wdja_cms_init($nroute);
$nhead = $variable[ii_cvgenre($ngenre) . '.nhead'];
$nfoot = $variable[ii_cvgenre($ngenre) . '.nfoot'];
if (ii_isnull($nhead)) $nhead = $default_head;
if (ii_isnull($nfoot)) $nfoot = $default_foot;
$ndatabase = $address_database;
$nidfield = $address_idfield;
$nfpre = $address_fpre;
$npagesize = $variable[ii_cvgenre($ngenre) . '.npagesize'];
$nuppath = $variable[ii_cvgenre($ngenre) . '.nuppath'];
$nuptype = $variable[ii_cvgenre($ngenre) . '.nuptype'];
?>

<?php
$nroute = 'child';
$ngenre = ii_get_actual_genre(__FILE__, $nroute);
wdja_cms_init($nroute);
wdja_cms_admin_init();
$ndatabase = $variable['common.admin.ndatabase'];
$nidfield = $variable['common.admin.nidfield'];
$nfpre = $variable['common.admin.nfpre'];
$npagesize = $variable[ii_cvgenre($ngenre) . '.npagesize'];
?>

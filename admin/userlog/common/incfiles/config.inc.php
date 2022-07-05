<?php
$nroute = 'child';
$ngenre = ii_get_actual_genre(__FILE__, $nroute);
wdja_cms_init($nroute);
wdja_cms_admin_init();
$ndatabase = $variable['common.adminlog.ndatabase'];
$nidfield = $variable['common.adminlog.nidfield'];
$nfpre = $variable['common.adminlog.nfpre'];
$npagesize = $variable[ii_cvgenre($ngenre) . '.npagesize'];
?>

<?php
$nroute = 'child';
$ngenre = ii_get_actual_genre(__FILE__, $nroute);
wdja_cms_init($nroute);
$nhead = $variable[ii_cvgenre($ngenre) . '.nhead'];
$nfoot = $variable[ii_cvgenre($ngenre) . '.nfoot'];
if (ii_isnull($nhead)) $nhead = $default_head;
if (ii_isnull($nfoot)) $nfoot = $default_foot;
$ndatabase = $variable[ii_cvgenre($ngenre) . '.ndatabase'];
$nidfield = $variable[ii_cvgenre($ngenre) . '.nidfield'];
$nfpre = $variable[ii_cvgenre($ngenre) . '.nfpre'];
$ndatabase_data = $variable[ii_cvgenre($ngenre) . '.ndatabase_data'];
$nidfield_data = $variable[ii_cvgenre($ngenre) . '.nidfield_data'];
$nfpre_data = $variable[ii_cvgenre($ngenre) . '.nfpre_data'];
$npagesize = $variable[ii_cvgenre($ngenre) . '.npagesize'];
$nlisttopx = $variable[ii_cvgenre($ngenre) . '.nlisttopx'];
?>
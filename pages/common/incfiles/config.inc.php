<?php
$nroute = 'node';
$ngenre = ii_get_actual_genre(__FILE__, $nroute);
wdja_cms_init($nroute);
$nhead = $variable[ii_cvgenre($ngenre) . '.nhead'];
$nfoot = $variable[ii_cvgenre($ngenre) . '.nfoot'];
if (ii_isnull($nhead)) $nhead = $default_head;
if (ii_isnull($nfoot)) $nfoot = $default_foot;
$ndatabase = $variable[ii_cvgenre($ngenre) . '.ndatabase'];
$nidfield = $variable[ii_cvgenre($ngenre) . '.nidfield'];
$nfpre = $variable[ii_cvgenre($ngenre) . '.nfpre'];
$npagesize = $variable[ii_cvgenre($ngenre) . '.npagesize'];
$nuppath = $variable[ii_cvgenre($ngenre) . '.nuppath'];
$nuptype = $variable[ii_cvgenre($ngenre) . '.nuptype'];
$nlisttopx = $variable[ii_cvgenre($ngenre) . '.nlisttopx'];
$nclstype = $variable[ii_cvgenre($ngenre) . '.nclstype'];
$nsaveimages = $variable[ii_cvgenre($ngenre) . '.nsaveimages'];
$nupsimg = $variable[ii_cvgenre($ngenre) . '.thumbnail.upsimg'];
$nupsimgs = $variable[ii_cvgenre($ngenre) . '.thumbnail.upsimgs'];
$ntitles = $variable[ii_cvgenre($ngenre) . '.ntitles'];
if(ii_isnull($ntitles)) $ntitles = ii_itake('module.channel_title', 'lng');
$nkeywords = $variable[ii_cvgenre($ngenre) . '.nkeywords'];
$ndescription = $variable[ii_cvgenre($ngenre) . '.ndescription'];
?>
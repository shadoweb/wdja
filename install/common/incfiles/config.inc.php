<?php
global $variable, $nvalidate;
$nroute = 'node';
$ngenre = ii_get_actual_genre(__FILE__, $nroute);
ii_get_variable_init();
$nhead = 'clear_head';
$nfoot = 'clear_foot';
$ntitles = '安装程序';
$nkeywords = '网站安装程序';
$ndescription = 'WDJA网站内容管理系统安装程序是帮助您快速安装WDJA程序的功能.';
$installfile = __DIR__. DIRECTORY_SEPARATOR;
$installpath = str_replace('install/common/incfiles/','',str_replace('\\','/',$installfile));
$images_route = ii_itake('global.tpl_config.images_route', 'tpl');
$global_images_route = ii_get_actual_route($images_route);
$nskin = 'default';
?>
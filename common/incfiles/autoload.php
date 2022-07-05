<?php
$file = __DIR__. DIRECTORY_SEPARATOR;
$dopath = dirname($_SERVER["SCRIPT_FILENAME"]) . DIRECTORY_SEPARATOR;
$webpath = str_replace('common/incfiles/','',str_replace('\\','/',$file));
require_once($file.'const.inc.php');
require_once($file.'class.inc.php');
require_once($file.'function.inc.php');
require_once($file.'plus.inc.php');
require_once($file.'common.inc.php');
require_once($file.'admin.inc.php');
require_once($file.'upfiles.inc.php');
//主题函数文件引用
$current_theme = ii_isMobileAgent() ? MOBILE_SKIN : DEFAULT_SKIN;
$themes_guide = $webpath.'support/themes/themes.php';
$themes_function = $webpath.'support/themes/'.$current_theme.'/common/incfiles/theme.inc.php';
if(file_exists($themes_guide)) require_once($themes_guide);
if(file_exists($themes_function)) require_once($themes_function);
//模块配置文件引用
$config_file = $dopath.'common/incfiles/config.inc.php';
if(file_exists($config_file)) require_once($config_file);
//模块函数文件引用
$module_config_file = $dopath.'common/incfiles/' . (ii_isAdmin() ? 'manage_config.inc.php' : 'module_config.inc.php');
if(file_exists($module_config_file)) require_once($module_config_file);
//功能插件函数文件引用
ii_require_ApiFile($webpath);
//定时功能引用
require_once($webpath.'cron.php');
?>
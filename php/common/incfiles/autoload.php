<?php
$file = __DIR__. DIRECTORY_SEPARATOR;
$webpath = isset($_SERVER['DOCUMENT_ROOT'])?$_SERVER['DOCUMENT_ROOT']. DIRECTORY_SEPARATOR:(isset($_SERVER['APPL_PHYSICAL_PATH'])?trim($_SERVER['APPL_PHYSICAL_PATH'],"\\"):(isset($_['PATH_TRANSLATED'])?str_replace($_SERVER["PHP_SELF"]):str_replace(str_replace("/","\\",isset($_SERVER["PHP_SELF"])?$_SERVER["PHP_SELF"]:(isset($_SERVER["URL"])?$_SERVER["URL"]:$_SERVER["SCRIPT_NAME"])),"",isset($_SERVER["PATH_TRANSLATED"])?$_SERVER["PATH_TRANSLATED"]:$_SERVER["SCRIPT_FILENAME"]))). DIRECTORY_SEPARATOR;
$dopath = dirname($_SERVER["SCRIPT_FILENAME"]). DIRECTORY_SEPARATOR;
require_once($file.'const.inc.php');
require_once($file.'class.inc.php');
require_once($file.'function.inc.php');
require_once($file.'plus.inc.php');
require_once($file.'common.inc.php');
require_once($file.'admin.inc.php');
require_once($file.'upfiles.inc.php');
ii_require_ApiFile($webpath);
require_once($dopath.'common/incfiles/config.inc.php');
if (ii_isAdmin()) $cfile = $dopath.'common/incfiles/manage_config.inc.php';
else $cfile = $dopath.'common/incfiles/module_config.inc.php';
if(is_file($cfile)) require_once($cfile);
require_once($webpath.'cron.php');
?>
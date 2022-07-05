<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
function wdja_cms_module_index()
{
  global $ngenre;
  global $nvalidate;
  global $ntitles,$nkeywords,$ndescription;
  $tmpstr = ii_itake('module.index', 'tpl');
  mm_cntitle($ntitles);
  mm_cnkeywords($nkeywords);
  mm_cndescription($ndescription);
  $tmpstr = str_replace('{$genre}', $ngenre, $tmpstr);
  $tmpstr = mm_cvalhtml($tmpstr, $nvalidate, '{@recurrence_valcode}');
  $tmpstr = ii_creplace($tmpstr);
  if (!ii_isnull($tmpstr)) return $tmpstr;
  else return wdja_cms_module_index();
}

function wdja_cms_module_step1()
{
  global $ngenre;
  global $nvalidate;
  global $ntitles,$nkeywords,$ndescription;
  $phpver = PHP_VERSION;
  if($phpver < 7.0) $tmpstr = ii_itake('module.php_err', 'tpl');
  elseif(!function_exists('mysqli_connect')) $tmpstr = ii_itake('module.mysqli_err', 'tpl');
  elseif(!extension_loaded('gd')) $tmpstr = ii_itake('module.gd_err', 'tpl');
  else $tmpstr = ii_itake('module.step1', 'tpl');
  mm_cntitle($ntitles);
  mm_cnkeywords($nkeywords);
  mm_cndescription($ndescription);
  $tmpstr = str_replace('{$genre}', $ngenre, $tmpstr);
  $tmpstr = str_replace('{$ip}', ii_get_client_ip(), $tmpstr);
  $tmpstr = mm_cvalhtml($tmpstr, $nvalidate, '{@recurrence_valcode}');
  $tmpstr = ii_creplace($tmpstr);
  if (!ii_isnull($tmpstr)) return $tmpstr;
  else return wdja_cms_module_index();
}

function wdja_cms_module_step2()
{
  global $ngenre;
  global $nvalidate;
  global $ntitles,$nkeywords,$ndescription;
  $tmpstr = ii_itake('module.step2', 'tpl');
  mm_cntitle($ntitles);
  mm_cnkeywords($nkeywords);
  mm_cndescription($ndescription);
  $tmpstr = str_replace('{$genre}', $ngenre, $tmpstr);
  $tmpstr = mm_cvalhtml($tmpstr, $nvalidate, '{@recurrence_valcode}');
  $tmpstr = ii_creplace($tmpstr);
  if (!ii_isnull($tmpstr)) return $tmpstr;
  else return wdja_cms_module_index();
}

function wdja_cms_module_step3()
{
  global $ngenre;
  global $nvalidate;
  global $ntitles,$nkeywords,$ndescription,$installpath;
  $tmpstr = ii_itake('module.step3', 'tpl');
  $dbhost = ii_get_request('dbhost');
  $dbuser = ii_get_request('dbuser');
  $dbpass = ii_get_request('dbpass');
  $dbname = ii_get_request('dbname');
  $subweb_switch = ii_get_request('subweb_switch');
  $subweb_folder = ii_get_request('subweb_folder');
  if($subweb_switch == 1){
    edit_global_config('subweb_switch',$subweb_switch);
    edit_global_config('subweb_folder',$subweb_folder);
  }
  $adminuser = ii_get_request('adminuser');
  $adminpass = ii_get_request('adminpass');
  $dbconn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
  if (mysqli_connect_errno()){
      $tmpstr = ii_itake('module.mysql_err', 'tpl');
  }else{
      $ver = mysqli_get_server_info($dbconn);
      if ($ver < 5.5) $tmpstr = ii_itake('module.mysql_ver', 'tpl');
      else update_config(array('DB_HOST' => $dbhost,'DB_USERNAME' => $dbuser,'DB_PASSWORD' => $dbpass,'DB_DATABASE' => $dbname));
  } 
  mm_cntitle($ntitles);
  mm_cnkeywords($nkeywords);
  mm_cndescription($ndescription);
  $tmpstr = str_replace('{$genre}', $ngenre, $tmpstr);
  $tmpstr = str_replace('{$ver}', $ver, $tmpstr);
  $tmpstr = str_replace('{$dbhost}', $dbhost, $tmpstr);
  $tmpstr = str_replace('{$dbuser}', $dbuser, $tmpstr);
  $tmpstr = str_replace('{$dbpass}', $dbpass, $tmpstr);
  $tmpstr = str_replace('{$dbname}', $dbname, $tmpstr);
  $tmpstr = str_replace('{$subweb_switch}', $subweb_switch ? '是' : '否', $tmpstr);
  $tmpstr = str_replace('{$subweb_folder}', $subweb_folder, $tmpstr);
  $tmpstr = str_replace('{$adminuser}', $adminuser, $tmpstr);
  $tmpstr = str_replace('{$adminpass}', $adminpass, $tmpstr);
  $tmpstr = mm_cvalhtml($tmpstr, $nvalidate, '{@recurrence_valcode}');
  $tmpstr = ii_creplace($tmpstr);
  if (!ii_isnull($tmpstr)) return $tmpstr;
  else return wdja_cms_module_index();
}

function wdja_cms_module_step4()
{
  global $ngenre,$conn;
  global $variable;
  global $ntitles,$nkeywords,$ndescription,$installpath;
  $tmpstr = ii_itake('module.step4', 'tpl');
  mm_cntitle($ntitles);
  mm_cnkeywords($nkeywords);
  mm_cndescription($ndescription);
  $dbhost = ii_get_request('dbhost');
  $dbuser = ii_get_request('dbuser');
  $dbpass = ii_get_request('dbpass');
  $dbname = ii_get_request('dbname');
  $adminuser = ii_get_request('adminuser');
  $adminpass = ii_get_request('adminpass');
  $dbconn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
  
  //数据库还原
  install_database($dbconn);
  $ndatabase = $variable['common.admin.ndatabase'];
  $nfpre = $variable['common.admin.nfpre'];
  if (mysqli_connect_errno()) die('MYSQL.Connect.Error!');
  //修改后台帐号密码
  $tsqlstr = "update $ndatabase set " . ii_cfnames($nfpre,'name') . "='" . $adminuser ."',". ii_cfnames($nfpre,'pword') . "='" . ii_md5($adminpass) . "' where ".ii_cfnames($nfpre,'name') . "='admin'";
  $trs = ii_conn_query($tsqlstr, $dbconn);
  if (!$trs)
  {
      $adminuser = 'admin';
      $adminpass = 'admin';
  }
  install_success(get_weburl().'/admin');
  $tmpstr = str_replace('{$weburl}', get_weburl(), $tmpstr);
  $tmpstr = str_replace('{$adminuser}', $adminuser, $tmpstr);
  $tmpstr = str_replace('{$adminpass}', $adminpass, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  if (!ii_isnull($tmpstr)) return $tmpstr;
  else return wdja_cms_module_index();
}

function wdja_cms_module()
{
  switch($_GET['type'])
  {
    case 'index':
      return wdja_cms_module_index();
      break;
    case 'step1':
      return wdja_cms_module_step1();
      break;
    case 'step2':
      return wdja_cms_module_step2();
      break;
    case 'step3':
      return wdja_cms_module_step3();
      break;
    case 'step4':
      return wdja_cms_module_step4();
      break;
    default:
      return wdja_cms_module_index();
      break;
  }
}
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
?>
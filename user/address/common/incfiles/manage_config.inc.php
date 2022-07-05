<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
wdja_cms_admin_init();
$nsearch = 'name,username,phone';
$ncontrol = 'select,delete';

function pp_manage_navigation()
{
  return ii_ireplace('manage.navigation', 'tpl');
}

function wdja_cms_admin_manage_editdisp()
{
  global $conn;
  global $ndatabase, $nidfield, $nfpre;
  $tid = ii_get_num($_GET['id']);
  $tbackurl = $_GET['backurl'];
  $tckstr = 'name:' . ii_itake('manage.name', 'lng');
  $tary = explode(',', $tckstr);
  $Err = array();
  foreach ($tary as $val)
  {
    $tvalary = explode(':', $val);
    if (ii_isnull($_POST[$tvalary[0]])) $Err[count($Err)] = str_replace('[]', '[' . $tvalary[1] . ']', ii_itake('global.lng_error.insert_empty', 'lng'));
  }
  if (is_array($Err) && count($Err) > 0) wdja_cms_admin_msg($Err[0], $tbackurl, 1);
  $tname = ii_left(ii_cstr($_POST['name']), 50);
  $tdictid = ii_left(ii_cstr($_POST['dictid']), 255);
  $taddress = ii_left(ii_cstr($_POST['address']), 255);
  $tcode = ii_left(ii_cstr($_POST['code']), 50);
  $tphone = ii_left(ii_cstr($_POST['phone']), 50);
  $temail = ii_left(ii_cstr($_POST['email']), 50);
  $ttime = ii_now();
  $tsqlstr = "update $ndatabase set 
      " . ii_cfname('name') . "='$tname',
      " . ii_cfname('dictid') . "='$tdictid',
      " . ii_cfname('address') . "='$taddress',
      " . ii_cfname('code') . "='$tcode',
      " . ii_cfname('phone') . "='$tphone',
      " . ii_cfname('email') . "='$temail',
      " . ii_cfname('update') . "='$ttime'
      where ".$nidfield."=".$tid;
  $trs = ii_conn_query($tsqlstr, $conn);
  if ($trs)
  {
    wdja_cms_admin_msg(ii_itake('manage.editsucceed', 'lng'), $tbackurl, 1);
  }
  else
  {
    wdja_cms_admin_msg(ii_itake('manage.editerr', 'lng'), $tbackurl, 1);
  }
}

function wdja_cms_admin_manage_deletedisp()
{
  global $conn, $slng;
  global $ndatabase, $nidfield, $nfpre;
  $tid = ii_get_num($_GET['id']);
  $tbackurl = $_GET['backurl'];
  $tsqlstr = "select * from $ndatabase where $nidfield=$tid";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs)
  {
        mm_dbase_delete($ndatabase, $nidfield, $tid);
        wdja_cms_admin_msg(ii_itake('manage.deletesucceed', 'lng'), $tbackurl, 1);
  }
  else
  {
    wdja_cms_admin_msg(ii_itake('manage.deleteerr', 'lng'), $tbackurl, 1);
  }
}



function wdja_cms_admin_manage_action()
{
  global $sgenre, $slng;
  global $ndatabase, $nidfield, $nfpre, $ncontrol;
  $taction = $_GET['action'];
  $tappstr = 'sys_sort_' . $sgenre . '_' . $slng;
  if (!ii_isnull($taction)) @ii_cache_remove($tappstr);
  switch($taction)
  {
    case 'edit':
      wdja_cms_admin_manage_editdisp();
      break;
    case 'delete':
      wdja_cms_admin_manage_deletedisp();
      break;
    case 'control':
      wdja_cms_admin_controldisp($ndatabase, $nidfield, $nfpre, $ncontrol);
      break;
  }
}

function wdja_cms_admin_manage_edit()
{
  global $conn;
  global $ndatabase, $nidfield, $nfpre;
  $tid = ii_get_num($_GET['id']);
  $tsqlstr = "select * from $ndatabase where $nidfield=$tid";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs)
  {
    $tmpstr = ii_itake('manage.edit', 'tpl');
    $tmpstr = str_replace('{$id}', $trs[$nidfield], $tmpstr);
    $tmpstr = str_replace('{$name}', ii_htmlencode($trs[ii_cfname('name')]), $tmpstr);
    $tmpstr = str_replace('{$dictid}', ii_htmlencode($trs[ii_cfname('dictid')]), $tmpstr);
    $tmpstr = str_replace('{$address}', ii_htmlencode($trs[ii_cfname('address')]), $tmpstr);
    $tmpstr = str_replace('{$code}', ii_htmlencode($trs[ii_cfname('code')]), $tmpstr);
    $tmpstr = str_replace('{$phone}', ii_htmlencode($trs[ii_cfname('phone')]), $tmpstr);
    $tmpstr = str_replace('{$email}', ii_htmlencode($trs[ii_cfname('email')]), $tmpstr);
    $tmpstr = str_replace('{$username}', ii_htmlencode($trs[ii_cfname('username')]), $tmpstr);
    $tmpstr = str_replace('{$time}', ii_get_date($trs[ii_cfname('time')]), $tmpstr);
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
  }
  else
  {
    mm_client_alert(ii_itake('manage.editerr', 'lng'), -1);
  }
}

function wdja_cms_admin_manage_list()
{
  global $conn;
  global $ndatabase, $nidfield, $nfpre, $npagesize;
  global $slng;
  $toffset = ii_get_num($_GET['offset']);
  $search_field = ii_get_safecode($_GET['field']);
  $search_keyword = ii_get_safecode($_GET['keyword']);
  $tmpstr = ii_itake('manage.list', 'tpl');
  $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_ida}');
  $tmprstr = '';
  $tsqlstr = "select * from $ndatabase where $nidfield>0";
  if ($search_field == 'name') $tsqlstr .= " and " . ii_cfname('name') . " like '%" . $search_keyword . "%'";
  if ($search_field == 'phone') $tsqlstr .= " and " . ii_cfname('phone') . " like '%" . $search_keyword . "%'";
  if ($search_field == 'username') $tsqlstr .= " and " . ii_cfname('username') . " like '%" . $search_keyword . "%'";
  $tsqlstr .= " order by " . ii_cfname('order') . " asc";
  $tcp = new cc_cutepage;
  $tcp -> id = $nidfield;
  $tcp -> sqlstr = $tsqlstr;
  $tcp -> offset = $toffset;
  $tcp -> pagesize = $npagesize;
  $tcp -> init();
  $trsary = $tcp -> get_rs_array();
  $font_disabled = ii_itake('global.tpl_config.font_disabled', 'tpl');
  $tdeletenotice = ii_itake('manage.deletenotice', 'lng');
  if (is_array($trsary))
  {
    foreach($trsary as $trs)
    {
      $tmptstr = str_replace('{$name}', ii_htmlencode($trs[ii_cfname('name')]), $tmpastr);
      $tmptstr = str_replace('{$address}', ii_htmlencode($trs[ii_cfname('address')]), $tmptstr);
      $tmptstr = str_replace('{$code}', ii_htmlencode($trs[ii_cfname('code')]), $tmptstr);
      $tmptstr = str_replace('{$phone}', ii_htmlencode($trs[ii_cfname('phone')]), $tmptstr);
      $tmptstr = str_replace('{$email}', ii_htmlencode($trs[ii_cfname('email')]), $tmptstr);
      $tmptstr = str_replace('{$username}', ii_htmlencode($trs[ii_cfname('username')]), $tmptstr);
      $tmptstr = str_replace('{$time}', ii_get_date($trs[ii_cfname('time')]), $tmptstr);
      $tmptstr = str_replace('{$id}', $trs[$nidfield], $tmptstr);
      $tmptstr = ii_creplace($tmptstr);
      $tmprstr .= $tmptstr;
    }
  }
  $tmpstr = str_replace('{$cpagestr}', $tcp -> get_pagenum(), $tmpstr);
  $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
  $tmpstr = str_replace('{$id}', $tid, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function wdja_cms_admin_manage()
{
  switch($_GET['type'])
  {
    case 'edit':
      return wdja_cms_admin_manage_edit();
      break;
    case 'list':
      return wdja_cms_admin_manage_list();
      break;
    default:
      return wdja_cms_admin_manage_list();
      break;
  }
}
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
?>
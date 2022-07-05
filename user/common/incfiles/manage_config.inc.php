<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
wdja_cms_admin_init();
$nsearch = 'username,id';
$ncontrol = 'select,lock,delete';

function pp_manage_navigation()
{
  return ii_ireplace('manage.navigation', 'tpl');
}

function pp_manage_group_select($utype) {
  $tmprstr = '';
  $tmpastr = '<option value="{$keyword}" {$selected}>{$topic}</option>';
  $tary = ii_itake('sel_group.all', 'sel', 1);
  if (is_array($tary))
  {
    foreach ($tary as $key => $val)
    { 
      if ($utype == $key && !ii_isnull($utype)) $selected = 'selected';
      else $selected = '';
      $tmptstr = str_replace('{$selected}', $selected, $tmpastr);
      $tmptstr = str_replace('{$topic}', $val, $tmptstr);
      $tmptstr = str_replace('{$keyword}',  $key, $tmptstr);
      $tmprstr .= $tmptstr;
    }
  }
  return $tmprstr;
}

function wdja_cms_admin_manage_adddisp()
{
  global $ngenre;
  global $conn;
  global $ndatabase, $nidfield, $nfpre;
  $tbackurl = $_GET['backurl'];
  $tckstr = 'username:' . ii_itake('config.username', 'lng');
  $tary = explode(',', $tckstr);
  $Err = array();
  foreach ($tary as $val)
  {
    $tvalary = explode(':', $val);
    if (ii_isnull($_POST[$tvalary[0]])) $Err[count($Err)] = str_replace('[]', '[' . $tvalary[1] . ']', ii_itake('global.lng_error.insert_empty', 'lng'));
  }
  if (is_array($Err) && count($Err) > 0) wdja_cms_admin_msg($Err[0], $tbackurl, 1);
  $tusername = ii_left(ii_cstr($_POST['username']), 50);
  $tsqlstr = "select * from $ndatabase where " . ii_cfname('username') . "='$tusername'";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs) wdja_cms_admin_msg(ii_itake('global.lng_public.exist', 'lng'), $tbackurl, 1);
  else
  {
    $tsqlstr = "insert into $ndatabase (
    " . ii_cfname('username') . ",
    " . ii_cfname('password') . ",
    " . ii_cfname('email') . ",
    " . ii_cfname('name') . ",
    " . ii_cfname('sex') . ",
    " . ii_cfname('qq') . ",
    " . ii_cfname('phone') . ",
    " . ii_cfname('address') . ",
    " . ii_cfname('money') . ",
    " . ii_cfname('emoney') . ",
    " . ii_cfname('integral') . ",
    " . ii_cfname('utype') . ",
    " . ii_cfname('lock') . ",
    " . ii_cfname('face') . ",
    " . ii_cfname('forum_admin') . ",
    " . ii_cfname('sign') . ",
    " . ii_cfname('time') . "
    ) values (
    '$tusername',
    '" . ii_md5($_POST['password']) . "',
    '" . ii_left(ii_cstr($_POST['email']), 50) . "',
    '" . ii_left(ii_cstr($_POST['name']), 50) . "',
    '" . ii_left(ii_cstr($_POST['sex']), 50) . "',
    '" . ii_left(ii_cstr($_POST['qq']), 50) . "',
    '" . ii_left(ii_cstr($_POST['phone']), 50) . "',
    '" . ii_left(ii_cstr($_POST['address']), 255) . "',
    " . ii_get_num($_POST['money']) . ",
    " . ii_get_num($_POST['emoney']) . ",
    " . ii_get_num($_POST['integral']) . ",
    " . ii_get_num($_POST['utype']) . ",
    " . ii_get_num($_POST['lock']) . ",
    '" . ii_left(ii_cstr($_POST['face']), 255) . "',
    " . ii_get_num($_POST['forum_admin']) . ",
    '" . ii_left(ii_cstr($_POST['sign']), 255) . "',
    '" . ii_now() . "'
    )";
    $trs = ii_conn_query($tsqlstr, $conn);
    if ($trs) {
        $userid = ii_conn_insert_id($conn);
        wdja_cms_admin_msg(ii_itake('global.lng_public.add_succeed', 'lng'), $tbackurl, 1);
    }else{
      wdja_cms_admin_msg(ii_itake('global.lng_public.add_failed', 'lng'), $tbackurl, 1);
    }
  }
}

function wdja_cms_admin_manage_editdisp()
{
  global $ngenre;
  global $conn;
  global $ndatabase, $nidfield, $nfpre;
  $tbackurl = $_GET['backurl'];
  $tid = ii_get_num($_GET['id']);
  if (!($tid == 0))
  {
    $tsqlstr = "update $ndatabase set ";
    if (!ii_isnull($_POST['password'])) $tsqlstr .= ii_cfname('password') . "='" . ii_md5($_POST['password']) . "',";
    $tsqlstr .= ii_cfname('email') . "='" . ii_left(ii_cstr($_POST['email']), 50) . "',
    " . ii_cfname('name') . "='" . ii_left(ii_cstr($_POST['name']), 50) . "',
    " . ii_cfname('sex') . "='" . ii_left(ii_cstr($_POST['sex']), 50) . "',
    " . ii_cfname('qq') . "='" . ii_left(ii_cstr($_POST['qq']), 50) . "',
    " . ii_cfname('phone') . "='" . ii_left(ii_cstr($_POST['phone']), 50) . "',
    " . ii_cfname('address') . "='" . ii_left(ii_cstr($_POST['address']), 255) . "',
    " . ii_cfname('emoney') . "=" . ii_get_num($_POST['emoney']) . ",
    " . ii_cfname('utype') . "=" . ii_get_num($_POST['utype']) . ",
    " . ii_cfname('lock') . "=" . ii_get_num($_POST['lock']) . ",
    " . ii_cfname('face') . "='" . ii_left(ii_cstr($_POST['face']), 255) . "',
    " . ii_cfname('forum_admin') . "=" . ii_get_num($_POST['forum_admin']) . ",
    " . ii_cfname('sign') . "='" . ii_left(ii_cstr($_POST['sign']), 255) . "'
    where $nidfield=$tid";
    $trs = ii_conn_query($tsqlstr, $conn);
    if ($trs) wdja_cms_admin_msg(ii_itake('global.lng_public.edit_succeed', 'lng'), $tbackurl, 1);
    else wdja_cms_admin_msg(ii_itake('global.lng_public.edit_failed', 'lng'), $tbackurl, 1);
  }
  else
  {
    wdja_cms_admin_msg(ii_itake('global.lng_public.sudd', 'lng'), $tbackurl, 1);
  }
}

function wdja_cms_admin_manage_action()
{
  global $ndatabase, $nidfield, $nfpre, $ncontrol;
  switch($_GET['action'])
  {
    case 'add':
      wdja_cms_admin_manage_adddisp();
      break;
    case 'edit':
      wdja_cms_admin_manage_editdisp();
      break;
    case 'delete':
      wdja_cms_admin_deletedisp($ndatabase, $nidfield);
      break;
    case 'control':
      wdja_cms_admin_controldisp($ndatabase, $nidfield, $nfpre, $ncontrol);
      break;
    case 'upload':
      uu_upload_files();
      break;
  }
}

function wdja_cms_admin_manage_add()
{
  global $nupsimg;
  $tmpstr = ii_itake('manage.add', 'tpl');
  $tmpstr = str_replace('{$upsimg}', $nupsimg, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function wdja_cms_admin_manage_edit()
{
  global $conn;
  global $ndatabase, $nidfield, $nfpre, $nupsimg;
  $tid = ii_get_num($_GET['id']);
  $tsqlstr = "select * from $ndatabase where $nidfield=$tid";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs)
  {
    $tmpstr = ii_itake('manage.edit', 'tpl');
    foreach ($trs as $key => $val)
    {
      $tkey = ii_get_lrstr($key, '_', 'rightr');
      $GLOBALS['RS_' . $tkey] = $val;
      $tmpstr = str_replace('{$' . $tkey . '}', ii_htmlencode($val), $tmpstr);
    }
    $tmpstr = str_replace('{$id}', $trs[$nidfield], $tmpstr);
    $tmpstr = str_replace('{$upsimg}', $nupsimg, $tmpstr);
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
  }
  else mm_client_alert(ii_itake('global.lng_public.sudd', 'lng'), -1);
}

function wdja_cms_admin_manage_list()
{
  global $conn;
  global $ngenre, $npagesize;
  global $ndatabase, $nidfield, $nfpre;
  $toffset = ii_get_num($_GET['offset']);
  $tutype = ii_get_num($_GET['utype'],-1);
  $search_field = ii_get_safecode($_GET['field']);
  $search_keyword = ii_get_safecode($_GET['keyword']);
  $tmpstr = ii_itake('manage.list', 'tpl');
  $tmprstr = '';
  $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_idb}');
  $tsqlstr = "select * from $ndatabase where $nidfield>0";
  if ($search_field == 'username') $tsqlstr .= " and " . ii_cfname('username') . " like '%" . $search_keyword . "%'";
  if ($search_field == 'lock') $tsqlstr .= " and " . ii_cfname('lock') . "=" . ii_get_num($search_keyword);
  if (!ii_isnull($tutype) && $tutype != -1) $tsqlstr .= " and " . ii_cfname('utype') . "='" .$tutype."'";
  if ($search_field == 'id') $tsqlstr .= " and $nidfield=" . ii_get_num($search_keyword);
  $tsqlstr .= " order by $ndatabase." . ii_cfname('time') . " desc";
  $tcp = new cc_cutepage;
  $tcp -> id = $nidfield;
  $tcp -> sqlstr = $tsqlstr;
  $tcp -> offset = $toffset;
  $tcp -> pagesize = $npagesize;
  $tcp -> init();
  $trsary = $tcp -> get_rs_array();
  $font_disabled = ii_itake('global.tpl_config.font_disabled', 'tpl');
  if (is_array($trsary))
  {
    foreach($trsary as $trs)
    {
      $tusername = ii_htmlencode($trs[ii_cfname('username')]);
      if ($trs[ii_cfname('lock')] == 1) $tusername = str_replace('{$explain}', $tusername, $font_disabled);
      $tmptstr = str_replace('{$username}', $tusername, $tmpastr);
      $tmptstr = str_replace('{$usernamestr}', ii_encode_scripts(ii_htmlencode($trs[ii_cfname('username')])), $tmptstr);
      $tmptstr = str_replace('{$name}', ii_htmlencode($trs[ii_cfname('name')]), $tmptstr);
      $tmptstr = str_replace('{$sex}', ii_itake('global.sel_sex.' . ii_get_num($trs[ii_cfname('sex')]), 'sel'), $tmptstr);
      $tmptstr = str_replace('{$group}', ii_itake('sel_group.' . ii_get_num($trs[ii_cfname('utype')]), 'sel'), $tmptstr);
      $tmptstr = str_replace('{$money}', ii_htmlencode($trs[ii_cfname('money')]), $tmptstr);
      $tmptstr = str_replace('{$phone}', ii_htmlencode($trs[ii_cfname('phone')]), $tmptstr);
      $tmptstr = str_replace('{$qq}', ii_htmlencode($trs[ii_cfname('qq')]), $tmptstr);
      $tmptstr = str_replace('{$email}', ii_htmlencode($trs[ii_cfname('email')]), $tmptstr);
      $tmptstr = str_replace('{$time}', ii_get_date($trs[ii_cfname('time')]), $tmptstr);
      $tmptstr = str_replace('{$id}', ii_get_num($trs[$nidfield]), $tmptstr);
      $tmprstr .= $tmptstr;
    }
  }
  $tmpstr = str_replace('{$tutype}', $tutype, $tmpstr);
  $tmpstr = str_replace('{$cpagestr}', $tcp -> get_pagenum(), $tmpstr);
  $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function wdja_cms_admin_manage()
{
  switch($_GET['type'])
  {
    case 'add':
      return wdja_cms_admin_manage_add();
      break;
    case 'edit':
      return wdja_cms_admin_manage_edit();
      break;
    case 'list':
      return wdja_cms_admin_manage_list();
      break;
    case 'upload':
      uu_upload_files_html('upload_html');
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

<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
wdja_cms_admin_init();
$nsearch = 'url';
$ncontrol = 'select,delete';

function pp_manage_navigation()
{
  return ii_ireplace('manage.navigation', 'tpl');
}

function wdja_cms_admin_manage_adddisp()
{
  global $ngenre;
  global $conn;
  global $ndatabase, $nidfield, $nfpre;
  $tbackurl = $_GET['backurl'];
  $turl = ii_cstr($_POST['url']);
  $tckstr = 'url:' . ii_itake('manage.url', 'lng');
  $tary = explode(',', $tckstr);
  $Err = array();
  foreach ($tary as $val)
  {
    $tvalary = explode(':', $val);
    if (ii_isnull($_POST[$tvalary[0]])) $Err[count($Err)] = str_replace('[]', '[' . $tvalary[1] . ']', ii_itake('global.lng_error.insert_empty', 'lng'));
  }
  if (is_array($Err) && count($Err) > 0) wdja_cms_admin_msg($Err[0], $tbackurl, 1);
  if (!(ii_isnull($turl)))
  {
    $tsqlstr = "insert into $ndatabase (
    " . ii_cfname('url') . ",
    " . ii_cfname('image') . ",
    " . ii_cfname('title') . ",
    " . ii_cfname('author') . ",
    " . ii_cfname('content') . ",
    " . ii_cfname('replace') . ",
    " . ii_cfname('hidden') . ",
    " . ii_cfname('update') . ",
    " . ii_cfname('time') . "
    ) values (
    '" . ii_left($turl, 255) . "',
    '" . ii_left(ii_cstr($_POST['image']), 255) . "',
    '" . ii_left(ii_cstr($_POST['title']), 255) . "',
    '" . ii_left(ii_cstr($_POST['author']), 255) . "',
    '" . ii_left(ii_cstr($_POST['content']), 255) . "',
    '" . ii_left(ii_cstr($_POST['replace']), 255) . "',
    " . ii_get_num($_POST['hidden']) . ",
    '" . ii_now() . "',
    '" . ii_now() . "'
    )";
    $trs = ii_conn_query($tsqlstr, $conn);
    if ($trs) wdja_cms_admin_msg(ii_itake('global.lng_public.add_succeed', 'lng'), $tbackurl, 1);
    else wdja_cms_admin_msg(ii_itake('global.lng_public.add_failed', 'lng'), $tbackurl, 1);
  }
  else
  {
    wdja_cms_admin_msg(ii_itake('global.lng_public.sudd', 'lng'), $tbackurl, 1);
  }
}

function wdja_cms_admin_manage_editdisp()
{
  global $conn;
  global $ndatabase, $nidfield, $nfpre;
  $tbackurl = $_GET['backurl'];
  $turl = ii_cstr($_POST['url']);
  $tid = ii_get_num($_GET['id']);
  $tckstr = 'url:' . ii_itake('manage.url', 'lng');
  $tary = explode(',', $tckstr);
  $Err = array();
  foreach ($tary as $val)
  {
    $tvalary = explode(':', $val);
    if (ii_isnull($_POST[$tvalary[0]])) $Err[count($Err)] = str_replace('[]', '[' . $tvalary[1] . ']', ii_itake('global.lng_error.insert_empty', 'lng'));
  }
  if (is_array($Err) && count($Err) > 0) wdja_cms_admin_msg($Err[0], $tbackurl, 1);
  if (!(ii_isnull($turl)))
  {
    $tsqlstr = "update $ndatabase set
    " . ii_cfname('url') . "='" . ii_left($turl, 255) . "',
    " . ii_cfname('image') . "='" . ii_left(ii_cstr($_POST['image']), 255) . "',
    " . ii_cfname('title') . "='" . ii_left(ii_cstr($_POST['title']), 255) . "',
    " . ii_cfname('author') . "='" . ii_left(ii_cstr($_POST['author']), 255) . "',
    " . ii_cfname('content') . "='" . ii_left(ii_cstr($_POST['content']), 255) . "',
    " . ii_cfname('replace') . "='" . ii_left(ii_cstr($_POST['replace']), 255) . "',
    " . ii_cfname('hidden') . "=" . ii_get_num($_POST['hidden']) . ",
    " . ii_cfname('time') . "='" . ii_get_date(ii_cstr($_POST['time'])) . "',
    " . ii_cfname('update') . "='" . ii_now() . "'
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
  }
}

function wdja_cms_admin_manage_add()
{
  $tmpstr = ii_ireplace('manage.add', 'tpl');
  return $tmpstr;
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
    foreach ($trs as $key => $val)
    {
      $tkey = ii_get_lrstr($key, '_', 'rightr');
      $GLOBALS['RS_' . $tkey] = $val;
      $tmpstr = str_replace('{$' . $tkey . '}', ii_htmlencode($val), $tmpstr);
    }
    $tmpstr = str_replace('{$id}', $trs[$nidfield], $tmpstr);
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
  }
  else mm_client_alert(ii_itake('global.lng_public.sudd', 'lng'), -1);
}

function wdja_cms_admin_manage_list()
{
  global $conn;
  global $ndatabase, $nidfield, $nfpre, $npagesize;
  $toffset = ii_get_num($_GET['offset']);
  $search_field = ii_get_safecode($_GET['field']);
  $search_keyword = ii_get_safecode($_GET['keyword']);
  $tmpstr = ii_itake('manage.list', 'tpl');
  $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_ida}');
  $tmprstr = '';
  $tsqlstr = "select * from $ndatabase where $nidfield>0";
  if ($search_field == 'url') $tsqlstr .= " and " . ii_cfname('url') . " like '%" . $search_keyword . "%'";
  $tsqlstr .= " order by $ndatabase." . ii_cfname('time') . " desc";
  $tcp = new cc_cutepage;
  $tcp -> id = $nidfield;
  $tcp -> sqlstr = $tsqlstr;
  $tcp -> offset = $toffset;
  $tcp -> pagesize = $npagesize;
  $tcp -> init();
  $trsary = $tcp -> get_rs_array();
  if (!(ii_isnull($search_keyword)) && $search_field == 'topic') $font_red = ii_itake('global.tpl_config.font_red', 'tpl');
  if (is_array($trsary))
  {
    foreach($trsary as $trs)
    {
      $turl = ii_htmlencode($trs[ii_cfname('url')]);
      if (isset($font_red))
      {
        $font_red = str_replace('{$explain}', $search_keyword, $font_red);
        $turl = str_replace($search_keyword, $font_red, $turl);
      }
      $tmptstr = str_replace('{$url}', $turl, $tmpastr);
      $tmptstr = str_replace('{$urlstr}', ii_encode_scripts(ii_htmlencode($trs[ii_cfname('url')])), $tmptstr);
      $tmptstr = str_replace('{$image}', ii_htmlencode($trs[ii_cfname('image')]), $tmptstr);
      $tmptstr = str_replace('{$title}', ii_htmlencode($trs[ii_cfname('title')]), $tmptstr);
      $tmptstr = str_replace('{$content}', ii_htmlencode($trs[ii_cfname('content')]), $tmptstr);
      $tmptstr = str_replace('{$hidden}',ii_itake('global.sel_yesno.'.ii_get_num($trs[ii_cfname('hidden')]), 'lng'), $tmptstr);
      $tmptstr = str_replace('{$time}', ii_get_date($trs[ii_cfname('time')]), $tmptstr);
      $tmptstr = str_replace('{$id}', ii_get_num($trs[$nidfield]), $tmptstr);
      $tmprstr .= $tmptstr;
    }
  }
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
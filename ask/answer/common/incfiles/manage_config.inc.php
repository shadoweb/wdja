<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
wdja_cms_admin_init();
$nsearch = 'author,content,id';
$ncontrol = 'select,hidden,delete';
$ncttype = ii_get_num($_GET['htype'], -1);
if ($ncttype == -1) $ncttype = 1;

function pp_manage_navigation()
{
  return ii_ireplace('manage.navigation', 'tpl');
}

function wdja_cms_admin_manage_adddisp()
{
  global $ngenre, $nlng;
  global $conn;
  global $ndatabase, $nidfield, $nfpre;
  $tbackurl = $_GET['backurl'];
  $tauthor = ii_cstr($_POST['author']);
  if (!(ii_isnull($tauthor)))
  {
    $tsqlstr = "insert into $ndatabase (
    " . ii_cfname('author') . ",
    " . ii_cfname('authorip') . ",
    " . ii_cfname('content') . ",
    " . ii_cfname('time') . ",
    " . ii_cfname('update') . ",
    " . ii_cfname('fid') . ",
    " . ii_cfname('hidden') . ",
    " . ii_cfname('lng') . "
    ) values (
    '" . ii_left($tauthor, 50) . "',
    '" . ii_left(ii_cstr($_POST['authorip']), 50) . "',
    '" . ii_left(ii_cstr($_POST['content']), 100000) . "',
    '" . ii_now() . "',
    '" . ii_now() . "',
    " . ii_get_num($_POST['ask_sid']) . ",
    " . ii_get_num($_POST['hidden']) . ",
    '$nlng'
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

function wdja_cms_admin_manage_good()
{
  global $conn;
  global $ndatabase, $nidfield, $nfpre;
  $tbackurl = $_GET['backurl'];
  $tid = ii_get_num($_GET['id']);
  if (!($tid == 0))
  {
    $tsqlstr = "update $ndatabase set " . ii_cfname('good') . "=1 where $nidfield=$tid";
    $trs = ii_conn_query($tsqlstr, $conn);
    if ($trs) wdja_cms_admin_msg(ii_itake('global.lng_public.edit_succeed', 'lng'), $tbackurl, 1);
    else wdja_cms_admin_msg(ii_itake('global.lng_public.edit_failed', 'lng'), $tbackurl, 1);
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
  $tbackurl2 = $_GET['backurl2'];
  if (!ii_isnull($tbackurl2)) $tbackurl = $tbackurl2;
  $tid = ii_get_num($_GET['id']);
  if (!($tid == 0))
  {
    $tsqlstr = "update $ndatabase set
    " . ii_cfname('author') . "='" . ii_left(ii_cstr($_POST['author']), 50) . "',
    " . ii_cfname('authorip') . "='" . ii_left(ii_cstr($_POST['authorip']), 50) . "',
    " . ii_cfname('content') . "='" . ii_left(ii_cstr($_POST['content']), 100000) . "',
    " . ii_cfname('time') . "='" . ii_get_date(ii_cstr($_POST['time'])) . "',
    " . ii_cfname('update') . "='" . ii_now() . "',
    " . ii_cfname('fid') . "=" . ii_get_num($_POST['ask_sid']) . ",
    " . ii_cfname('good') . "=" . ii_get_num($_POST['good']) . ",
    " . ii_cfname('hidden') . "=" . ii_get_num($_POST['hidden']) . "
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
  $tbackurl = $_GET['backurl'];
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
    $tmpstr = str_replace('{$ask_topic}', mm_get_field('ask',$trs[ii_cfname('fid')],'topic'), $tmpstr);
    $tmpstr = str_replace('{$id}', $trs[$nidfield], $tmpstr);
    $tmpstr = str_replace('{$backurl}', urlencode($tbackurl), $tmpstr);
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
  }
  else mm_client_alert(ii_itake('global.lng_public.sudd', 'lng'), -1);
}

function wdja_cms_admin_manage_list()
{
  global $conn, $nlng;
  global $ndatabase, $nidfield, $nfpre, $npagesize, $nlisttopx;
  $toffset = ii_get_num($_GET['offset']);
  $search_field = ii_get_safecode($_GET['field']);
  $search_keyword = ii_get_safecode($_GET['keyword']);
  $tmpstr = ii_itake('manage.list', 'tpl');
  $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_ida}');
  $tmprstr = '';
  $tsqlstr = "select * from $ndatabase where " . ii_cfname('lng') . "='$nlng'";
  if ($search_field == 'author') $tsqlstr .= " and " . ii_cfname('author') . " like '%" . $search_keyword . "%'";
  if ($search_field == 'content') $tsqlstr .= " and " . ii_cfname('content') . " like '%" . $search_keyword . "%'";
  if ($search_field == 'hidden') $tsqlstr .= " and " . ii_cfname('hidden') . "=" . ii_get_num($search_keyword);
  if ($search_field == 'id') $tsqlstr .= " and $nidfield=" . ii_get_num($search_keyword);
  $tsqlstr .= " order by $ndatabase." . ii_cfname('time') . " desc";
  $tcp = new cc_cutepage;
  $tcp -> id = $nidfield;
  $tcp -> sqlstr = $tsqlstr;
  $tcp -> offset = $toffset;
  $tcp -> pagesize = $npagesize;
  $tcp -> rslimit = $nlisttopx;
  $tcp -> init();
  $trsary = $tcp -> get_rs_array();
  $font_disabled = ii_itake('global.tpl_config.font_disabled', 'tpl');
  if (is_array($trsary))
  {
    foreach($trsary as $trs)
    {
      $tauthor = ii_htmlencode($trs[ii_cfname('author')]);
      if ($trs[ii_cfname('hidden')] == 1) $tauthor = str_replace('{$explain}', $tauthor, $font_disabled);
      $tmptstr = str_replace('{$author}', $tauthor, $tmpastr);
      $tmptstr = str_replace('{$authorstr}', ii_encode_scripts(ii_htmlencode($trs[ii_cfname('author')])), $tmptstr);
      $tmptstr = str_replace('{$authorip}', ii_htmlencode($trs[ii_cfname('authorip')]), $tmptstr);
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
    case 'good':
      return wdja_cms_admin_manage_good();
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

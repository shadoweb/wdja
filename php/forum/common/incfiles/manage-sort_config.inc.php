<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
wdja_cms_admin_init();
$ncontrol = 'select,hidden';
$ndatabase = mm_cndatabase($ngenre, 'sort');
$nidfield = mm_cnidfield($ngenre, 'sort');
$nfpre = mm_cnfpre($ngenre, 'sort');

function pp_nav_sort($lng, $baseurl, $sid)
{
  global $conn;
  global $ndatabase, $nidfield, $nfpre;
  $tsid = ii_get_num($sid);
  $tpl_href = ii_itake('global.tpl_config.a_href_sort', 'tpl');
  $tsqlstr = "select * from $ndatabase where $nidfield=$tsid";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs)
  {
    $tfid = $trs[ii_cfname('fid')];
    $tfid = pp_get_sortfid($tfid, $tsid);
    if (ii_cidary($tfid))
    {
      $tmpstr = '';
      $font_disabled = ii_itake('global.tpl_config.font_disabled', 'tpl');
      $tsqlstr = "select * from $ndatabase where $nidfield in (" . $tfid . ") and " . ii_cfname('lng') . "='$lng' order by $nidfield asc";
      $trs = ii_conn_query($tsqlstr, $conn);
      while ($trow = ii_conn_fetch_array($trs))
      {
        $tsort = $trow[ii_cfname('sort')];
        if ($trow[ii_cfname('hidden')] == 1) $tsort = str_replace('{$explain}', $tsort, $font_disabled);
        $tstr = $tpl_href;
        $tstr = str_replace('{$explain}', $tsort, $tstr);
        $tstr = str_replace('{$value}', $baseurl . $trow[$nidfield], $tstr);
        $tmpstr .= $tstr;
      }
      return $tmpstr;
    }
  }
}

function wdja_cms_admin_manage_adddisp()
{
  global $conn;
  global $ndatabase, $nidfield, $nfpre;
  $tsid = ii_get_num($_GET['sid']);
  $tbackurl = $_GET['backurl'];
  $tsort = ii_left(ii_cstr($_POST['sort']), 50);
  $tlng = ii_left(ii_cstr($_POST['lng']), 50);
  if (ii_isnull($tsort) || ii_isnull($tlng)) wdja_cms_admin_msg(ii_itake('manage-sort.empty', 'lng'), $tbackurl, 1);
  $tsqlstr = "select * from $ndatabase where " . ii_cfname('lng') . "='$tlng' and $nidfield=$tsid";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs) $tfid = pp_get_sortfid($trs[ii_cfname('fid')], $tsid);
  else $tfid = '0';
  if (strlen($tfid) <= 255)
  {
    $tpopedom = $_POST['popedom'];
    if (is_array($tpopedom)) $tpopedom = implode(',', $tpopedom);
    $tsqlstr = "insert into $ndatabase (
    " . ii_cfname('sort') . ",
    " . ii_cfname('fid') . ",
    " . ii_cfname('fsid') . ",
    " . ii_cfname('lng') . ",
    " . ii_cfname('order') . ",
    " . ii_cfname('type') . ",
    " . ii_cfname('mode') . ",
    " . ii_cfname('popedom') . ",
    " . ii_cfname('images') . ",
    " . ii_cfname('admin') . ",
    " . ii_cfname('rule') . ",
    " . ii_cfname('explain') . ",
    " . ii_cfname('attestation') . ",
    " . ii_cfname('hidden') . "
    ) values (
    '$tsort',
    '$tfid',
    $tsid,
    '$tlng',
    " . pp_get_sortfid_count($tfid) . ",
    " . ii_get_num($_POST['type']) . ",
    " . ii_get_num($_POST['mode']) . ",
    '$tpopedom',
    '" . ii_left(ii_cstr($_POST['images']), 200) . "',
    '" . ii_left(ii_cstr($_POST['admin']), 1000) . "',
    '" . ii_left(ii_cstr($_POST['rule']), 500) . "',
    '" . ii_left(ii_cstr($_POST['explain']), 500) . "',
    '" . ii_left(ii_cstr($_POST['attestation']), 500) . "',
    " . ii_get_num($_POST['hidden']) . "
    )";
    $trs = ii_conn_query($tsqlstr, $conn);
    if ($trs) wdja_cms_admin_msg(ii_itake('global.lng_public.add_succeed', 'lng'), $tbackurl, 1);
    else wdja_cms_admin_msg(ii_itake('global.lng_public.add_failed', 'lng'), $tbackurl, 1);
  }
  else wdja_cms_admin_msg(ii_itake('manage-sort.dbaseerror', 'lng'), $tbackurl, 1);
}

function wdja_cms_admin_manage_editdisp()
{
  global $conn;
  global $ndatabase, $nidfield, $nfpre;
  $tsid = ii_get_num($_GET['sid']);
  $tbackurl = $_GET['backurl'];
  $tpopedom = $_POST['popedom'];
  if (is_array($tpopedom)) $tpopedom = implode(',', $tpopedom);
  $tsqlstr = "update $ndatabase set
  " . ii_cfname('sort') . "='" . ii_left(ii_cstr($_POST['sort']), 50) . "',
  " . ii_cfname('type') . "=" . ii_get_num($_POST['type']) . ",
  " . ii_cfname('mode') . "=" . ii_get_num($_POST['mode']) . ",
  " . ii_cfname('popedom') . "='$tpopedom',
  " . ii_cfname('images') . "='" . ii_left(ii_cstr($_POST['images']), 200) . "',
  " . ii_cfname('admin') . "='" . ii_left(ii_cstr($_POST['admin']), 1000) . "',
  " . ii_cfname('rule') . "='" . ii_left(ii_cstr($_POST['rule']), 500) . "',
  " . ii_cfname('explain') . "='" . ii_left(ii_cstr($_POST['explain']), 500) . "',
  " . ii_cfname('attestation') . "='" . ii_left(ii_cstr($_POST['attestation']), 500) . "',
  " . ii_cfname('hidden') . "=" . ii_get_num($_POST['hidden']) . "
  where $nidfield=$tsid";
  $trs = ii_conn_query($tsqlstr, $conn);
  if ($trs) wdja_cms_admin_msg(ii_itake('global.lng_public.edit_succeed', 'lng'), $tbackurl, 1);
  else wdja_cms_admin_msg(ii_itake('global.lng_public.edit_failed', 'lng'), $tbackurl, 1);
}

function wdja_cms_admin_manage_deletedisp()
{
  global $conn, $slng;
  global $ndatabase, $nidfield, $nfpre;
  $tsid = ii_get_num($_GET['sid']);
  $tbackurl = $_GET['backurl'];
  $tsqlstr = "select * from $ndatabase where $nidfield=$tsid";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs)
  {
    $tfid = pp_get_sortfid($trs[ii_cfname('fid')], $tsid);
    $tfidCount = pp_get_sortfid_count($tfid);
    if ($tfidCount == 0)
    {
      $tsqlstr = "update $ndatabase set " . ii_cfname('order') . "=" . ii_cfname('order') . "-1 where " . ii_cfname('fsid') . "=" . $trs[ii_cfname('fsid')] . " and " . ii_cfname('lng') . "='$slng' and " . ii_cfname('order') . ">" . $trs[ii_cfname('order')];
      $trs = ii_conn_query($tsqlstr, $conn);
      if ($trs) $trs = mm_dbase_delete($ndatabase, $nidfield, $tsid);
      if ($trs) wdja_cms_admin_msg(ii_itake('manage-sort.deletesucceed', 'lng'), $tbackurl, 1);
      else wdja_cms_admin_msg(ii_itake('manage-sort.deletefailed', 'lng'), $tbackurl, 1);
    }
    else wdja_cms_admin_msg(ii_itake('manage-sort.delete_has', 'lng'), $tbackurl, 1);
  }
  else wdja_cms_admin_msg(ii_itake('manage-sort.deleteerr', 'lng'), $tbackurl, 1);
}

function wdja_cms_admin_manage_orderdisp()
{
  global $ngenre, $slng;
  wdja_cms_admin_orderdisp($ngenre, 'sort', " and " . ii_cfname('lng') . "='$slng'");
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
      wdja_cms_admin_manage_deletedisp();
      break;
    case 'order':
      wdja_cms_admin_manage_orderdisp();
      break;
    case 'control':
      wdja_cms_admin_controldisp($ndatabase, $nidfield, $nfpre, $ncontrol);
      break;
  }
}

function wdja_cms_admin_manage_edit()
{
  global $conn, $slng;
  global $ndatabase, $nidfield, $nfpre;
  $tsid = ii_get_num($_GET['sid']);
  $tsqlstr = "select * from $ndatabase where $nidfield=$tsid";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs)
  {
    $tmpstr = ii_itake('manage-sort.edit', 'tpl');
    $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_idc}');
    $tary = explode('{@@}', $tmpastr);
    if (ii_get_num($trs[ii_cfname('fsid')]) != 0) $tmprstr = $tary[0];
    else $tmprstr = $tary[1];
    $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
    foreach ($trs as $key => $val)
    {
      $tkey = ii_get_lrstr($key, '_', 'rightr');
      $GLOBALS['RS_' . $tkey] = $val;
      $tmpstr = str_replace('{$' . $tkey . '}', ii_htmlencode($val), $tmpstr);
    }
    $tmpstr = str_replace('{$id}', $trs[$nidfield], $tmpstr);
    $tmpstr = str_replace('{$nav_forum_sort}', pp_nav_sort($slng, '?slng=' . $slng . '&sid=', $tsid), $tmpstr);
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
  }
  else mm_client_alert(ii_itake('global.lng_public.sudd', 'lng'), -1);
}

function wdja_cms_admin_manage_list()
{
  global $conn, $slng;
  global $ngenre, $npagesize;
  global $ndatabase, $nidfield, $nfpre;
  $tsid = ii_get_num($_GET['sid']);
  $toffset = ii_get_num($_GET['offset']);
  $tmpstr = ii_itake('manage-sort.list', 'tpl');
  $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_ida}');
  $tmprstr = '';
  $tsqlstr = "select * from $ndatabase where $nidfield=$tsid";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs)
  {
    $tmptstr = str_replace('{$topic}', ii_htmlencode($trs[ii_cfname('sort')]), $tmpastr);
    $tmptstr = str_replace('{$ahref}', '?slng=' . $slng . '&sid=' . ii_get_num($trs[ii_cfname('fsid')]), $tmptstr);
    $tmptstr = str_replace('{$sclass}', 'red', $tmptstr);
    $tmprstr .= $tmptstr;
  }
  $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
  $tmprstr = '';
  $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_idb}');
  $tsqlstr = "select * from $ndatabase where " . ii_cfname('lng') . "='$slng' and " . ii_cfname('fsid') . "=$tsid order by " . ii_cfname('order') . " asc";
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
      $tsort = ii_htmlencode($trs[ii_cfname('sort')]);
      if (ii_get_num($trs[ii_cfname('hidden')]) == 1) $tsort = str_replace('{$explain}', $tsort, $font_disabled);
      $tmptstr = str_replace('{$sort}', $tsort, $tmpastr);
      $tmptstr = str_replace('{$sortstr}', ii_encode_scripts(ii_htmlencode($trs[ii_cfname('sort')])), $tmptstr);
      $tmptstr = str_replace('{$sid}', ii_get_num($trs[$nidfield]), $tmptstr);
      $tmprstr .= $tmptstr;
    }
  }
  $tmpstr = str_replace('{$cpagestr}', $tcp -> get_pagenum(), $tmpstr);
  $tmpstr = str_replace('{$sid}', $tsid, $tmpstr);
  $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
  $tmprstr = '';
  $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_idc}');
  $tary = explode('{@@}', $tmpastr);
  if ($tsid != 0) $tmprstr = $tary[0];
  else $tmprstr = $tary[1];
  $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
  $tmpstr = str_replace('{$nav_forum_sort}', pp_nav_sort($slng, '?slng=' . $slng . '&sid=', $tsid), $tmpstr);
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

<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
wdja_cms_admin_init();
$nsearch = 'topic,intro';
$ncontrol = 'select,delete';

function pp_manage_navigation()
{
  return ii_ireplace('manage.navigation', 'tpl');
}

function pp_manage_order()
{
  global $conn, $ngenre, $nlng;
  global $ndatabase, $nidfield, $nfpre;
  global $conn;
  $tat = $_GET['at'];
  $tbackurl = $_GET['backurl'];
  $tid = ii_get_num($_GET['id']);
    $tsqlstr = "select * from $ndatabase where $nidfield=$tid";
    $trs = ii_conn_query($tsqlstr, $conn);
    $trs = ii_conn_fetch_array($trs);
    if ($trs)
    {
      $tsqlstr2 = "select count($nidfield) from $ndatabase where " . ii_cfname('lng') . "='$nlng'";
      $trs2 = ii_conn_query($tsqlstr2, $conn);
      $trs2 = ii_conn_fetch_array($trs2);
      $tfid_count = $trs2[0];
      if ($tat == 'down')
      {
        $tnum = $trs[ii_cfnames($nfpre, 'order')] + 1;
        if ($tnum <= ($tfid_count - 1))
        {
          $tsqlstr3 = "update $ndatabase set " . ii_cfnames($nfpre, 'order') . "=" . ii_cfnames($nfpre, 'order') . "-1 where " . ii_cfnames($nfpre, 'order') . "=" . $tnum;
          $tsqlstr4 = "update $ndatabase set " . ii_cfnames($nfpre, 'order') . "=$tnum where $nidfield=$tid";
          $trs3 = ii_conn_query($tsqlstr3, $conn);
          if ($trs3) @ii_conn_query($tsqlstr4, $conn);
        }
      }
      else
      {
        $tnum = $trs[ii_cfnames($nfpre, 'order')] - 1;
        if ($tnum >= 0)
        {
          $tsqlstr3 = "update $ndatabase set " . ii_cfnames($nfpre, 'order') . "=" . ii_cfnames($nfpre, 'order') . "+1 where " . ii_cfnames($nfpre, 'order') . "=" . $tnum;
          $tsqlstr4 = "update $ndatabase set " . ii_cfnames($nfpre, 'order') . "=$tnum where $nidfield=$tid";
          $trs3 = ii_conn_query($tsqlstr3, $conn);
          if ($trs3) @ii_conn_query($tsqlstr4, $conn);
        }
      }
    }
    mm_client_redirect($tbackurl);
}


function wdja_cms_admin_manage_adddisp()
{
  global $conn, $ngenre, $nlng;
  global $ndatabase, $nidfield, $nfpre;
  $tbackurl = $_GET['backurl'];
  $ttopic = ii_cstr($_POST['topic']);
  $timage = ii_left(ii_cstr($_POST['image']), 255);
  $tckstr = 'topic:' . ii_itake('manage.topic', 'lng').',image:' . ii_itake('global.lng_config.image', 'lng').',url:' . ii_itake('manage.url', 'lng');
  $tary = explode(',', $tckstr);
  $Err = array();
  foreach ($tary as $val)
  {
    $tvalary = explode(':', $val);
    if (ii_isnull($_POST[$tvalary[0]])) $Err[count($Err)] = str_replace('[]', '[' . $tvalary[1] . ']', ii_itake('global.lng_error.insert_empty', 'lng'));
  }
  if (is_array($Err) && count($Err) > 0) wdja_cms_admin_msg($Err[0], $tbackurl, 1);
  if (!(ii_isnull($ttopic)))
  {
    $tsqlstr = "insert into $ndatabase (
    " . ii_cfname('topic') . ",
    " . ii_cfname('url') . ",
    " . ii_cfname('image') . ",
    " . ii_cfname('lng') . ",
    " . ii_cfname('intro') . ",
    " . ii_cfname('hidden') . ",
    " . ii_cfname('time') . ",
    " . ii_cfname('update') . "
    ) values (
    '" . ii_left($ttopic, 50) . "',
    '" . ii_left(ii_cstr($_POST['url']), 255) . "',
    '$timage',
    '" . $nlng . "',
    '" . ii_left(ii_cstr($_POST['intro']), 255) . "',
    " . ii_get_num($_POST['hidden']) . ",
    '" . ii_get_date(ii_cstr($_POST['time'])) . "',
    '" . ii_now() . "'
    )";
    $trs = ii_conn_query($tsqlstr, $conn);
    if ($trs)
    {
      $upfid = ii_conn_insert_id($conn);
      uu_upload_update_database_note($ngenre, $timage, 'image', $upfid);
      wdja_cms_admin_msg(ii_itake('global.lng_public.add_succeed', 'lng'), $tbackurl, 1);
    }
    else wdja_cms_admin_msg(ii_itake('global.lng_public.add_failed', 'lng'), $tbackurl, 1);
  }
  else
  {
    wdja_cms_admin_msg(ii_itake('global.lng_public.sudd', 'lng'), $tbackurl, 1);
  }
}

function wdja_cms_admin_manage_editdisp()
{
  global $conn, $ngenre;
  global $ndatabase, $nidfield, $nfpre;
  $tbackurl = $_GET['backurl'];
  $ttopic = ii_cstr($_POST['topic']);
  $timage = ii_left(ii_cstr($_POST['image']), 255);
  $tid = ii_get_num($_GET['id']);
  $tckstr = 'topic:' . ii_itake('manage.topic', 'lng').',image:' . ii_itake('global.lng_config.image', 'lng').',url:' . ii_itake('manage.url', 'lng');
  $tary = explode(',', $tckstr);
  $Err = array();
  foreach ($tary as $val)
  {
    $tvalary = explode(':', $val);
    if (ii_isnull($_POST[$tvalary[0]])) $Err[count($Err)] = str_replace('[]', '[' . $tvalary[1] . ']', ii_itake('global.lng_error.insert_empty', 'lng'));
  }
  if (is_array($Err) && count($Err) > 0) wdja_cms_admin_msg($Err[0], $tbackurl, 1);
  if (!(ii_isnull($ttopic)))
  {
    $tsqlstr = "update $ndatabase set
    " . ii_cfname('topic') . "='" . ii_left($ttopic, 50) . "',
    " . ii_cfname('url') . "='" . ii_left(ii_cstr($_POST['url']), 255) . "',
    " . ii_cfname('image') . "='$timage',
    " . ii_cfname('intro') . "='" . ii_left(ii_cstr($_POST['intro']), 255) . "',
    " . ii_cfname('hidden') . "=" . ii_get_num($_POST['hidden']) . ",
    " . ii_cfname('update') . "='" . ii_now() . "'
    where $nidfield=$tid";
    $trs = ii_conn_query($tsqlstr, $conn);
    if ($trs)
    {
      $upfid = $tid;
      uu_upload_update_database_note($ngenre, $timage, 'image', $upfid);
      wdja_cms_admin_msg(ii_itake('global.lng_public.edit_succeed', 'lng'), $tbackurl, 1);
    }
    else wdja_cms_admin_msg(ii_itake('global.lng_public.edit_failed', 'lng'), $tbackurl, 1);
  }
  else
  {
    wdja_cms_admin_msg(ii_itake('global.lng_public.sudd', 'lng'), $tbackurl, 1);
  }
}

function wdja_cms_admin_manage_resetdisp()
{
  global $slng, $sgenre;
  global $conn;
  global $ndatabase, $nidfield, $nfpre;
  $tid = ii_get_num($_GET['id']);
  $tbackurl = $_GET['backurl'];
  $tsqlstr = "select * from $ndatabase where " . ii_cfname('lng') . "='$slng' order by $nidfield asc";
  $trs = ii_conn_query($tsqlstr, $conn);
  $ti = 0;
  while ($trow = ii_conn_fetch_array($trs))
  {
    $tsqlstr = "update $ndatabase set " . ii_cfname('order') . "=$ti where $nidfield=$trow[$nidfield]";
    ii_conn_query($tsqlstr, $conn);
    $ti = $ti + 1;
  }
  mm_client_redirect($tbackurl);
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
    case 'reset':
      wdja_cms_admin_manage_resetdisp();
      break;
    case 'order':
      pp_manage_order();
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
  global $nupsimg, $nupsimgs;
  $tmpstr = ii_itake('manage.add', 'tpl');
  $tmpstr = str_replace('{$upsimg}', $nupsimg, $tmpstr);
  $tmpstr = str_replace('{$upsimgs}', $nupsimgs, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function wdja_cms_admin_manage_edit()
{
  global $conn;
  global $ndatabase, $nidfield, $nfpre, $nupsimg, $nupsimgs;
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
    $tmpstr = str_replace('{$upsimgs}', $nupsimgs, $tmpstr);
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
  if ($search_field == 'topic') $tsqlstr .= " and " . ii_cfname('topic') . " like '%" . $search_keyword . "%'";
  if ($search_field == 'intro') $tsqlstr .= " and " . ii_cfname('intro') . " like '%" . $search_keyword . "%'";
  $tsqlstr .= " order by $ndatabase." . ii_cfname('order') . " asc";
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
      $ttopic = ii_htmlencode($trs[ii_cfname('topic')]);
      if (isset($font_red))
      {
        $font_red = str_replace('{$explain}', $search_keyword, $font_red);
        $ttopic = str_replace($search_keyword, $font_red, $ttopic);
      }
      $tmptstr = str_replace('{$topic}', $ttopic, $tmpastr);
      $tmptstr = str_replace('{$topicstr}', ii_encode_scripts(ii_htmlencode($trs[ii_cfname('topic')])), $tmptstr);
      $tmptstr = str_replace('{$image}', ii_htmlencode($trs[ii_cfname('image')]), $tmptstr);
      $tmptstr = str_replace('{$url}', ii_htmlencode($trs[ii_cfname('url')]), $tmptstr);
      $tmptstr = str_replace('{$intro}', ii_htmlencode($trs[ii_cfname('intro')]), $tmptstr);
      $tmptstr = str_replace('{$hidden}', ii_itake('global.sel_yesno.'.ii_get_num($trs[ii_cfname('hidden')]), 'lng'), $tmptstr);
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
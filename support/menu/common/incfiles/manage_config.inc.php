<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
$ncontrol = 'select,hidden';
$mgroup = ii_get_num($_GET['group'],0);
if (ii_isnull($mgroup)) $mgroup = pp_get_sort_select_default();
if (!($admc_popedom == '-1' || ii_cinstr($admc_popedom, $mgroup, ','))) wdja_cms_admin_msgs(ii_itake('global.lng_admin.popedom_error', 'lng'), 1);

function pp_manage_navigation()
{
  return ii_ireplace('manage.navigation', 'tpl');
}

function wdja_cms_admin_manage_adddisp()
{
  global $slng, $mgroup;
  global $conn;
  global $ndatabase, $nidfield, $nfpre;
  $ttopic = ii_get_safecode($_POST['topic']);
  $ttitle = ii_get_safecode($_POST['title']);
  $timage = ii_get_safecode($_POST['image']);
  $talt = ii_get_safecode($_POST['alt']);
  $tgourl = ii_get_safecode($_POST['gourl']);
  $tbackurl = $_GET['backurl'];
  $tfsid = ii_get_num($_POST['fsid']);
  $tfgourl = mm_get_sort_field($tfsid,'gourl');
  $tckstr = 'topic:' . ii_itake('manage.name', 'lng');
  $tary = explode(',', $tckstr);
  $Err = array();
  foreach ($tary as $val)
  {
    $tvalary = explode(':', $val);
    if (ii_isnull($_POST[$tvalary[0]])) $Err[count($Err)] = str_replace('[]', '[' . $tvalary[1] . ']', ii_itake('global.lng_error.insert_empty', 'lng'));
  }
  if (is_array($Err) && count($Err) > 0) wdja_cms_admin_msg($Err[0], $tbackurl, 1);
  if (!ii_isnull($tfgourl) && ii_isnull($tgourl)) wdja_cms_admin_msg(ii_itake('manage.fid-gourl', 'lng'), $tbackurl, 1);
  if (!ii_isnull($ttopic))
  {
    $tsqlstr = "select * from $ndatabase where " . ii_cfname('lng') . "='$slng' and $nidfield=$tfsid";
    $trs = ii_conn_query($tsqlstr, $conn);
    $trs = ii_conn_fetch_array($trs);
    if ($trs)
    {
      $tfid = mm_get_sortfid($trs[ii_cfname('fid')], $tfsid);
    }
    else
    {
      $tfid = '0';
    }
    if (strlen($tfid) < 255)
    {
      $tfid_count = mm_get_sortfid_count($tfid, $mgroup, $slng);
      $tsqlstr = "insert into $ndatabase (
      	" . ii_cfname('topic') . ",
      	" . ii_cfname('title') . ",
      	" . ii_cfname('image') . ",
      	" . ii_cfname('alt') . ",
      	" . ii_cfname('hidden') . ",
      	" . ii_cfname('fid') . ",
      	" . ii_cfname('fsid') . ",
      	" . ii_cfname('group') . ",
      	" . ii_cfname('lng') . ",
      	" . ii_cfname('gourl') . ",
      	" . ii_cfname('order') . ",
      	" . ii_cfname('time') . "
      	) values (
      		'" . ii_left($ttopic, 50) . "',
      		'" . ii_left($ttitle, 100) . "',
      		'$timage',
      		'" . ii_left($talt, 250) . "',
      		'" . ii_get_num($thidden) . "',
      		'" . $tfid . "',
      		" . $tfsid . ",
      		'" . $mgroup . "',
      		'" . $slng . "',
      		'" . ii_left($tgourl, 250) . "',
      		" . $tfid_count . ",
      		'" . ii_now() . "'
      	)";
      $trs = ii_conn_query($tsqlstr, $conn);
      if ($trs)
      {
        mm_client_redirect($tbackurl);
      }
      else
      {
        wdja_cms_admin_msg(ii_itake('global.lng_public.sudd', 'lng'), $tbackurl, 1);
      }
    }
    else
    {
      wdja_cms_admin_msg(ii_itake('manage.dbaseerror', 'lng'), $tbackurl, 1);
    }
  }
  else
  {
    wdja_cms_admin_msg(ii_itake('global.lng_public.sudd', 'lng'), $tbackurl, 1);
  }
}

function wdja_cms_admin_manage_editdisp()
{
  global $conn;
  global $slng, $mgroup;
  global $ndatabase, $nidfield, $nfpre;
  $tid = ii_get_num($_GET['id']);
  $tbackurl = $_GET['backurl'];
  $ttopic = ii_get_safecode($_POST['topic']);
  $tfsid = ii_get_safecode($_POST['fsid']);
  $ttitle = ii_get_safecode($_POST['title']);
  $timage = ii_get_safecode($_POST['image']);
  $talt = ii_get_safecode($_POST['alt']);
  $tgourl = ii_get_safecode($_POST['gourl']);
  $thidden = ii_get_num($_POST['hidden']);
  $ttime = ii_now();
  $tfgourl = mm_get_sort_field($tfsid,'gourl');
  $tckstr = 'topic:' . ii_itake('manage.name', 'lng');
  $tary = explode(',', $tckstr);
  $Err = array();
  foreach ($tary as $val)
  {
    $tvalary = explode(':', $val);
    if (ii_isnull($_POST[$tvalary[0]])) $Err[count($Err)] = str_replace('[]', '[' . $tvalary[1] . ']', ii_itake('global.lng_error.insert_empty', 'lng'));
  }
  if (is_array($Err) && count($Err) > 0) wdja_cms_admin_msg($Err[0], $tbackurl, 1);
  if (!ii_isnull($tfgourl) && ii_isnull($tgourl)) wdja_cms_admin_msg(ii_itake('manage.fid-gourl', 'lng'), $tbackurl, 1);
  if ($tid !=$tfsid ) {
    $tsqlstr = "select * from $ndatabase where " . ii_cfname('lng') . "='$slng' and $nidfield=$tfsid";
    $trs = ii_conn_query($tsqlstr, $conn);
    $trs = ii_conn_fetch_array($trs);
    if ($trs)
    {
      //判断上级分类是否包含当前分类.
      $afid = preg_split( ',',$trs[ii_cfname('fid')]);
      foreach($afid as $aid) {
        if ($tid == $aid) return;
      }
      $tfid = mm_get_sortfid($trs[ii_cfname('fid')], $tfsid);
    }
    else
    {
      $tfid = '0';
    }
    if (strlen($tfid) < 255)
    {
      $tsqlstr = "update $ndatabase set " . ii_cfname('topic') . "='$ttopic',
      " . ii_cfname('fid') . "='$tfid'," . ii_cfname('fsid') . "='$tfsid',
      " . ii_cfname('title') . "='$ttitle',
      " . ii_cfname('image') . "='$timage',
      " . ii_cfname('alt') . "='$talt',
      " . ii_cfname('gourl') . "='$tgourl',
      " . ii_cfname('time') . "='$ttime',
      " . ii_cfname('update') . "='".ii_now()."',
      " . ii_cfname('hidden') . "='$thidden'
      where $nidfield=$tid";
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
    else
    {
      wdja_cms_admin_msg(ii_itake('manage.dbaseerror', 'lng'), $tbackurl, 1);
    }
  }
}

function wdja_cms_admin_manage_deletedisp()
{
  global $slng, $mgroup;
  global $conn;
  global $ndatabase, $nidfield, $nfpre;
  $tid = ii_get_num($_GET['id']);
  $tbackurl = $_GET['backurl'];
  $tsqlstr = "select * from $ndatabase where $nidfield=$tid";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs)
  {
    $tfid = mm_get_sortfid($trs[ii_cfname('fid')], $tid);
    $tfid_count = mm_get_sortfid_count($tfid, $mgroup, $slng);
    if ($tfid_count > 0)
    {
      mm_client_alert(ii_itake('manage.delete_has', 'lng'), $tbackurl, 1);
    }
    else
    {
      $tsqlstr2 = "update $ndatabase set " . ii_cfname('order') . "=" . ii_cfname('order') . "-1 where " . ii_cfname('group') . "='" . $trs[ii_cfname('group')] . "' and  " . ii_cfname('lng') . "='" . $trs[ii_cfname('lng')] . "' and " . ii_cfname('fid') . "='" . $trs[ii_cfname('fid')] . "' and " . ii_cfname('order') . ">" . $trs[ii_cfname('order')];
      $trs2 = ii_conn_query($tsqlstr2, $conn);
      if ($trs2)
      {
        mm_dbase_delete($ndatabase, $nidfield, $tid);
        wdja_cms_admin_msg(ii_itake('manage.deletesucceed', 'lng'), $tbackurl, 1);
      }
      else
      {
        wdja_cms_admin_msg(ii_itake('manage.deletefailed', 'lng'), $tbackurl, 1);
      }
    }
  }
  else
  {
    wdja_cms_admin_msg(ii_itake('manage.deleteerr', 'lng'), $tbackurl, 1);
  }
}

function wdja_cms_admin_manage_resetdisp()
{
  global $slng, $mgroup;
  global $conn;
  global $ndatabase, $nidfield, $nfpre;
  $tid = ii_get_num($_GET['id']);
  $tbackurl = $_GET['backurl'];
  $tsqlstr = "select * from $ndatabase where " . ii_cfname('lng') . "='$slng' and " . ii_cfname('group') . "='$mgroup' and " . ii_cfname('fsid') . "=$tid order by $nidfield asc";
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
  global $slng, $mgroup;
  global $ndatabase, $nidfield, $nfpre, $ncontrol;
  $taction = $_GET['action'];
  switch($taction)
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
    case 'reset':
      wdja_cms_admin_manage_resetdisp();
      break;
    case 'order':
      wdja_cms_admin_orderdisp('support.menu', '', " and " . ii_cfname('group') . "='" . $mgroup . "' and " . ii_cfname('lng') . "='" . $slng . "'");
      break;
    case 'control':
      wdja_cms_admin_controldisp($ndatabase, $nidfield, $nfpre, $ncontrol);
      break;
    case 'upload':
      uu_upload_files();
      break;
  }
}

function wdja_cms_admin_manage_edit()
{
  global $conn, $mgroup, $slng;
  global $ndatabase, $nidfield, $nfpre, $nupsimg;
  $tid = ii_get_num($_GET['id']);
  $tsqlstr = "select * from $ndatabase where $nidfield=$tid";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs)
  {
    $tmpstr = ii_itake('manage.edit', 'tpl');
    $tmpstr = str_replace('{$id}', $trs[$nidfield], $tmpstr);
    $tmpstr = str_replace('{$fsid}', $trs[ii_cfname('fsid')], $tmpstr);
    $tmpstr = str_replace('{$topic}', $trs[ii_cfname('topic')], $tmpstr);
    $tmpstr = str_replace('{$title}', $trs[ii_cfname('title')], $tmpstr);
    $tmpstr = str_replace('{$image}', $trs[ii_cfname('image')], $tmpstr);
    $tmpstr = str_replace('{$alt}', $trs[ii_cfname('alt')], $tmpstr);
    $tmpstr = str_replace('{$gourl}', $trs[ii_cfname('gourl')], $tmpstr);
    $tmpstr = str_replace('{$hidden}', $trs[ii_cfname('hidden')], $tmpstr);
    $tmpstr = str_replace('{$group}', $mgroup, $tmpstr);
    $tmpstr = str_replace('{$lng}', $slng, $tmpstr);
    $tmpstr = str_replace('{$nav_menu}', mm_nav_menu($mgroup, '?group=' . $mgroup . '&id=', $tid), $tmpstr);
    $tmpstr = str_replace('{$upsimg}', $nupsimg, $tmpstr);
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
  global $ndatabase, $nidfield, $nfpre, $npagesize, $nupsimg;;
  global $slng, $mgroup;
  $tid = ii_get_num($_GET['id']);
  $toffset = ii_get_num($_GET['offset']);
  $tmpstr = ii_itake('manage.list', 'tpl');
  $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_ida}');
  $tmprstr = '';
  $tsqlstr = "select * from $ndatabase where $nidfield=$tid";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs)
  {
    $tfid = $trs[ii_cfname('fid')];
    $tarys = preg_split(',', $tfid);
    if(!ii_isnull($tarys)) $tsid = $tarys[ii_get_num(count($tarys),1) - 1];
    else $tsid = 0;
    $tmptstr = str_replace('{$topic}', $trs[ii_cfname('topic')], $tmpastr);
    $tmptstr = str_replace('{$href}', '?group=' . $mgroup . '&id=' . $tsid, $tmptstr);
    $tmprstr = $tmprstr . $tmptstr;
  }
  $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
  $tmprstr = '';
  $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_idb}');
  $tsqlstr = "select * from $ndatabase where " . ii_cfname('lng') . "='$slng' and " . ii_cfname('group') . "='$mgroup' and " . ii_cfname('fsid') . "=$tid order by " . ii_cfname('order') . " asc";
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
      $ttopic = $trs[ii_cfname('topic')];
      if ($trs[ii_cfname('hidden')] == 1) $tsort = str_replace('{$explain}', $tsort, $font_disabled);
      $tmptstr = str_replace('{$topic}', $ttopic, $tmpastr);
      $tmptstr = str_replace('{$topicstr}', ii_encode_scripts(str_replace('[]', '[' . ii_htmlencode($trs[ii_cfname('topic')]) . ']', $tdeletenotice)), $tmptstr);
      $tmptstr = str_replace('{$gourl}', ii_htmlencode($trs[ii_cfname('gourl')]), $tmptstr);
      $tmptstr = str_replace('{$hidden}', ii_itake('global.sel_yesno.'.ii_get_num($trs[ii_cfname('hidden')]),'lng'), $tmptstr);
      $tmptstr = str_replace('{$order}', ii_htmlencode($trs[ii_cfname('order')]), $tmptstr);
      $tmptstr = str_replace('{$id}', $trs[$nidfield], $tmptstr);
      $tmprstr .= $tmptstr;
    }
  }
  $tmpstr = str_replace('{$cpagestr}', $tcp -> get_pagenum(), $tmpstr);
  $tmpstr = str_replace('{$id}', $tid, $tmpstr);
  $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
  $tmpstr = str_replace('{$nav_menu}', mm_nav_menu($mgroup, '?group=' . $mgroup . '&id=', $tid), $tmpstr);
  $tmpstr = str_replace('{$upsimg}', $nupsimg, $tmpstr);
  $tmpstr = str_replace('{$mgroup}', $mgroup, $tmpstr);
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
    case 'upload':
      uu_upload_files_html('upload_html');
      break;
  }
}
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
?>
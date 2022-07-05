<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
wdja_cms_admin_init();
$nsearch = 'topic,id';
$ncontrol = 'select,hidden';

function pp_manage_navigation()
{
  $tmpstr = ii_itake('manage.navigation', 'tpl');
  $tgenre = ii_get_safecode($_GET['genre']) ? ii_get_safecode($_GET['genre']):'';
  $tmpstr = str_replace('{$tgenre}', $tgenre, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function pp_config_type($strers)
{
  if ($strers == 0) return 'radio';
  else return 'checkbox';
}

function wdja_cms_admin_manage_adddisp()
{
  global $conn;
  global $ndatabase, $nidfield, $nfpre;
  global $ngenre, $slng;
  $tbackurl = $_GET['backurl'];
  $ttopic = ii_cstr($_POST['topic']);
  $ttype = ii_get_num($_POST['type']);
  $tcount = ii_get_num($_POST['count']);
  $tckstr = 'topic:' . ii_itake('manage.topic', 'lng');
  $tary = explode(',', $tckstr);
  $Err = array();
  foreach ($tary as $val)
  {
    $tvalary = explode(':', $val);
    if (ii_isnull($_POST[$tvalary[0]])) $Err[count($Err)] = str_replace('[]', '[' . $tvalary[1] . ']', ii_itake('global.lng_error.insert_empty', 'lng'));
  }
  if (is_array($Err) && count($Err) > 0) wdja_cms_admin_msg($Err[0], $tbackurl, 1);
  if ($tcount <= 0) mm_client_alert(ii_itake('manage.add_count_error', 'lng'), $tbackurl);
  if (!(ii_isnull($ttopic)))
  {
    $tsqlstr = "insert into $ndatabase (
    " . ii_cfname('genre') . ",
    " . ii_cfname('name') . ",
    " . ii_cfname('topic') . ",
    " . ii_cfname('type') . ",
    " . ii_cfname('count') . ",
    " . ii_cfname('hidden') . ",
    " . ii_cfname('hidden_list') . ",
    " . ii_cfname('hidden_detail') . ",
    " . ii_cfname('time') . ",
    " . ii_cfname('update') . ",
    " . ii_cfname('lng') . "
    ) values (
    '" . ii_left(ii_cstr($_POST['genre']), 50) . "',
    '" . ii_left(ii_cstr($_POST['name']), 50) . "',
    '" . ii_left($ttopic, 50) . "',
    $ttype,
    $tcount,
    " . ii_get_num($_POST['hidden']) . ",
    " . ii_get_num($_POST['hidden_list']) . ",
    " . ii_get_num($_POST['hidden_detail']) . ",
    '" . ii_now() . "',
    '" . ii_now() . "',
    '$slng'
    )";
    $trs = ii_conn_query($tsqlstr, $conn);
    if ($trs)
    {
    	if ($ttype != 3) {
	      $upfid = ii_conn_insert_id($conn);
	      $tdatabase = mm_cndatabase(ii_cvgenre($ngenre), 'data');
	      $tidfield = mm_cnidfield(ii_cvgenre($ngenre), 'data');
	      $tfpre = mm_cnfpre(ii_cvgenre($ngenre), 'data');
	      for ($i = 1; $i <= $tcount; $i ++)
	      {
	        $tsqlstr = "insert into $tdatabase (
	        " . ii_cfnames($tfpre, 'topic') . ",
	        " . ii_cfnames($tfpre, 'fid') . ",
	        " . ii_cfnames($tfpre, 'oid') . "
	        ) values (
	        '" . ii_left(ii_cstr($_POST['option' . $i]), 50) . "',
	        $upfid,
	        $i
	        )";
	        ii_conn_query($tsqlstr, $conn);
	      }
    	}
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
  global $conn;
  global $ndatabase, $nidfield, $nfpre;
  global $ngenre;
  $tid = ii_get_num($_GET['id']);
  $tbackurl = $_GET['backurl'];
  $ttopic = ii_cstr($_POST['topic']);
  $ttype = ii_get_num($_POST['type']);
  $tcount = ii_get_num($_POST['count']);
  $otype = mm_get_field($ngenre,$tid,'type');
  $tckstr = 'topic:' . ii_itake('manage.topic', 'lng');
  $tary = explode(',', $tckstr);
  $Err = array();
  foreach ($tary as $val)
  {
    $tvalary = explode(':', $val);
    if (ii_isnull($_POST[$tvalary[0]])) $Err[count($Err)] = str_replace('[]', '[' . $tvalary[1] . ']', ii_itake('global.lng_error.insert_empty', 'lng'));
  }
  if (is_array($Err) && count($Err) > 0) wdja_cms_admin_msg($Err[0], $tbackurl, 1);
  if (($otype == 3 || $ttype == 3 ) && $otype != $ttype) wdja_cms_admin_msg(ii_itake('manage.type_error', 'lng'), $tbackurl, 1);
  if (($otype == 4 || $ttype == 4 ) && $otype != $ttype) wdja_cms_admin_msg(ii_itake('manage.type_error', 'lng'), $tbackurl, 1);
  if (($otype == 5 || $ttype == 5 ) && $otype != $ttype) wdja_cms_admin_msg(ii_itake('manage.type_error', 'lng'), $tbackurl, 1);
  if (($otype == 6 || $ttype == 6 ) && $otype != $ttype) wdja_cms_admin_msg(ii_itake('manage.type_error', 'lng'), $tbackurl, 1);
  if (!(ii_isnull($ttopic)))
  {
    $tsqlstr = "select * from $ndatabase where $nidfield=$tid";
    $trs = ii_conn_query($tsqlstr, $conn);
    $trs = ii_conn_fetch_array($trs);
    if ($trs)
    {
      $tycount = ii_get_num($trs[ii_cfname('count')]);
      $tsqlstr = "update $ndatabase set
      " . ii_cfname('genre') . "='" . ii_left(ii_cstr($_POST['genre']), 50) . "',
      " . ii_cfname('name') . "='" . ii_left(ii_cstr($_POST['name']), 50) . "',
      " . ii_cfname('topic') . "='" . ii_left($ttopic, 50) . "',
      " . ii_cfname('type') . "=$ttype,
      " . ii_cfname('count') . "=$tcount,
      " . ii_cfname('hidden') . "=" . ii_get_num($_POST['hidden']) . ",
      " . ii_cfname('hidden_list') . "=" . ii_get_num($_POST['hidden_list']) . ",
      " . ii_cfname('hidden_detail') . "=" . ii_get_num($_POST['hidden_detail']) . ",
      " . ii_cfname('time') . "='" . ii_get_date($_POST['time']) . "',
      " . ii_cfname('update') . "='" . ii_now() . "'
      where $nidfield=$tid";
      $trs = ii_conn_query($tsqlstr, $conn);
      if ($trs)
      {
        $tdatabase = mm_cndatabase(ii_cvgenre($ngenre), 'data');
        $tidfield = mm_cnidfield(ii_cvgenre($ngenre), 'data');
        $tfpre = mm_cnfpre(ii_cvgenre($ngenre), 'data');
        $gdatabase = mm_cndatabase(ii_cvgenre($ngenre), 'gid');
        $gidfield = mm_cnidfield(ii_cvgenre($ngenre), 'gid');
        $gfpre = mm_cnfpre(ii_cvgenre($ngenre), 'gid');
        for($i = 1; $i <= $tcount; $i ++)
        {
          $tsqlstr = "select * from $tdatabase where " . ii_cfnames($tfpre, 'fid') . "=$tid and " . ii_cfnames($tfpre, 'oid') . "=$i";
          $trs = ii_conn_query($tsqlstr, $conn);
          $trs = ii_conn_fetch_array($trs);
          if ($trs) $tsqlstr2 = "update $tdatabase set " . ii_cfnames($tfpre, 'topic') . "='" . ii_left(ii_cstr($_POST['option' . $i]), 50) . "' where " . ii_cfnames($tfpre, 'fid') . "=$tid and " . ii_cfnames($tfpre, 'oid') . "=$i";
          else $tsqlstr2 = "insert into $tdatabase (" . ii_cfnames($tfpre, 'topic') . "," . ii_cfnames($tfpre, 'fid') . "," . ii_cfnames($tfpre, 'oid') . ") values ('" . ii_left(ii_cstr($_POST['option' . $i]), 50) . "',$tid,$i)";
          ii_conn_query($tsqlstr2, $conn);
        }
        if ($tycount > $tcount)
        {
          $tmyvid = '';
          for($i = ($tcount + 1); $i <= $tycount; $i ++)
          {
            $tmyvid .= $i . ',';
          }
          if (!ii_isnull($tmyvid))
          {
            $tmyvid = ii_left($tmyvid, strlen($tmyvid) - 1);
            $tsqlstr3 = 'delete from '.$tdatabase.' where '.ii_cfnames($tfpre, 'fid').' = '.$tid.' and '.ii_cfnames($tfpre, 'oid').' in ('.$tmyvid.')';
            ii_conn_query($tsqlstr3, $conn);
            $tmyvid2 = str_replace(',','|,',$tmyvid).'|';
            $tsqlstr4 = "select * from $gdatabase where " . ii_cfnames($gfpre, 'fid') . "=$tid";
            $trs = ii_conn_query($tsqlstr4, $conn);
            while ($trow = ii_conn_fetch_array($trs))
            {
              $gid = $trow[$gidfield];
              if (strpos($trow[ii_cfnames($gfpre, 'data')],'|') !==false)
              {
                $tmyvid2_array = explode(',', $tmyvid2);
                foreach($tmyvid2_array as $kk => $vv) {
                  $vv = str_replace($vv,'',$trow[ii_cfnames($gfpre, 'data')]);
                  $tsqlstr5 = 'update '.$gdatabase.' set '.ii_cfnames($gfpre, 'data').' = "'.$vv.'" where '.$gidfield.' = '.$gid.' and '.ii_cfnames($gfpre, 'fid').' = '.$trow[ii_cfnames($gfpre, 'fid')];
                  ii_conn_query($tsqlstr5, $conn);
                }
              }
              else
              {
                $tsqlstr6 = 'delete from '.$gdatabase.' where '.ii_cfnames($gfpre, 'fid').' = '.$tid.' and '.ii_cfnames($gfpre, 'data').' in ('.$tmyvid.')';
                ii_conn_query($tsqlstr6, $conn);
              }
            }
          }
        }
        wdja_cms_admin_msg(ii_itake('global.lng_public.edit_succeed', 'lng'), $tbackurl, 1);
      }
      else wdja_cms_admin_msg(ii_itake('global.lng_public.edit_failed', 'lng'), $tbackurl, 1);
    }
    else wdja_cms_admin_msg(ii_itake('global.lng_public.sudd', 'lng'), $tbackurl, 1);
  }
  else wdja_cms_admin_msg(ii_itake('global.lng_public.sudd', 'lng'), $tbackurl, 1);
}

function wdja_cms_admin_manage_deletedisp()
{
  global $ndatabase, $nidfield, $nfpre;
  global $ngenre;
  $tbackurl = $_GET['backurl'];
  $tid = ii_get_num($_GET['id']);
  mm_dbase_delete($ndatabase, $nidfield, $tid);
  $tdatabase = mm_cndatabase(ii_cvgenre($ngenre), 'data');
  $tidfield = mm_cnidfield(ii_cvgenre($ngenre), 'data');
  $tfpre = mm_cnfpre(ii_cvgenre($ngenre), 'data');
  $gdatabase = mm_cndatabase(ii_cvgenre($ngenre), 'gid');
  $gidfield = mm_cnidfield(ii_cvgenre($ngenre), 'gid');
  $gfpre = mm_cnfpre(ii_cvgenre($ngenre), 'gid');
  mm_dbase_delete($tdatabase, ii_cfnames($tfpre, 'fid'), $tid);
  mm_dbase_delete($gdatabase, ii_cfnames($gfpre, 'fid'), $tid);
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
      wdja_cms_admin_manage_deletedisp();
      break;
    case 'control':
      wdja_cms_admin_controldisp($ndatabase, $nidfield, $nfpre, $ncontrol);
      break;
  }
}

function wdja_cms_admin_manage_add()
{
  $tmpstr = ii_itake('manage.add', 'tpl');
  $tgenre = ii_get_safecode($_GET['genre']) ? ii_get_safecode($_GET['genre']):'';
  $tmpstr = str_replace('{$tgenre}', $tgenre, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function wdja_cms_admin_manage_edit()
{
  global $conn;
  global $ndatabase, $nidfield, $nfpre;
  global $ngenre;
  $tid = ii_get_num($_GET['id']);
  $tdatabase = mm_cndatabase(ii_cvgenre($ngenre), 'data');
  $tidfield = mm_cnidfield(ii_cvgenre($ngenre), 'data');
  $tfpre = mm_cnfpre(ii_cvgenre($ngenre), 'data');
  $otype = mm_get_field($ngenre,$tid,'type');
  if ($otype == 3) $tmpstr = ii_itake('manage.edit_input', 'tpl');
  elseif ($otype == 4) $tmpstr = ii_itake('manage.edit_textarea', 'tpl');
  elseif ($otype == 5) $tmpstr = ii_itake('manage.edit_upload', 'tpl');
  elseif ($otype == 6) $tmpstr = ii_itake('manage.edit_gallery', 'tpl');
  else $tmpstr = ii_itake('manage.edit', 'tpl');
  $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_ida}');
  $tmprstr = '';
  $tsqlstr = "select * from $tdatabase where " . ii_cfnames($tfpre, 'fid') . "=" . $tid . " order by " . ii_cfnames($tfpre, 'oid') . " asc";
  $trs = ii_conn_query($tsqlstr, $conn);
  while ($trow = ii_conn_fetch_array($trs))
  {
    $tmptstr = str_replace('{$topic}', ii_htmlencode($trow[ii_cfnames($tfpre, 'topic')]), $tmpastr);
    $tmptstr = str_replace('{$count}', ii_get_num($trow[ii_cfnames($tfpre, 'count')], 0), $tmptstr);
    $tmptstr = str_replace('{$oid}', ii_get_num($trow[ii_cfnames($tfpre, 'oid')], 0), $tmptstr);
    $tmprstr .= $tmptstr;
  }
  $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
  $tsqlstr = "select * from $ndatabase where $nidfield=$tid";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs)
  {
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
  else
  {
    mm_client_alert(ii_itake('global.lng_public.sudd', 'lng'), -1);
  }
}

function wdja_cms_admin_manage_list()
{
  global $conn, $slng;
  global $ndatabase, $nidfield, $nfpre, $npagesize;
  $toffset = ii_get_num($_GET['offset']);
  $tgenre = ii_get_safecode($_GET['genre']);
  $search_field = ii_get_safecode($_GET['field']);
  $search_keyword = ii_get_safecode($_GET['keyword']);
  $tmpstr = ii_itake('manage.list', 'tpl');
  $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_ida}');
  $tmprstr = '';
  $tsqlstr = "select * from $ndatabase where " . ii_cfname('lng') . "= '" . $slng . "'";
  if ($search_field == 'topic') $tsqlstr .= " and " . ii_cfname('topic') . " like '%" . $search_keyword . "%'";
  if ($search_field == 'id') $tsqlstr .= " and $nidfield=" . ii_get_num($search_keyword);
  if (!ii_isnull($tgenre)) $tsqlstr .= " and " . ii_cfname('genre') . "='" . $tgenre . "'";
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
      $ttopic = ii_htmlencode($trs[ii_cfname('topic')]);
      if (isset($font_red))
      {
        $font_red = str_replace('{$explain}', $search_keyword, $font_red);
        $ttopic = str_replace($search_keyword, $font_red, $ttopic);
      }
      $tmptstr = str_replace('{$topic}', $ttopic, $tmpastr);
      $tmptstr = str_replace('{$topicstr}', ii_encode_scripts(ii_htmlencode($trs[ii_cfname('topic')])), $tmptstr);
      $tmptstr = str_replace('{$name}', ii_htmlencode($trs[ii_cfname('name')]), $tmptstr);
      $tmptstr = str_replace('{$hidden}', ii_itake('global.sel_yesno.'.ii_get_num($trs[ii_cfname('hidden')]), 'sel'), $tmptstr);
      $tmptstr = str_replace('{$hidden_list}', ii_itake('global.sel_yesno.'.ii_get_num($trs[ii_cfname('hidden_list')]), 'sel'), $tmptstr);
      $tmptstr = str_replace('{$hidden_detail}', ii_itake('global.sel_yesno.'.ii_get_num($trs[ii_cfname('hidden_detail')]), 'sel'), $tmptstr);
      $tmptstr = str_replace('{$time}', ii_get_date($trs[ii_cfname('time')]), $tmptstr);
      $tmptstr = str_replace('{$type}', ii_get_num($trs[ii_cfname('type')]), $tmptstr);
      $tmptstr = str_replace('{$genre}',ii_itake('global.'.$trs[ii_cfname('genre')].':module.channel_title', 'lng'), $tmptstr);
      $tmptstr = str_replace('{$id}', ii_get_num($trs[$nidfield]), $tmptstr);
      $tmprstr .= $tmptstr;
    }
  }
  $tmpstr = str_replace('{$cpagestr}', $tcp -> get_pagenum(), $tmpstr);
  $tmpstr = str_replace('{$tgenre}', $tgenre, $tmpstr);
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
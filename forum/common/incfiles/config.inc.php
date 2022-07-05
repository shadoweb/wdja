<?php
/*
判断是否作为网站首页使用
*/
if (ii_strlen(dirname($_SERVER['PHP_SELF']))>1) {
  $nroute = 'node';
  $ngenre = ii_get_actual_genre(__FILE__, $nroute);
}else{
  $nroute = '';
  $ngenre = 'forum';
}
wdja_cms_init($nroute);
$nhead = $variable[ii_cvgenre($ngenre) . '.nhead'];
$nfoot = $variable[ii_cvgenre($ngenre) . '.nfoot'];
if (ii_isnull($nhead)) $nhead = $default_head;
if (ii_isnull($nfoot)) $nfoot = $default_foot;
$ndatabase = $variable[ii_cvgenre($ngenre) . '.ndatabase'];
$nidfield = $variable[ii_cvgenre($ngenre) . '.nidfield'];
$nfpre = $variable[ii_cvgenre($ngenre) . '.nfpre'];
$npagesize = $variable[ii_cvgenre($ngenre) . '.npagesize'];
$nuppath = $variable[ii_cvgenre($ngenre) . '.nuppath'];
$nuptype = $variable[ii_cvgenre($ngenre) . '.nuptype'];
$nlisttopx = $variable[ii_cvgenre($ngenre) . '.nlisttopx'];
$npagesize_reply = ii_get_num($variable[ii_cvgenre($ngenre) . '.npagesize_reply']);
$nint_topic = ii_get_num($variable[ii_cvgenre($ngenre) . '.nint_topic']);
$nint_reply = ii_get_num($variable[ii_cvgenre($ngenre) . '.nint_reply']);
$nmax_vote_option = ii_get_num($variable[ii_cvgenre($ngenre) . '.nmax_vote_option']);
$nuser_upload = ii_get_num($variable[ii_cvgenre($ngenre) . '.nuser_upload']);
$nnew_user_release_timeout = ii_get_num($variable[ii_cvgenre($ngenre) . '.nnew_user_release_timeout']);
$ntitle = ii_itake('module.channel_title', 'lng');

function pp_change_forum_topic($topic, $color, $b)
{
  $ttopic = $topic;
  if (!ii_isnull($color)) $ttopic = '<font color="' . ii_htmlencode($color) . '">' . $ttopic . '</font>';
  if (ii_get_num($b) == 1) $ttopic = '<b>' . $ttopic . '</b>';
  return $ttopic;
}

function pp_change_forum_vote_type($strers)
{
  if ($strers == 0) return 'radio';
  else return 'checkbox';
}

function pp_check_forum_islock($strids, $strmyid, $strisuname, $strunames, $strmyuname)
{
  if (ii_isnull($strids)) $tbool1 = true;
  else
  {
    if (ii_cinstr($strids, $strmyid, ',')) $tbool1 = true;
    else $tbool1 = false;
  }
  if (ii_get_num($strisuname) == 2)
  {
    if (ii_cinstr($strunames, $strmyuname, ',')) $tbool2 = true;
    else $tbool2 = false;
  }
  else $tbool2 = true;
  if ($tbool1 && $tbool2) return false;
  else return true;
}

function pp_check_forum_isnew($strdate)
{
  if (ii_isdate($strdate))
  {
    if (ii_datediff('d', $strdate, ii_now()) == 0) return true;
    else return false;
  }
  else return false;
}

function pp_check_forum_isadmin($sid, $username)
{
  global $conn, $ngenre;
  $tsid = ii_get_num($sid);
  if ($tsid == 0) return 0;
  else
  {
    $tisadmin = ap_get_userinfo('forum_admin', $username);
    if ($tisadmin == 1) return 1;
    else
    {
      $tdatabase = mm_cndatabase($ngenre, 'sort');
      $tidfield = mm_cnidfield($ngenre, 'sort');
      $tfpre = mm_cnfpre($ngenre, 'sort');
      $tsqlstr = "select * from $tdatabase where " . ii_cfnames($tfpre, 'hidden') . "=0 and $tidfield=$tsid";
      $trs = ii_conn_query($tsqlstr, $conn);
      $trs = ii_conn_fetch_array($trs);
      if ($trs)
      {
        if (ii_cinstr($trs[ii_cfnames($tfpre, 'admin')], $username, ',')) return 2;
        else return 0;
      }
      else return 0;
    }
  }
}

function pp_check_forum_popedom($sid, $type)
{
  global $conn;
  global $ngenre, $nusername;
  $tsid = ii_get_num($sid);
  $tutype = ap_get_userinfo('utype', $nusername);
  $tdatabase = mm_cndatabase($ngenre, 'sort');
  $tidfield = mm_cnidfield($ngenre, 'sort');
  $tfpre = mm_cnfpre($ngenre, 'sort');
  $tsqlstr = "select * from $tdatabase where " . ii_cfnames($tfpre, 'hidden') . "=0 and not " . ii_cfnames($tfpre, 'fsid') . "=0 and $tidfield=$tsid";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs)
  {
    if ($type == 1)
    {
      if (ii_get_num($trs[ii_cfnames($tfpre, 'mode')]) == 1) return 2;
      if (ii_get_num($trs[ii_cfnames($tfpre, 'type')]) == 1)
      {
        if (pp_check_forum_isadmin($tsid, $nusername) == 0) return 1;
      }
      if (ii_get_num($trs[ii_cfnames($tfpre, 'mode')]) == 2) return 2.5;
    }
    if (pp_check_forum_islock($trs[ii_cfnames($tfpre, 'popedom')], $tutype, $trs[ii_cfnames($tfpre, 'type')], $trs[ii_cfnames($tfpre, 'attestation')], $nusername)) return 3;
    return 0;
  }
  else return -1;
}

function pp_check_forum_blacklist($sid, $username)
{
  global $conn, $ngenre;
  $tdatabase = mm_cndatabase($ngenre, 'blacklist');
  $tidfield = mm_cnidfield($ngenre, 'blacklist');
  $tfpre = mm_cnfpre($ngenre, 'blacklist');
  $tsqlstr = "select * from $tdatabase where " . ii_cfnames($tfpre, 'username') . "='$username' and " . ii_cfnames($tfpre, 'sid') . "=$sid";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs) return true;
  else return false;
}

function pp_get_forum_admin($strers)
{
  if (!ii_isnull($strers))
  {
    $tmpstr = ii_itake('global.tpl_config.a_href_blank', 'tpl');
    $treturnstr = '';
    $tary = explode(',', $strers);
    foreach ($tary as $key => $val)
    {
      $tstr = $tmpstr;
      $tstr = str_replace('{$explain}', ii_htmlencode($val), $tstr);
      $tstr = str_replace('{$value}', ii_get_actual_route(USER_FOLDER) . '/?type=user_detail&username=' . urlencode($val), $tstr);
      $treturnstr .= $tstr . ' ';
    }
    return $treturnstr;
  }
}

function pp_get_forum_pic($strislock, $strdate)
{
  if ($strislock) return 'forum_lock';
  else
  {
    if (pp_check_forum_isnew($strdate)) return 'forum_new';
    else return 'forum';
  }
}

function pp_get_forum_topic_pic($htop, $top, $lock, $elite, $count)
{
  if ($htop == 1) return 'htop';
  elseif ($top == 1) return 'top';
  elseif ($lock == 1) return 'lock';
  elseif ($elite == 1) return 'elite';
  elseif ($count >= 200) return 'hot';
  else return 'normal';
}

function pp_get_forum_info($strsid, $strislock, $strid, $strtopic, $strtime, $strnum_new, $strnum_new_date, $strnum_topic, $strnum_note)
{
  $tmpstr = ii_itake('module.data_forum_info', 'tpl');
  $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_ida}');
  $tary = explode('{@@}', $tmpastr);
  if ($strislock) $tmprstr = $tary[1];
  else
  {
    $tsid = ii_get_num($strsid, 0);
    $tid = ii_get_num($strid, 0);
    $ttopic = $strtopic;
    if (ii_isnull($strtime)) $ttime = '';
    else $ttime = ii_get_date($strtime);
    $tnum_new = $strnum_new;
    if (!ii_isdate($strnum_new_date)) $tnum_new = 0;
    else
    {
      if (ii_format_date($strnum_new_date, 1) != ii_format_date(ii_now(), 1)) $tnum_new = 0;
    }
    $tnum_topic = $strnum_topic;
    $tnum_note = $strnum_note;
    $tmprstr = $tary[0];
    $tmprstr = str_replace('{$sid}', ii_htmlencode($tsid), $tmprstr);
    $tmprstr = str_replace('{$tid}', ii_htmlencode($tid), $tmprstr);
    $tmprstr = str_replace('{$topic}', ii_htmlencode($ttopic), $tmprstr);
    $tmprstr = str_replace('{$time}', ii_htmlencode($ttime), $tmprstr);
    $tmprstr = str_replace('{$num_new}', ii_htmlencode(number_format(ii_get_num($tnum_new, 0))), $tmprstr);
    $tmprstr = str_replace('{$num_topic}', ii_htmlencode(number_format(ii_get_num($tnum_topic, 0))), $tmprstr);
    $tmprstr = str_replace('{$num_note}', ii_htmlencode(number_format(ii_get_num($tnum_note, 0))), $tmprstr);
  }
  $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function pp_get_forum_files_list($strers)
{
  $option_unselected = ii_itake('global.tpl_config.option_unselect', 'tpl');
  $tstrers = ii_htmlencode($strers, 1);
  if (!ii_isnull($tstrers))
  {
    $treturnstr = '';
    $tary = explode('|', $tstrers);
    foreach ($tary as $key => $val)
    {
      $tmpstr = str_replace('{$explain}', $val, $option_unselected);
      $tmpstr = str_replace('{$value}', $val, $tmpstr);
      $treturnstr .= $tmpstr;
    }
    return $treturnstr;
  }
}

function pp_get_mysortary($lng, $fsid)
{
  global $conn, $ngenre;
  $tdatabase = mm_cndatabase($ngenre, 'sort');
  $tidfield = mm_cnidfield($ngenre, 'sort');
  $tfpre = mm_cnfpre($ngenre, 'sort');
  $tarys = Array();
  $tlng = ii_get_safecode($lng);
  $tfsid = ii_get_num($fsid);
  $tsqlstr = "select * from $tdatabase where " . ii_cfnames($tfpre, 'fsid') . "=$tfsid and " . ii_cfnames($tfpre, 'lng') . "='$tlng' and " . ii_cfnames($tfpre, 'hidden') . "=0 order by " . ii_cfnames($tfpre, 'order') . " asc";
  $trs = ii_conn_query($tsqlstr, $conn);
  while ($trow = ii_conn_fetch_array($trs))
  {
    $tary[$trow[$tidfield]]['id'] = $trow[$tidfield];
    $tary[$trow[$tidfield]]['sort'] = $trow[ii_cfnames($tfpre, 'sort')];
    $tary[$trow[$tidfield]]['fid'] = $trow[ii_cfnames($tfpre, 'fid')];
    $tary[$trow[$tidfield]]['fsid'] = $trow[ii_cfnames($tfpre, 'fsid')];
    $tary[$trow[$tidfield]]['order'] = $trow[ii_cfnames($tfpre, 'order')];
    $tarys += $tary;
    $tarys += pp_get_mysortary($tlng, $trow[$tidfield]);
  }
  return $tarys;
}

function pp_get_sortary($lng)
{
  global $ngenre;
  $tappstr = $ngenre . '_sort_' . $lng;
  if (ii_cache_is($tappstr))
  {
    ii_cache_get($tappstr, 1);
  }
  else
  {
    $tary = pp_get_mysortary($lng, 0);
    ii_cache_put($tappstr, 1, $tary);
    $GLOBALS[$tappstr] = $tary;
  }
  return $GLOBALS[$tappstr];
}

function pp_get_sortfid($fid, $sid)
{
  if (ii_isnull($fid) || $fid == '0') return $sid;
  else return $fid . ',' . $sid;
}

function pp_get_sortfid_count($fid)
{
  global $conn, $slng;
  global $ndatabase, $nidfield, $nfpre;
  $tsqlstr = "select count($nidfield) from $ndatabase where " . ii_cfname('fid') . "='$fid' and " . ii_cfname('lng') . "='$slng'";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs) return $trs[0];
  else return 0;
}

function pp_nav_forum()
{
  global $nlng, $ngenre;
  $tsid = ii_get_num($_GET['sid']);
  $toutstr = '';
  $tpl_href = ii_itake('global.tpl_config.a_href_self', 'tpl');
  $tary = pp_get_sortary($nlng);
  if (is_array($tary))
  {
    foreach ($tary as $key => $val)
    {
      if ($key == $tsid) $tfid = pp_get_sortfid($val['fid'], $val['id']);
    }
    if (isset($tfid))
    {
      foreach ($tary as $key => $val)
      {
        if (ii_cinstr($tfid, $key, ',') && $val['fsid'] != 0)
        {
          $toutstr .= NAV_SP_STR . $tpl_href;
          $toutstr = str_replace('{$explain}', $val['sort'], $toutstr);
          $toutstr = str_replace('{$value}', ii_get_actual_route($ngenre) . '/?type=list&amp;sid=' . $val['id'], $toutstr);
        }
      }
    }
  }
  return $toutstr;
}

function pp_sel_forum_sort($sid, $lng)
{
  $tary = pp_get_sortary($lng);
  if (is_array($tary))
  {
    $tsid = ii_get_num($sid);
    $trestr = ii_itake('global.tpl_config.sys_spsort', 'tpl');
    $option_unselected = ii_itake('global.tpl_config.option_unselect', 'tpl');
    $option_selected = ii_itake('global.tpl_config.option_select', 'tpl');
    $tmpstr = '';
    $treturnstr = '';
    foreach ($tary as $key => $val)
    {
      if ($key == $tsid) $tmpstr = $option_selected;
      else $tmpstr = $option_unselected;
      $tmpstr = str_replace('{$explain}', str_repeat($trestr, mm_get_sortfid_incount($val['fid'], ',') + 1) . $val['sort'], $tmpstr);
      $tmpstr = str_replace('{$value}', $val['id'], $tmpstr);
      $treturnstr .= $tmpstr;
    }
    return $treturnstr;
  }
}
?>

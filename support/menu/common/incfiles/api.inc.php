<?php

function mm_nav_menu($mgroup, $baseurl, $id)
{
  global $conn,$slng, $variable;
  ii_conn_init();
  $tgenre = 'support/menu';
  $ndatabase = $variable[ii_cvgenre($tgenre) . '.ndatabase'];
  $nidfield = $variable[ii_cvgenre($tgenre) . '.nidfield'];
  $nfpre = $variable[ii_cvgenre($tgenre) . '.nfpre'];
  $tid = ii_get_num($id);
  $tpl_href = ii_itake('global.tpl_config.a_href_sort', 'tpl');
  $tsqlstr = "select * from $ndatabase where $nidfield=$tid";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs)
  {
    $tfid = $trs[ii_cfnames($nfpre, 'fid')];
    $tfid = mm_get_sortfid($tfid, $tid);
    if (ii_cidary($tfid))
    {
      $tmpstr = '';
      $font_disabled = ii_itake('global.tpl_config.font_disabled', 'tpl');
      $tsqlstr = "select * from $ndatabase where $nidfield in (" . $tfid . ") and " . ii_cfnames($nfpre, 'group') . "='$mgroup' and " . ii_cfnames($nfpre, 'lng') . "='$slng' order by $nidfield asc";
      $trs = ii_conn_query($tsqlstr, $conn);
      while ($trow = ii_conn_fetch_array($trs))
      {
        $ttopic = $trow[ii_cfnames($nfpre, 'topic')];
        if ($trow[ii_cfnames($nfpre, 'hidden')] == 1) $tsort = str_replace('{$explain}', $ttopic, $font_disabled);
        $tstr = $tpl_href;
        $tstr = str_replace('{$explain}', $ttopic, $tstr);
        $tstr = str_replace('{$value}', $baseurl . $trow[$nidfield], $tstr);
        $tmpstr .= $tstr;
      }
      return $tmpstr;
    }
  }
}

function mm_get_mymenuary($group,$lng,$fsid)
{
  global $conn, $variable;
  ii_conn_init();
  $tgenre = 'support/menu';
  $ndatabase = $variable[ii_cvgenre($tgenre) . '.ndatabase'];
  $nidfield = $variable[ii_cvgenre($tgenre) . '.nidfield'];
  $nfpre = $variable[ii_cvgenre($tgenre) . '.nfpre'];
  $tarys = Array();
  $tgroup = ii_get_num($group);
  $tfsid = ii_get_num($fsid);
  $tlng = ii_get_safecode($lng);
  $tsqlstr = "select * from $ndatabase where " . ii_cfnames($nfpre, 'group') . "='$tgroup' and " . ii_cfnames($nfpre, 'fsid') . "=$tfsid and " . ii_cfnames($nfpre, 'lng') . "='$tlng' order by " . ii_cfnames($nfpre, 'time') . " asc";
  $trs = ii_conn_query($tsqlstr, $conn);
  while ($trow = ii_conn_fetch_array($trs))
  {
    $tary[$trow[$nidfield]]['id'] = $trow[$nidfield];
    $tary[$trow[$nidfield]]['fid'] = $trow[ii_cfnames($nfpre, 'fid')];
    $tary[$trow[$nidfield]]['topic'] = $trow[ii_cfnames($nfpre, 'topic')];
    $tary[$trow[$nidfield]]['fsid'] = $trow[ii_cfnames($nfpre, 'fsid')];
    $tary[$trow[$nidfield]]['order'] = $trow[ii_cfnames($nfpre, 'order')];
    $tarys += $tary;
    $tarys += mm_get_mymenuary($tgroup, $tlng, $trow[$nidfield]);
  }
  return $tarys;
}

function mm_get_menuary($group,$lng)
{
    $tary = mm_get_mymenuary($group, $lng, 0);
    $GLOBALS[$tappstr] = $tary;
    return $GLOBALS[$tappstr];
}

function mm_sel_menu($group,$lng,$fsid)
{
  $tary = mm_get_menuary($group,$lng);
  if (is_array($tary))
  {
    $trestr = ii_itake('global.tpl_config.sys_spsort', 'tpl');
    $option_pre = '<option value="0" selected>'.ii_itake('global.lng_config.unselect', 'lng').'</option>';
    $option_unselected = ii_itake('global.tpl_config.option_unselect', 'tpl');
    $option_selected = ii_itake('global.tpl_config.option_select', 'tpl');
    $tmpstr = '';
    $treturnstr = '';
    foreach ($tary as $key => $val)
    {
      if ($key == $fsid) $tmpstr = $option_selected;
      else $tmpstr = $option_unselected;
      $tmpstr = str_replace('{$explain}', str_repeat($trestr, mm_get_sortfid_incount($val['fid'], ',') + 1) . $val['topic'], $tmpstr);
      $tmpstr = str_replace('{$value}', $val['id'], $tmpstr);
      $treturnstr .= $tmpstr;
    }
    return $option_pre.$treturnstr;
  }else{
    return $option_pre;
  }
}

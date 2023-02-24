<?php

function mm_get_sort_field($id,$field)
{
  //获取分类任意字段
  global $conn, $variable, $sort_database, $sort_idfield, $sort_fpre;
  $tmpstr = '';
  $tsqlstr = 'select * from '. $sort_database.' where '. $sort_idfield.' = ' .$id;
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  return $trs[ii_cfnames($sort_fpre,$field)];
}

function mm_get_firstSortId($ngenre) {
//获取模块第一个分类id
global $conn, $nlng;
global $variable, $sort_database, $sort_idfield, $sort_fpre;
$tsqlstr = 'select * from '. $sort_database.' where ' . ii_cfnames($sort_fpre,'genre') . ' = "' .$ngenre.'"  and ' . ii_cfnames($sort_fpre,'lng') . ' = "' .$nlng.'" order by '.$sort_idfield.' asc';
$trs = ii_conn_query($tsqlstr, $conn);
$trs = ii_conn_fetch_array($trs);
return $trs[$sort_idfield];
}

function mm_sel_sort_list($genre, $lng, $sid)
{
  $tary = mm_get_sortary($genre, $lng);
  if (is_array($tary))
  {
    $tsid = ii_get_safecode($sid);
    $trestr = ii_itake('global.tpl_config.sys_spsort', 'tpl');
    $option_pre = '';//'<option value="0" selected>'.ii_itake('global.lng_config.unselect', 'lng').'</option>';
    $option_unselected = ii_itake('global.tpl_config.option_unselect', 'tpl');
    $option_selected = ii_itake('global.tpl_config.option_select', 'tpl');
    $tmpstr = '';
    $treturnstr = '';
    foreach ($tary as $key => $val)
    {
      //if ($key == $tsid) $tmpstr = $option_selected;
      $tgourl = mm_get_sort_field($val['id'],'gourl');
      $tfgourl = mm_get_sort_field($val['fid'],'gourl');
      if (!ii_isnull($tgourl) || !ii_isnull($tfgourl)) continue;
      if (ii_cinstr($tsid,$key,',')) $tmpstr = $option_selected;
      else $tmpstr = $option_unselected;
      $tmpstr = str_replace('{$explain}', str_repeat($trestr, mm_get_sortfid_incount($val['fid'], ',') + 1) . $val['sort'], $tmpstr);
      $tmpstr = str_replace('{$value}', $val['id'], $tmpstr);
      $treturnstr .= $tmpstr;
    }
    return $option_pre.$treturnstr;
  }else{
    return $option_pre;
  }
}
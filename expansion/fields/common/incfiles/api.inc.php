<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
function pp_get_genre_select($module='')
{
  global $variable;
  $tary = ii_get_valid_module();
  if (is_array($tary))
  {
    $tmpstr = '';
    $option_selected = ii_itake('global.tpl_config.option_select', 'tpl');
    $option_unselected = ii_itake('global.tpl_config.option_unselect', 'tpl');
    foreach ($tary as $key => $val)
    {
      if (!ii_isnull($module) && $val == $module) $tmprstr = $option_selected;
      else $tmprstr = $option_unselected;
      $nfields = ii_get_num($variable[ii_cvgenre($val) . '.nfields'],0);
      if ($nfields == 1) {
        $tmprstr = str_replace('{$explain}', '(' . mm_get_genre_title($val) . ')' , $tmprstr);
        $tmprstr = str_replace('{$value}', $val, $tmprstr);
      }
      else continue;
      $tmpstr .= $tmprstr;
    }
    return $tmpstr;
  }
}

function api_get_gid() {
  global $nurs;
  $fields_arr = api_fields_convertUrlQuery($nurs);
  $gid_arr = array();
  $gid_narr = array();
  foreach ($fields_arr as $key => $val) {
      if(!ii_isnull($val)){
        $tkey = str_replace('f','',$key);
        $gid_narr = api_get_fields_gid($tkey,$val);
        if (count($gid_arr) > 0) $gid_arr = array_intersect($gid_arr,$gid_narr);
        else $gid_arr = $gid_narr;
        if (count($gid_arr) < 1) return -1;
      }
  }
  $tgid = implode(",", $gid_arr);
  return $tgid;
}

function api_fields_convertUrlQuery($query)
{
  $queryParts = explode('&', $query);
  $params = array();
  foreach ($queryParts as $param) {
    $item = explode('=', $param);
    if ($item[0] != 'type' && $item[0] != 'classid' && $item[0] != 'offset' && $item[1] != 0) $params[$item[0]] = $item[1];
  }
  return $params;
}

function api_get_fields_gid($oid,$data)
{
  //获取存储的属性对应商品ID
  global $conn;
  $fgenre = 'expansion/fields';
  $fdatabase = mm_cndatabase(ii_cvgenre($fgenre), 'gid');
  $fidfield = mm_cnidfield(ii_cvgenre($fgenre), 'gid');
  $ffpre = mm_cnfpre(ii_cvgenre($fgenre), 'gid');
  $fid = $oid;
  $tmpstr = '';
  $gid = array();
  $tsqlstr = 'select '. ii_cfnames($ffpre,"gid") .' from '. $fdatabase.' where '.ii_cfnames($ffpre,"fid").' = '.$fid.' and ('.ii_cfnames($ffpre,"data").' = ' .$data.' or '.ii_cfnames($ffpre,"data").' like \'%|' .$data.'|%\')';
  $trs = ii_conn_query($tsqlstr, $conn);
  while ($trow = ii_conn_fetch_array($trs))
  {
    if ($trow) {
      $gid[] = $trow[ii_cfnames($ffpre,"gid")];
    }
  }
  return $gid;
}

function api_list_fields_input() {
  //前台列表页固定表单项
  global $conn, $ngenre, $nlng;
  $fgenre = 'expansion/fields';
  $fdatabase = mm_cndatabase(ii_cvgenre($fgenre));
  $fidfield = mm_cnidfield(ii_cvgenre($fgenre));
  $ffpre = mm_cnfpre(ii_cvgenre($fgenre));
  $tmpstr = '';
  $tsqlstr = 'select * from '. $fdatabase.' where '.ii_cfnames($ffpre,"hidden").'=0 and '.ii_cfnames($ffpre,"lng").'="' . $nlng . '" and '.ii_cfnames($ffpre,"genre").'="'.$ngenre.'"';
  $trs = ii_conn_query($tsqlstr, $conn);
  while ($trow = ii_conn_fetch_array($trs))
  {
    foreach ($trow as $key => $val)
    {
      $tkey = ii_get_lrstr($key, '_', 'rightr');
      if ($tkey == 'type') $type = $val;
    }
    $oid = $trow[$fidfield];
    if ($type != 3 && $type != 4 && $type != 5 && $type != 6) $tmpstr .= api_list_input($oid);
  }
  return $tmpstr;
}

function api_list_input($oid)
{
  global $conn, $nlng;
  $fgenre = 'expansion/fields';
  $fdatabase = mm_cndatabase(ii_cvgenre($fgenre));
  $fidfield = mm_cnidfield(ii_cvgenre($fgenre));
  $ffpre = mm_cnfpre(ii_cvgenre($fgenre));
  $tid = $oid;
  $tmpstr = ii_itake('global.expansion/fields:module.listinput', 'tpl');
  if (!ii_isnull($tmpstr))
  {
    $tsqlstr = "select * from $fdatabase where $fidfield=$tid";
    $trs = ii_conn_query($tsqlstr, $conn);
    $trs = ii_conn_fetch_array($trs);
    if ($trs)
    {
      $tmpstr = str_replace('{$ctopic}', ii_htmlencode($trs[ii_cfnames($ffpre,'topic')]), $tmpstr);
      $tmpstr = str_replace('{$type}', ii_htmlencode($trs[ii_cfnames($ffpre,'type')]), $tmpstr);
      $tmpstr = str_replace('{$oid}', $trs[$fidfield], $tmpstr);
    }
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
  }
}

function api_list_form($classid,$offset) {
  //前台筛选显示调用
  global $ngenre;
  $tmpstr = ii_ireplace('global.expansion/fields:module.listform', 'tpl');
  $tmpstr = str_replace('{$ngenre}', $ngenre, $tmpstr);
  $tmpstr = str_replace('{$classid}', ii_htmlencode($classid), $tmpstr);
  $tmpstr = str_replace('{$offset}', ii_htmlencode($offset), $tmpstr);
  return $tmpstr;
}

function api_list_sorts() {
  //前台筛选显示调用
  global $ngenre;
  $tmpstr = vv_isort($ngenre, 'tpl=global.expansion/fields:module.listsort');
  return $tmpstr;
}

function api_list_fields() {
  //前台筛选显示调用
  global $conn, $ngenre, $nlng;
  $fgenre = 'expansion/fields';
  $fdatabase = mm_cndatabase(ii_cvgenre($fgenre));
  $fidfield = mm_cnidfield(ii_cvgenre($fgenre));
  $ffpre = mm_cnfpre(ii_cvgenre($fgenre));
  $tmpstr = '';
  $tsqlstr = 'select * from '. $fdatabase.' where '.ii_cfnames($ffpre,"hidden").'=0 and '.ii_cfnames($ffpre,"lng").'="' . $nlng . '" and '.ii_cfnames($ffpre,"genre").'="'.$ngenre.'"';
  $trs = ii_conn_query($tsqlstr, $conn);
  while ($trow = ii_conn_fetch_array($trs))
  {
    foreach ($trow as $key => $val)
    {
      $tkey = ii_get_lrstr($key, '_', 'rightr');
      if ($tkey == 'type') $type = $val;
    }
    $oid = $trow[$fidfield];
    if ($type != 3 && $type != 4 && $type != 5 && $type != 6) $tmpstr .= api_list_fields_radio($oid);//这里排除筛选字段类型ID,3为单选文本,4为多行文本
  }
  return $tmpstr;
}

function api_list_fields_radio($oid)
{
  global $conn;
  $fgenre = 'expansion/fields';
  $fdatabase = mm_cndatabase(ii_cvgenre($fgenre));
  $fidfield = mm_cnidfield(ii_cvgenre($fgenre));
  $ffpre = mm_cnfpre(ii_cvgenre($fgenre));
  $tid = $oid;
  $tmpstr = ii_itake('global.expansion/fields:module.listradio', 'tpl');
  if (!ii_isnull($tmpstr))
  {
    $tsqlstr = "select * from $fdatabase where $fidfield=$tid";
    $trs = ii_conn_query($tsqlstr, $conn);
    $trs = ii_conn_fetch_array($trs);
    if ($trs)
    {
      $tmpstr = str_replace('{$ctopic}', ii_htmlencode($trs[ii_cfnames($ffpre,'topic')]), $tmpstr);
      $tmpstr = str_replace('{$type}', ii_htmlencode($trs[ii_cfnames($ffpre,'type')]), $tmpstr);
      $tmpstr = str_replace('{$oid}', $trs[$fidfield], $tmpstr);
    }
    $fdatabase = mm_cndatabase(ii_cvgenre($fgenre), 'data');
    $fidfield = mm_cnidfield(ii_cvgenre($fgenre), 'data');
    $ffpre = mm_cnfpre(ii_cvgenre($fgenre), 'data');
    $tsqlstr = "select * from $fdatabase where " . ii_cfnames($ffpre,'fid') . "=$tid order by " . ii_cfnames($ffpre,'oid') . " asc";
    $tmpastr = ii_ctemplate($tmpstr, '{@}');
    $tmprstr = '';
    $trs = ii_conn_query($tsqlstr, $conn);
    while ($trow = ii_conn_fetch_array($trs))
    {
      $tmptstr = $tmpastr;
      foreach ($trow as $key => $val)
      {
        $tkey = ii_get_lrstr($key, '_', 'rightr');
        $GLOBALS['RS_' . $tkey] = $val;
        $tmptstr = str_replace('{$' . $tkey . '}', ii_htmlencode($val), $tmptstr);
      }
      $tmptstr = str_replace('{$id}', $trow[ii_cfnames($ffpre,'oid')], $tmptstr);
      $tmprstr .= $tmptstr;
    }
    $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
  }
}

function api_list_fields_select($oid)
{
  global $conn;
  $fgenre = 'expansion/fields';
  $fdatabase = mm_cndatabase(ii_cvgenre($fgenre));
  $fidfield = mm_cnidfield(ii_cvgenre($fgenre));
  $ffpre = mm_cnfpre(ii_cvgenre($fgenre));
  $tid = $oid;
  $tmpstr = ii_itake('global.expansion/fields:manage.select', 'tpl');
  if (!ii_isnull($tmpstr))
  {
    $tsqlstr = "select * from $fdatabase where $fidfield=$tid";
    $trs = ii_conn_query($tsqlstr, $conn);
    $trs = ii_conn_fetch_array($trs);
    if ($trs)
    {
      $tmpstr = str_replace('{$ctopic}', ii_htmlencode($trs[ii_cfnames($ffpre,'topic')]), $tmpstr);
      $tmpstr = str_replace('{$type}', ii_htmlencode($trs[ii_cfnames($ffpre,'type')]), $tmpstr);
      $tmpstr = str_replace('{$oid}', $trs[$fidfield], $tmpstr);
    }
    $fdatabase = mm_cndatabase(ii_cvgenre($fgenre), 'data');
    $fidfield = mm_cnidfield(ii_cvgenre($fgenre), 'data');
    $ffpre = mm_cnfpre(ii_cvgenre($fgenre), 'data');
    $tsqlstr = "select * from $fdatabase where " . ii_cfnames($ffpre,'fid') . "=$tid order by " . ii_cfnames($ffpre,'oid') . " asc";
    $tmpastr = ii_ctemplate($tmpstr, '{@}');
    $tmprstr = '';
    $trs = ii_conn_query($tsqlstr, $conn);
    while ($trow = ii_conn_fetch_array($trs))
    {
      $tmptstr = $tmpastr;
      foreach ($trow as $key => $val)
      {
        $tkey = ii_get_lrstr($key, '_', 'rightr');
        $GLOBALS['RS_' . $tkey] = $val;
        $tmptstr = str_replace('{$' . $tkey . '}', ii_htmlencode($val), $tmptstr);
      }
      $tmptstr = str_replace('{$id}', $trow[ii_cfnames($ffpre,'oid')], $tmptstr);
      $tmprstr .= $tmptstr;
    }
    $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
  }
}

function api_replace_fields($str,$id='',$genre='') {
  global $conn, $ngenre, $nlng;
  $fgenre = 'expansion/fields';
  $fdatabase = mm_cndatabase(ii_cvgenre($fgenre));
  $fidfield = mm_cnidfield(ii_cvgenre($fgenre));
  $ffpre = mm_cnfpre(ii_cvgenre($fgenre));
  $type = ii_cstr($_GET['type']);
  $gid = ii_isnull($id) ? $_GET['id']:$id;
  $tgenre = ii_isnull($genre) ? $ngenre:$genre;
  $tmpstr = '';
  $tsqlstr = 'select * from '. $fdatabase.' where '.ii_cfnames($ffpre,"hidden").'=0 and '.ii_cfnames($ffpre,"lng").'="'.$nlng.'" and '.ii_cfnames($ffpre,"genre").'="'.$tgenre.'"';
  if ($type == 'detail') $tsqlstr .= ' and '.ii_cfnames($ffpre,"hidden_detail").'=0 ';
  else $tsqlstr .= ' and '.ii_cfnames($ffpre,"hidden_list").'=0 ';//默认列表页url无type=list
  $trs = ii_conn_query($tsqlstr, $conn);
  while ($trow = ii_conn_fetch_array($trs))
  {
    $str = str_replace('{$' . $trow[ii_cfnames($ffpre,"name")] . '_topic}', $trow[ii_cfnames($ffpre,"topic")], $str);
    $str = str_replace('{$' . $trow[ii_cfnames($ffpre,"name")] . '}', api_replace_fields_value($trow[ii_cfnames($ffpre,"genre")],$trow[ii_cfnames($ffpre,"type")],$trow[$fidfield],$gid), $str);
  }
  return $str;
}

function api_replace_fields_value($genre,$type,$fid,$gid)
{
  switch($type)
  {
    case 0:
    case 1:
    case 2:
      return api_replace_fields_data($genre,$fid,$gid);
      break;
    case 3:
    case 4:
    case 5:
    case 6:
    default:
      return api_get_fields_data($fid,$gid);
      break;
  }
}

function api_replace_fields_data($genre,$fid,$gid){
    global $conn;
    $tdata = api_get_fields_data($fid,$gid);
    $tdata_arr = explode("|", $tdata);
    $trs = '';
    foreach ($tdata_arr as $oid)
    {
      if(!ii_isnull($oid)) $trs .= ' <a href="/'.$genre.'/?type=list&f'.$fid.'='.$oid.'">'.api_get_fields_topic($fid,$oid).'</a>, ';
    }
    $trs = ii_get_lrstr($trs, ',', 'leftr');
    return $trs;
}

//以下为后台商城模块接入使用
//后台管理页引入   require('config/common/incfiles/api_fields.inc.php');
//添加时模板中引入   {$=api_fields_add()}
//编辑时模板中引入   {$=api_fields_edit()}
//添加时代码中引入   api_save_fields($upfid);
//编辑时代码中引入   api_update_fields($tid);
//
function api_update_fields($gid)
{
  //循环保存
  global $conn, $ngenre;
  $fgenre = 'expansion/fields';
  $fdatabase = mm_cndatabase(ii_cvgenre($fgenre));
  $fidfield = mm_cnidfield(ii_cvgenre($fgenre));
  $ffpre = mm_cnfpre(ii_cvgenre($fgenre));
  $tmpstr = '';
  $tsqlstr = 'select * from '. $fdatabase.' where '.ii_cfnames($ffpre,"hidden").'=0 and '.ii_cfnames($ffpre,"genre").'="'.$ngenre.'"';
  $trs = ii_conn_query($tsqlstr, $conn);
  while ($trow = ii_conn_fetch_array($trs))
  {
    //需判断属性是否已添加过，添加过则更新，没添加过则保存
    if (api_check_fields_data($trow[$fidfield],$gid)) api_update_fields_sql($trow[$fidfield],$gid,$trow[ii_cfnames($ffpre,"type")]);
    else api_save_fields_sql($trow[$fidfield],$gid,$trow[ii_cfnames($ffpre,"type")]);
  }
}

function api_check_fields_data($fid,$gid)
{
  //判断属性是否已添加过
  global $conn;
  $check = false;
  $fgenre = 'expansion/fields';
  $fdatabase = mm_cndatabase(ii_cvgenre($fgenre), 'gid');
  $fidfield = mm_cnidfield(ii_cvgenre($fgenre), 'gid');
  $ffpre = mm_cnfpre(ii_cvgenre($fgenre), 'gid');
  $tmpstr = '';
  $tsqlstr = 'select * from '. $fdatabase.' where '.ii_cfnames($ffpre,"fid").' = '.$fid.' and '.ii_cfnames($ffpre,"gid").' = ' .$gid;
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs)  $check = true;
  return $check;
}

function api_update_fields_sql($id,$gid,$type='0') {
  //更新操作
  global $conn;
  $fgenre = 'expansion/fields';
  $fdatabase = mm_cndatabase(ii_cvgenre($fgenre), 'gid');
  $fidfield = mm_cnidfield(ii_cvgenre($fgenre), 'gid');
  $ffpre = mm_cnfpre(ii_cvgenre($fgenre), 'gid');
  if ($type == '1') {
    $tdata = '|';
    $tdata_arr = $_POST['fields_'.$id];
    foreach ($tdata_arr as $key => $val)
    {
      $tdata .= $val.'|';
    }
  }elseif ($type == '3') {
    $tdata = ii_left(ii_cstr($_POST['fields_'.$id]), 250);
  }elseif ($type == '4') {
    $tdata = ii_left(ii_cstr($_POST['fields_'.$id]), 100000);
  }elseif ($type == '5') {
    $tdata = ii_left(ii_cstr($_POST['fields_'.$id]), 250);
  }elseif ($type == '6') {
    $tdata = ii_left(ii_cstr($_POST['fields_'.$id]), 100000);
  }else{
    $tdata = ii_get_num($_POST['fields_'.$id]);
  }
  $tsqlstr = "update $fdatabase set
      " . ii_cfnames($ffpre,'data') . "='".$tdata."',
      " . ii_cfnames($ffpre,'time') . "='" . ii_now() . "',
      " . ii_cfnames($ffpre,'update') . "='" . ii_now() . "'
      where " . ii_cfnames($ffpre,'fid') . "='" . ii_get_num($id) . "' and ". ii_cfnames($ffpre,'gid') . "=" . ii_get_num($gid);
  $trs = ii_conn_query($tsqlstr, $conn);
}

function api_fields_edit()
{
  //编辑商品时调用
  global $conn, $ngenre, $slng;
  $fgenre = 'expansion/fields';
  $fdatabase = mm_cndatabase(ii_cvgenre($fgenre));
  $fidfield = mm_cnidfield(ii_cvgenre($fgenre));
  $ffpre = mm_cnfpre(ii_cvgenre($fgenre));
  $gid = $_GET['id'];
  $tmpstr = '';
  $tsqlstr = 'select * from '. $fdatabase.' where '.ii_cfnames($ffpre,"hidden").'=0 and '.ii_cfnames($ffpre,"lng").'="'.$slng.'" and '.ii_cfnames($ffpre,"genre").'="'.$ngenre.'"';
  $trs = ii_conn_query($tsqlstr, $conn);
  while ($trow = ii_conn_fetch_array($trs))
  {
    foreach ($trow as $key => $val)
    {
      $tkey = ii_get_lrstr($key, '_', 'rightr');
      if ($tkey == 'type') $type = $val;
    }
    $oid = $trow[$fidfield];
    $tmpstr .= api_get_fields_label($type,$oid,$gid) ;
  }
  return $tmpstr;
}

function api_get_fields_label($type,$oid,$gid)
{
  switch($type)
  {
    case 0:
      return api_get_fields_radio($oid,$gid);
      break;
    case 1:
      return api_get_fields_checkbox($oid,$gid);
      break;
    case 2:
      return api_get_fields_select($oid,$gid);
      break;
    case 3:
      return api_get_fields_input($oid,$gid);
      break;
    case 4:
      return api_get_fields_textarea($oid,$gid);
      break;
    case 5:
      return api_get_fields_upload($oid,$gid);
      break;
    case 6:
      return api_get_fields_gallery($oid,$gid);
      break;
    default:
      return api_get_fields_select($oid,$gid);
      break;
  }
}

function api_get_fields_data($fid,$gid)
{
  global $conn;
  $fgenre = 'expansion/fields';
  $fdatabase = mm_cndatabase(ii_cvgenre($fgenre), 'gid');
  $fidfield = mm_cnidfield(ii_cvgenre($fgenre), 'gid');
  $ffpre = mm_cnfpre(ii_cvgenre($fgenre), 'gid');
  $tsqlstr = 'select * from '. $fdatabase.' where '.ii_cfnames($ffpre,"fid").' = '.$fid.' and '.ii_cfnames($ffpre,"gid").' = ' .$gid;
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  return $trs[ii_cfnames($ffpre,"data")];
}

function api_get_fields_topic($fid,$oid)
{
  global $conn;
  $fgenre = 'expansion/fields';
  $fdatabase = mm_cndatabase(ii_cvgenre($fgenre), 'data');
  $fidfield = mm_cnidfield(ii_cvgenre($fgenre), 'data');
  $ffpre = mm_cnfpre(ii_cvgenre($fgenre), 'data');
  $tsqlstr = 'select * from '. $fdatabase.' where '.ii_cfnames($ffpre,"fid").' = '.$fid.' and '.ii_cfnames($ffpre,"oid").' = ' .$oid;
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  return $trs[ii_cfnames($ffpre,"topic")];
}

function api_get_fields_input($oid,$gid)
{
  global $conn;
  $fgenre = 'expansion/fields';
  $fdatabase = mm_cndatabase(ii_cvgenre($fgenre));
  $fidfield = mm_cnidfield(ii_cvgenre($fgenre));
  $ffpre = mm_cnfpre(ii_cvgenre($fgenre));
  $tid = $oid;
  $tmpstr = ii_itake('global.expansion/fields:manage.getinput', 'tpl');
  if (!ii_isnull($tmpstr))
  {
    $tsqlstr = "select * from $fdatabase where $fidfield=$tid";
    $trs = ii_conn_query($tsqlstr, $conn);
    $trs = ii_conn_fetch_array($trs);
    if ($trs)
    {
      $tmpstr = str_replace('{$ctopic}', ii_htmlencode($trs[ii_cfnames($ffpre,'topic')]), $tmpstr);
      $tmpstr = str_replace('{$oid}', $trs[$fidfield], $tmpstr);
    }
    $fdatabase = mm_cndatabase(ii_cvgenre($fgenre), 'gid');
    $fidfield = mm_cnidfield(ii_cvgenre($fgenre), 'gid');
    $ffpre = mm_cnfpre(ii_cvgenre($fgenre), 'gid');
    $tsqlstr = "select * from $fdatabase where " . ii_cfnames($ffpre,'fid') . "=$tid and " . ii_cfnames($ffpre,'gid') . "=$gid";
    $trs = ii_conn_query($tsqlstr, $conn);
    $trs = ii_conn_fetch_array($trs);
    if ($trs) $tmpstr = str_replace('{$data}', ii_htmlencode($trs[ii_cfnames($ffpre,'data')]), $tmpstr);
    else $tmpstr = str_replace('{$data}','', $tmpstr);
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
  }
}

function api_get_fields_textarea($oid,$gid)
{
  global $conn;
  $fgenre = 'expansion/fields';
  $fdatabase = mm_cndatabase(ii_cvgenre($fgenre));
  $fidfield = mm_cnidfield(ii_cvgenre($fgenre));
  $ffpre = mm_cnfpre(ii_cvgenre($fgenre));
  $tid = $oid;
  $tmpstr = ii_itake('global.expansion/fields:manage.gettextarea', 'tpl');
  if (!ii_isnull($tmpstr))
  {
    $tsqlstr = "select * from $fdatabase where $fidfield=$tid";
    $trs = ii_conn_query($tsqlstr, $conn);
    $trs = ii_conn_fetch_array($trs);
    if ($trs)
    {
      $tmpstr = str_replace('{$ctopic}', ii_htmlencode($trs[ii_cfnames($ffpre,'topic')]), $tmpstr);
      $tmpstr = str_replace('{$oid}', $trs[$fidfield], $tmpstr);
    }
    $fdatabase = mm_cndatabase(ii_cvgenre($fgenre), 'gid');
    $fidfield = mm_cnidfield(ii_cvgenre($fgenre), 'gid');
    $ffpre = mm_cnfpre(ii_cvgenre($fgenre), 'gid');
    $tsqlstr = "select * from $fdatabase where " . ii_cfnames($ffpre,'fid') . "=$tid and " . ii_cfnames($ffpre,'gid') . "=$gid";
    $trs = ii_conn_query($tsqlstr, $conn);
    $trs = ii_conn_fetch_array($trs);
    if ($trs) $GLOBALS['RS_data'] = $trs[ii_cfnames($ffpre,'data')];
    else $GLOBALS['RS_data'] = '';
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
  }
}

function api_get_fields_upload($oid,$gid)
{
  global $conn;
  $fgenre = 'expansion/fields';
  $fdatabase = mm_cndatabase(ii_cvgenre($fgenre));
  $fidfield = mm_cnidfield(ii_cvgenre($fgenre));
  $ffpre = mm_cnfpre(ii_cvgenre($fgenre));
  $tid = $oid;
  $tmpstr = ii_itake('global.expansion/fields:manage.getupload', 'tpl');
  if (!ii_isnull($tmpstr))
  {
    $tsqlstr = "select * from $fdatabase where $fidfield=$tid";
    $trs = ii_conn_query($tsqlstr, $conn);
    $trs = ii_conn_fetch_array($trs);
    if ($trs)
    {
      $tmpstr = str_replace('{$ctopic}', ii_htmlencode($trs[ii_cfnames($ffpre,'topic')]), $tmpstr);
      $tmpstr = str_replace('{$oid}', $trs[$fidfield], $tmpstr);
    }
    $fdatabase = mm_cndatabase(ii_cvgenre($fgenre), 'gid');
    $fidfield = mm_cnidfield(ii_cvgenre($fgenre), 'gid');
    $ffpre = mm_cnfpre(ii_cvgenre($fgenre), 'gid');
    $tsqlstr = "select * from $fdatabase where " . ii_cfnames($ffpre,'fid') . "=$tid and " . ii_cfnames($ffpre,'gid') . "=$gid";
    $trs = ii_conn_query($tsqlstr, $conn);
    $trs = ii_conn_fetch_array($trs);
    if ($trs) $tmpstr = str_replace('{$data}', ii_htmlencode($trs[ii_cfnames($ffpre,'data')]), $tmpstr);
    else $tmpstr = str_replace('{$data}','', $tmpstr);
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
  }
}

function api_get_fields_gallery($oid,$gid)
{
  global $conn;
  $fgenre = 'expansion/fields';
  $fdatabase = mm_cndatabase(ii_cvgenre($fgenre));
  $fidfield = mm_cnidfield(ii_cvgenre($fgenre));
  $ffpre = mm_cnfpre(ii_cvgenre($fgenre));
  $tid = $oid;
  $tmpstr = ii_itake('global.expansion/fields:manage.getgallery', 'tpl');
  if (!ii_isnull($tmpstr))
  {
    $tsqlstr = "select * from $fdatabase where $fidfield=$tid";
    $trs = ii_conn_query($tsqlstr, $conn);
    $trs = ii_conn_fetch_array($trs);
    if ($trs)
    {
      $tmpstr = str_replace('{$ctopic}', ii_htmlencode($trs[ii_cfnames($ffpre,'topic')]), $tmpstr);
      $tmpstr = str_replace('{$oid}', $trs[$fidfield], $tmpstr);
    }
    $fdatabase = mm_cndatabase(ii_cvgenre($fgenre), 'gid');
    $fidfield = mm_cnidfield(ii_cvgenre($fgenre), 'gid');
    $ffpre = mm_cnfpre(ii_cvgenre($fgenre), 'gid');
    $tsqlstr = "select * from $fdatabase where " . ii_cfnames($ffpre,'fid') . "=$tid and " . ii_cfnames($ffpre,'gid') . "=$gid";
    $trs = ii_conn_query($tsqlstr, $conn);
    $trs = ii_conn_fetch_array($trs);
    if ($trs) $tmpstr = str_replace('{$data}', ii_htmlencode($trs[ii_cfnames($ffpre,'data')]), $tmpstr);
    else $tmpstr = str_replace('{$data}','', $tmpstr);
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
  }
}

function api_get_fields_radio($oid,$gid)
{
  global $conn;
  $fgenre = 'expansion/fields';
  $fdatabase = mm_cndatabase(ii_cvgenre($fgenre));
  $fidfield = mm_cnidfield(ii_cvgenre($fgenre));
  $ffpre = mm_cnfpre(ii_cvgenre($fgenre));
  $tid = $oid;
  $tmpstr = ii_itake('global.expansion/fields:manage.getradio', 'tpl');
  if (!ii_isnull($tmpstr))
  {
    $tsqlstr = "select * from $fdatabase where $fidfield=$tid";
    $trs = ii_conn_query($tsqlstr, $conn);
    $trs = ii_conn_fetch_array($trs);
    if ($trs)
    {
      $tmpstr = str_replace('{$ctopic}', ii_htmlencode($trs[ii_cfnames($ffpre,'topic')]), $tmpstr);
      $tmpstr = str_replace('{$type}', ii_htmlencode($trs[ii_cfnames($ffpre,'type')]), $tmpstr);
      $tmpstr = str_replace('{$oid}', $trs[$fidfield], $tmpstr);
    }
    $fdatabase = mm_cndatabase(ii_cvgenre($fgenre), 'data');
    $fidfield = mm_cnidfield(ii_cvgenre($fgenre), 'data');
    $ffpre = mm_cnfpre(ii_cvgenre($fgenre), 'data');
    $tsqlstr = "select * from $fdatabase where " . ii_cfnames($ffpre,'fid') . "=$tid order by " . ii_cfnames($ffpre,'oid') . " asc";
    $tmpastr = ii_ctemplate($tmpstr, '{@}');
    $tmprstr = '';
    $trs = ii_conn_query($tsqlstr, $conn);
    while ($trow = ii_conn_fetch_array($trs))
    {
      $tmptstr = $tmpastr;
      foreach ($trow as $key => $val)
      {
        $tkey = ii_get_lrstr($key, '_', 'rightr');
        if ($trow[ii_cfnames($ffpre,'oid')] == api_get_fields_data($oid,$gid)) $tmptstr = str_replace('{$checked}','checked', $tmptstr);
        else $tmptstr = str_replace('{$checked}','', $tmptstr);
        $tmptstr = str_replace('{$' . $tkey . '}', ii_htmlencode($val), $tmptstr);
      }
      $tmptstr = str_replace('{$id}', $trow[ii_cfnames($ffpre,'oid')], $tmptstr);
      $tmprstr .= $tmptstr;
    }
    $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
  }
}

function api_get_fields_checkbox($oid,$gid)
{
  global $conn;
  $fgenre = 'expansion/fields';
  $fdatabase = mm_cndatabase(ii_cvgenre($fgenre));
  $fidfield = mm_cnidfield(ii_cvgenre($fgenre));
  $ffpre = mm_cnfpre(ii_cvgenre($fgenre));
  $tid = $oid;
  $tmpstr = ii_itake('global.expansion/fields:manage.getcheckbox', 'tpl');
  if (!ii_isnull($tmpstr))
  {
    $tsqlstr = "select * from $fdatabase where $fidfield=$tid";
    $trs = ii_conn_query($tsqlstr, $conn);
    $trs = ii_conn_fetch_array($trs);
    if ($trs)
    {
      $tmpstr = str_replace('{$ctopic}', ii_htmlencode($trs[ii_cfnames($ffpre,'topic')]), $tmpstr);
      $tmpstr = str_replace('{$type}', ii_htmlencode($trs[ii_cfnames($ffpre,'type')]), $tmpstr);
      $tmpstr = str_replace('{$oid}', $trs[$fidfield], $tmpstr);
    }
    $fdatabase = mm_cndatabase(ii_cvgenre($fgenre), 'data');
    $fidfield = mm_cnidfield(ii_cvgenre($fgenre), 'data');
    $ffpre = mm_cnfpre(ii_cvgenre($fgenre), 'data');
    $tsqlstr = "select * from $fdatabase where " . ii_cfnames($ffpre,'fid') . "=$tid order by " . ii_cfnames($ffpre,'oid') . " asc";
    $tmpastr = ii_ctemplate($tmpstr, '{@}');
    $tmprstr = '';
    $trs = ii_conn_query($tsqlstr, $conn);
    while ($trow = ii_conn_fetch_array($trs))
    {
      $tmptstr = $tmpastr;
      foreach ($trow as $key => $val)
      {
        $tkey = ii_get_lrstr($key, '_', 'rightr');
        //复选框字符串
        $tdata = api_get_fields_data($oid,$gid);//获取复选框字符串
        $tdata_arr = explode("|", $tdata);
        foreach($tdata_arr as $akey => $aval) {
          if ($aval == $trow[ii_cfnames($ffpre,'oid')]) $tmptstr = str_replace('{$checked}','checked', $tmptstr);
        }
        $tmptstr = str_replace('{$checked}','', $tmptstr);
        //
        $tmptstr = str_replace('{$' . $tkey . '}', ii_htmlencode($val), $tmptstr);
      }
      $tmptstr = str_replace('{$id}', $trow[ii_cfnames($ffpre,'oid')], $tmptstr);
      $tmprstr .= $tmptstr;
    }
    $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
  }
}

function api_get_fields_select($oid,$gid)
{
  global $conn;
  $fgenre = 'expansion/fields';
  $fdatabase = mm_cndatabase(ii_cvgenre($fgenre));
  $fidfield = mm_cnidfield(ii_cvgenre($fgenre));
  $ffpre = mm_cnfpre(ii_cvgenre($fgenre));
  $tid = $oid;
  $tmpstr = ii_itake('global.expansion/fields:manage.getselect', 'tpl');
  if (!ii_isnull($tmpstr))
  {
    $tsqlstr = "select * from $fdatabase where $fidfield=$tid";
    $trs = ii_conn_query($tsqlstr, $conn);
    $trs = ii_conn_fetch_array($trs);
    if ($trs)
    {
      $tmpstr = str_replace('{$ctopic}', ii_htmlencode($trs[ii_cfnames($ffpre,'topic')]), $tmpstr);
      $tmpstr = str_replace('{$type}', ii_htmlencode($trs[ii_cfnames($ffpre,'type')]), $tmpstr);
      $tmpstr = str_replace('{$oid}', $trs[$fidfield], $tmpstr);
    }
    $fdatabase = mm_cndatabase(ii_cvgenre($fgenre), 'data');
    $fidfield = mm_cnidfield(ii_cvgenre($fgenre), 'data');
    $ffpre = mm_cnfpre(ii_cvgenre($fgenre), 'data');
    $tsqlstr = "select * from $fdatabase where " . ii_cfnames($ffpre,'fid') . "=$tid order by " . ii_cfnames($ffpre,'oid') . " asc";
    $tmpastr = ii_ctemplate($tmpstr, '{@}');
    $tmprstr = '';
    $trs = ii_conn_query($tsqlstr, $conn);
    while ($trow = ii_conn_fetch_array($trs))
    {
      $tmptstr = $tmpastr;
      foreach ($trow as $key => $val)
      {
        $tkey = ii_get_lrstr($key, '_', 'rightr');
        if ($trow[ii_cfnames($ffpre,'oid')] == api_get_fields_data($oid,$gid)) $tmptstr = str_replace('{$selected}','selected', $tmptstr);
        else $tmptstr = str_replace('{$selected}','', $tmptstr);
        $tmptstr = str_replace('{$' . $tkey . '}', ii_htmlencode($val), $tmptstr);
      }
      $tmptstr = str_replace('{$id}', $trow[ii_cfnames($ffpre,'oid')], $tmptstr);
      $tmprstr .= $tmptstr;
    }
    $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
  }
}

function api_save_fields($gid)
{
  //循环保存
  global $conn, $ngenre;
  $fgenre = 'expansion/fields';
  $fdatabase = mm_cndatabase(ii_cvgenre($fgenre));
  $fidfield = mm_cnidfield(ii_cvgenre($fgenre));
  $ffpre = mm_cnfpre(ii_cvgenre($fgenre));
  $tmpstr = '';
  $tsqlstr = 'select * from '. $fdatabase.' where '.ii_cfnames($ffpre,"hidden").'=0 and '.ii_cfnames($ffpre,"genre").'="'.$ngenre.'"';
  $trs = ii_conn_query($tsqlstr, $conn);
  while ($trow = ii_conn_fetch_array($trs))
  {
    api_save_fields_sql($trow[$fidfield],$gid,$trow[ii_cfnames($ffpre,"type")]);
  }
}

function api_save_fields_sql($id,$gid,$type='0') {
  //保存操作
  global $conn;
  $fgenre = 'expansion/fields';
  $fdatabase = mm_cndatabase(ii_cvgenre($fgenre), 'gid');
  $fidfield = mm_cnidfield(ii_cvgenre($fgenre), 'gid');
  $ffpre = mm_cnfpre(ii_cvgenre($fgenre), 'gid');
  if ($type == '1') {
    $tdata = '|';
    $tdata_arr = $_POST['fields_'.$id];
    foreach ($tdata_arr as $key => $val)
    {
      $tdata .= $val.'|';
    }
  }elseif ($type == '3') {
    $tdata = ii_left(ii_cstr($_POST['fields_'.$id]), 250);
  }elseif ($type == '4') {
    $tdata = ii_left(ii_cstr($_POST['fields_'.$id]), 100000);
  }elseif ($type == '5') {
    $tdata = ii_left(ii_cstr($_POST['fields_'.$id]), 250);
  }elseif ($type == '6') {
    $tdata = ii_left(ii_cstr($_POST['fields_'.$id]), 100000);
  }else{
    $tdata = ii_get_num($_POST['fields_'.$id]);
  }
  $tsqlstr = "insert into $fdatabase (
    " . ii_cfnames($ffpre,'fid') . ",
    " . ii_cfnames($ffpre,'gid') . ",
    " . ii_cfnames($ffpre,'data') . ",
    " . ii_cfnames($ffpre,'time') . ",
    " . ii_cfnames($ffpre,'update') . "
    ) values (
    '" . ii_get_num($id) . "',
    " . ii_get_num($gid) . ",
    '" . $tdata . "',
    '" . ii_now() . "',
    '" . ii_now() . "'
    )";
  $trs = ii_conn_query($tsqlstr, $conn);
}

function api_fields_add()
{
  //添加商品时调用
  global $conn, $ngenre, $slng;
  $fgenre = 'expansion/fields';
  $fdatabase = mm_cndatabase(ii_cvgenre($fgenre));
  $fidfield = mm_cnidfield(ii_cvgenre($fgenre));
  $ffpre = mm_cnfpre(ii_cvgenre($fgenre));
  $tmpstr = '';
  $tsqlstr = 'select * from '. $fdatabase.' where '.ii_cfnames($ffpre,"hidden").'=0 and '.ii_cfnames($ffpre,"lng").'="'.$slng.'" and '.ii_cfnames($ffpre,"genre").'="'.$ngenre.'"';
  $trs = ii_conn_query($tsqlstr, $conn);
  while ($trow = ii_conn_fetch_array($trs))
  {
    foreach ($trow as $key => $val)
    {
      $tkey = ii_get_lrstr($key, '_', 'rightr');
      if ($tkey == 'type') $type = $val;
    }
    $oid = $trow[$fidfield];
    $tmpstr .= api_fields_label($type,$oid) ;
  }
  return $tmpstr;
}

function api_fields_label($type,$oid)
{
  switch($type)
  {
    case 0:
      return api_fields_radio($oid);
      break;
    case 1:
      return api_fields_checkbox($oid);
      break;
    case 2:
      return api_fields_select($oid);
      break;
    case 3:
      return api_fields_input($oid);
      break;
    case 4:
      return api_fields_textarea($oid);
      break;
    case 5:
      return api_fields_upload($oid);
      break;
    case 6:
      return api_fields_gallery($oid);
      break;
    default:
      return api_fields_select($oid);
      break;
  }

}

function api_fields_input($oid)
{
  global $conn;
  $fgenre = 'expansion/fields';
  $fdatabase = mm_cndatabase(ii_cvgenre($fgenre));
  $fidfield = mm_cnidfield(ii_cvgenre($fgenre));
  $ffpre = mm_cnfpre(ii_cvgenre($fgenre));
  $tid = $oid;
  $tmpstr = ii_itake('global.expansion/fields:manage.input', 'tpl');
  if (!ii_isnull($tmpstr))
  {
    $tsqlstr = "select * from $fdatabase where $fidfield=$tid";
    $trs = ii_conn_query($tsqlstr, $conn);
    $trs = ii_conn_fetch_array($trs);
    if ($trs)
    {
      $tmpstr = str_replace('{$ctopic}', ii_htmlencode($trs[ii_cfnames($ffpre,'topic')]), $tmpstr);
      $tmpstr = str_replace('{$oid}', $trs[$fidfield], $tmpstr);
    }
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
  }
}

function api_fields_textarea($oid)
{
  global $conn;
  $fgenre = 'expansion/fields';
  $fdatabase = mm_cndatabase(ii_cvgenre($fgenre));
  $fidfield = mm_cnidfield(ii_cvgenre($fgenre));
  $ffpre = mm_cnfpre(ii_cvgenre($fgenre));
  $tid = $oid;
  $tmpstr = ii_itake('global.expansion/fields:manage.textarea', 'tpl');
  if (!ii_isnull($tmpstr))
  {
    $tsqlstr = "select * from $fdatabase where $fidfield=$tid";
    $trs = ii_conn_query($tsqlstr, $conn);
    $trs = ii_conn_fetch_array($trs);
    if ($trs)
    {
      $tmpstr = str_replace('{$ctopic}', ii_htmlencode($trs[ii_cfnames($ffpre,'topic')]), $tmpstr);
      $tmpstr = str_replace('{$oid}', $trs[$fidfield], $tmpstr);
    }
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
  }
}

function api_fields_upload($oid)
{
  global $conn;
  $fgenre = 'expansion/fields';
  $fdatabase = mm_cndatabase(ii_cvgenre($fgenre));
  $fidfield = mm_cnidfield(ii_cvgenre($fgenre));
  $ffpre = mm_cnfpre(ii_cvgenre($fgenre));
  $tid = $oid;
  $tmpstr = ii_itake('global.expansion/fields:manage.upload', 'tpl');
  if (!ii_isnull($tmpstr))
  {
    $tsqlstr = "select * from $fdatabase where $fidfield=$tid";
    $trs = ii_conn_query($tsqlstr, $conn);
    $trs = ii_conn_fetch_array($trs);
    if ($trs)
    {
      $tmpstr = str_replace('{$ctopic}', ii_htmlencode($trs[ii_cfnames($ffpre,'topic')]), $tmpstr);
      $tmpstr = str_replace('{$oid}', $trs[$fidfield], $tmpstr);
    }
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
  }
}

function api_fields_gallery($oid)
{
  global $conn;
  $fgenre = 'expansion/fields';
  $fdatabase = mm_cndatabase(ii_cvgenre($fgenre));
  $fidfield = mm_cnidfield(ii_cvgenre($fgenre));
  $ffpre = mm_cnfpre(ii_cvgenre($fgenre));
  $tid = $oid;
  $tmpstr = ii_itake('global.expansion/fields:manage.gallery', 'tpl');
  if (!ii_isnull($tmpstr))
  {
    $tsqlstr = "select * from $fdatabase where $fidfield=$tid";
    $trs = ii_conn_query($tsqlstr, $conn);
    $trs = ii_conn_fetch_array($trs);
    if ($trs)
    {
      $tmpstr = str_replace('{$ctopic}', ii_htmlencode($trs[ii_cfnames($ffpre,'topic')]), $tmpstr);
      $tmpstr = str_replace('{$oid}', $trs[$fidfield], $tmpstr);
    }
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
  }
}

function api_fields_radio($oid)
{
  global $conn;
  $fgenre = 'expansion/fields';
  $fdatabase = mm_cndatabase(ii_cvgenre($fgenre));
  $fidfield = mm_cnidfield(ii_cvgenre($fgenre));
  $ffpre = mm_cnfpre(ii_cvgenre($fgenre));
  $tid = $oid;
  $tmpstr = ii_itake('global.expansion/fields:manage.radio', 'tpl');
  if (!ii_isnull($tmpstr))
  {
    $tsqlstr = "select * from $fdatabase where $fidfield=$tid";
    $trs = ii_conn_query($tsqlstr, $conn);
    $trs = ii_conn_fetch_array($trs);
    if ($trs)
    {
      $tmpstr = str_replace('{$ctopic}', ii_htmlencode($trs[ii_cfnames($ffpre,'topic')]), $tmpstr);
      $tmpstr = str_replace('{$type}', ii_htmlencode($trs[ii_cfnames($ffpre,'type')]), $tmpstr);
      $tmpstr = str_replace('{$oid}', $trs[$fidfield], $tmpstr);
    }
    $fdatabase = mm_cndatabase(ii_cvgenre($fgenre), 'data');
    $fidfield = mm_cnidfield(ii_cvgenre($fgenre), 'data');
    $ffpre = mm_cnfpre(ii_cvgenre($fgenre), 'data');
    $tsqlstr = "select * from $fdatabase where " . ii_cfnames($ffpre,'fid') . "=$tid order by " . ii_cfnames($ffpre,'oid') . " asc";
    $tmpastr = ii_ctemplate($tmpstr, '{@}');
    $tmprstr = '';
    $trs = ii_conn_query($tsqlstr, $conn);
    while ($trow = ii_conn_fetch_array($trs))
    {
      $tmptstr = $tmpastr;
      foreach ($trow as $key => $val)
      {
        $tkey = ii_get_lrstr($key, '_', 'rightr');
        $GLOBALS['RS_' . $tkey] = $val;
        $tmptstr = str_replace('{$' . $tkey . '}', ii_htmlencode($val), $tmptstr);
      }
      $tmptstr = str_replace('{$id}', $trow[ii_cfnames($ffpre,'oid')], $tmptstr);
      $tmprstr .= $tmptstr;
    }
    $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
  }
}

function api_fields_checkbox($oid)
{
  global $conn;
  $fgenre = 'expansion/fields';
  $fdatabase = mm_cndatabase(ii_cvgenre($fgenre));
  $fidfield = mm_cnidfield(ii_cvgenre($fgenre));
  $ffpre = mm_cnfpre(ii_cvgenre($fgenre));
  $tid = $oid;
  $tmpstr = ii_itake('global.expansion/fields:manage.checkbox', 'tpl');
  if (!ii_isnull($tmpstr))
  {
    $tsqlstr = "select * from $fdatabase where $fidfield=$tid";
    $trs = ii_conn_query($tsqlstr, $conn);
    $trs = ii_conn_fetch_array($trs);
    if ($trs)
    {
      $tmpstr = str_replace('{$ctopic}', ii_htmlencode($trs[ii_cfnames($ffpre,'topic')]), $tmpstr);
      $tmpstr = str_replace('{$type}', ii_htmlencode($trs[ii_cfnames($ffpre,'type')]), $tmpstr);
      $tmpstr = str_replace('{$oid}', $trs[$fidfield], $tmpstr);
    }
    $fdatabase = mm_cndatabase(ii_cvgenre($fgenre), 'data');
    $fidfield = mm_cnidfield(ii_cvgenre($fgenre), 'data');
    $ffpre = mm_cnfpre(ii_cvgenre($fgenre), 'data');
    $tsqlstr = "select * from $fdatabase where " . ii_cfnames($ffpre,'fid') . "=$tid order by " . ii_cfnames($ffpre,'oid') . " asc";
    $tmpastr = ii_ctemplate($tmpstr, '{@}');
    $tmprstr = '';
    $trs = ii_conn_query($tsqlstr, $conn);
    while ($trow = ii_conn_fetch_array($trs))
    {
      $tmptstr = $tmpastr;
      foreach ($trow as $key => $val)
      {
        $tkey = ii_get_lrstr($key, '_', 'rightr');
        $GLOBALS['RS_' . $tkey] = $val;
        $tmptstr = str_replace('{$' . $tkey . '}', ii_htmlencode($val), $tmptstr);
      }
      $tmptstr = str_replace('{$id}', $trow[ii_cfnames($ffpre,'oid')], $tmptstr);
      $tmprstr .= $tmptstr;
    }
    $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
  }
}

function api_fields_select($oid)
{
  global $conn;
  $fgenre = 'expansion/fields';
  $fdatabase = mm_cndatabase(ii_cvgenre($fgenre));
  $fidfield = mm_cnidfield(ii_cvgenre($fgenre));
  $ffpre = mm_cnfpre(ii_cvgenre($fgenre));
  $tid = $oid;
  $tmpstr = ii_itake('global.expansion/fields:manage.select', 'tpl');
  if (!ii_isnull($tmpstr))
  {
    $tsqlstr = "select * from $fdatabase where $fidfield=$tid";
    $trs = ii_conn_query($tsqlstr, $conn);
    $trs = ii_conn_fetch_array($trs);
    if ($trs)
    {
      $tmpstr = str_replace('{$ctopic}', ii_htmlencode($trs[ii_cfnames($ffpre,'topic')]), $tmpstr);
      $tmpstr = str_replace('{$type}', ii_htmlencode($trs[ii_cfnames($ffpre,'type')]), $tmpstr);
      $tmpstr = str_replace('{$oid}', $trs[$fidfield], $tmpstr);
    }
    $fdatabase = mm_cndatabase(ii_cvgenre($fgenre), 'data');
    $fidfield = mm_cnidfield(ii_cvgenre($fgenre), 'data');
    $ffpre = mm_cnfpre(ii_cvgenre($fgenre), 'data');
    $tsqlstr = "select * from $fdatabase where " . ii_cfnames($ffpre,'fid') . "=$tid order by " . ii_cfnames($ffpre,'oid') . " asc";
    $tmpastr = ii_ctemplate($tmpstr, '{@}');
    $tmprstr = '';
    $trs = ii_conn_query($tsqlstr, $conn);
    while ($trow = ii_conn_fetch_array($trs))
    {
      $tmptstr = $tmpastr;
      foreach ($trow as $key => $val)
      {
        $tkey = ii_get_lrstr($key, '_', 'rightr');
        $GLOBALS['RS_' . $tkey] = $val;
        $tmptstr = str_replace('{$' . $tkey . '}', ii_htmlencode($val), $tmptstr);
      }
      $tmptstr = str_replace('{$id}', $trow[ii_cfnames($ffpre,'oid')], $tmptstr);
      $tmprstr .= $tmptstr;
    }
    $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
  }
}

//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
?>
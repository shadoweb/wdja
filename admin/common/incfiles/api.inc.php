<?php
function api_pop_add_lists($source)
{
  $tmpstr = ii_itake('global.tpl_pops.pop_list_add', 'tpl');
  $tmpstr = str_replace('{$source}', $source, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function api_pop_edit_lists($source,$gid)
{
  global $ngenre,$conn;
  $ttitle = api_get_related_title($ngenre,$gid,$source);
  $tsid = api_get_related_sid($ngenre,$gid,$source);
  $tmpstr = ii_itake('global.tpl_pops.pop_list_edit', 'tpl');
  $tmpstr = str_replace('{$genre}', $ngenre, $tmpstr);
  $tmpstr = str_replace('{$source}', $source, $tmpstr);
  $tmpstr = str_replace('{$'.$source.'_title}', $ttitle, $tmpstr);
  $tmpstr = str_replace('{$'.$source.'_sid}', $tsid, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function api_pop_add_input($source)
{
  $tmpstr = ii_itake('global.tpl_pops.pop_input_add', 'tpl');
  $tmpstr = str_replace('{$source}', $source, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function api_pop_edit_input($source,$gid)
{
  global $ngenre,$conn;
  $ttitle = api_get_related_title($ngenre,$gid,$source);
  $tsid = api_get_related_sid($ngenre,$gid,$source);
  $tsid = ii_get_lrstr($tsid, ',', 'left');
  $tmpstr = ii_itake('global.tpl_pops.pop_input_edit', 'tpl');
  $tmpstr = str_replace('{$genre}', $ngenre, $tmpstr);
  $tmpstr = str_replace('{$source}', $source, $tmpstr);
  $tmpstr = str_replace('{$'.$source.'_title}', $ttitle, $tmpstr);
  $tmpstr = str_replace('{$'.$source.'_sid}', $tsid, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function api_get_pop_topic($source,$gid)
{
  global $variable,$conn;
  if(strpos($gid,',') !==false) $gid = ii_get_lrstr($gid, ',', 'left');
  $ndatabase = $variable[ii_cvgenre($source) . '.ndatabase'];
  $nidfield = $variable[ii_cvgenre($source) . '.nidfield'];
  $nfpre = $variable[ii_cvgenre($source) . '.nfpre'];
  if(!ii_isnull($ndatabase)){
      $tsqlstr = "select * from $ndatabase where $nidfield = $gid";
      $trs = ii_conn_query($tsqlstr, $conn);
      $trs = ii_conn_fetch_array($trs);
      if ($trs) return ii_htmlencode($trs[ii_cfnames($nfpre,'topic')]);
  }
}

function api_get_related_title($genre,$gid,$source)
{
  global $conn;
  global $related_database, $related_idfield, $related_fpre;
  $tgid = ii_get_num($gid,0);
  $ttitle = '';
  $fdatabase = $related_database;
  $fidfield = $related_idfield;
  $ffpre = $related_fpre;
  if($tgid != 0){
      $tsqlstr = 'select '.ii_cfnames($ffpre,"title").' from '. $fdatabase.' where '.ii_cfnames($ffpre,"genre").' = "' .$genre.'" and '.ii_cfnames($ffpre,"gid").' = "' .$tgid. '" and '.ii_cfnames($ffpre,"source").' = "' .$source. '"';
      $trs = ii_conn_query($tsqlstr, $conn);
      $trs = ii_conn_fetch_array($trs);
      if ($trs) $ttitle = $trs[ii_cfnames($ffpre,"title")];
  }
  return $ttitle;
}

function api_get_related_sid($genre,$gid,$source)
{
  global $conn;
  global $related_database, $related_idfield, $related_fpre;
  $tgid = ii_get_num($gid,0);
  $tsid = 0;
  $fdatabase = $related_database;
  $fidfield = $related_idfield;
  $ffpre = $related_fpre;
  if($tgid != 0){
      $tsqlstr = 'select '.ii_cfnames($ffpre,"sid").' from '. $fdatabase.' where '.ii_cfnames($ffpre,"genre").' = "' .$genre.'" and '.ii_cfnames($ffpre,"gid").' = "' .$tgid. '" and '.ii_cfnames($ffpre,"source").' = "' .$source. '"';
      $trs = ii_conn_query($tsqlstr, $conn);
      $trs = ii_conn_fetch_array($trs);
      if ($trs) $tsid = $trs[ii_cfnames($ffpre,"sid")];
      if (ii_isnull($tsid)) $tsid = 0;
   }
   return $tsid;
}

function mm_get_pop_lists($source,$sid)
{
  global $variable,$conn;
  $ndatabase = $variable[ii_cvgenre($source) . '.ndatabase'];
  $nidfield = $variable[ii_cvgenre($source) . '.nidfield'];
  $nfpre = $variable[ii_cvgenre($source) . '.nfpre'];
  if(!ii_isnull($sid) && !ii_isnull($ndatabase)){
      $tsqlstr = "select * from $ndatabase where $nidfield in ($sid)";
      $trs = ii_conn_query($tsqlstr, $conn);
      $tmpstr = ii_itake('global.tpl_pops.li', 'tpl');
      $tmpastr = ii_ctemplate($tmpstr, '{@}');
      while ($trow = ii_conn_fetch_array($trs))
      {
         $tmptstr = $tmpastr;
         foreach ($trow as $key => $val)
         {
           $tkey = ii_get_lrstr($key, '_', 'rightr');
           $tval = $val;
           $GLOBALS['RST_' . $tkey] = $tval;
           $tmptstr = str_replace('{$' . $tkey . '}', $tval, $tmptstr);
          }
          $tmptstr = str_replace('{$id}', $trow[$nidfield], $tmptstr);
          $tmptstr = str_replace('{$source}', $source, $tmptstr);
          $tmptstr = ii_creplace($tmptstr);
          $tmprstr .= $tmptstr;
       }
       $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
       $tmpstr = str_replace('{$source}', $source, $tmpstr);
       $tmpstr = ii_creplace($tmpstr);
  }
       return $tmpstr;
}

function mm_view_pop_lists($source,$gid)
{
  global $variable,$nurltype,$conn,$ngenre;
  $ttitle = api_get_related_title($ngenre,$gid,$source);
  if(ii_isnull($ttitle)) $ttitle = ii_itake('global.'.$source.':module.channel_title', 'lng');
  $tsid = api_get_related_sid($ngenre,$gid,$source);
  $ndatabase = $variable[ii_cvgenre($source) . '.ndatabase'];
  $nidfield = $variable[ii_cvgenre($source) . '.nidfield'];
  $nfpre = $variable[ii_cvgenre($source) . '.nfpre'];
  if(!ii_isnull($tsid) && !ii_isnull($ndatabase))
  {
    $tsqlstr = "select * from $ndatabase where $nidfield in ($tsid)";
    $trs = ii_conn_query($tsqlstr, $conn);
    $tmpstr = ii_itake('global.tpl_pops.view_lists', 'tpl');
    $tmpastr = ii_ctemplate($tmpstr, '{@}');
    $trows = ii_conn_fetch_all($trs);
    if(is_array($trows) && count($trows)>0)
    {
      foreach ($trows as $trow)
      {
        $tmptstr = $tmpastr;
        foreach ($trow as $key => $val)
        {
          $tkey = ii_get_lrstr($key, '_', 'rightr');
          $tval = $val;
          $GLOBALS['RST_' . $tkey] = $tval;
          $tmptstr = str_replace('{$' . $tkey . '}', $tval, $tmptstr);
        }
        $turl = ii_iurl('detail', $trow[$nidfield], $nurltype);
        $tmptstr = str_replace('{$id}', $trow[$nidfield], $tmptstr);
        $tmptstr = str_replace('{$url}', $turl, $tmptstr);
        $tmptstr = str_replace('{$source}', $source, $tmptstr);
        $tmptstr = ii_creplace($tmptstr);
        $tmprstr .= $tmptstr;
      }
      $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
      $tmpstr = str_replace('{$title}', $ttitle, $tmpstr);
      $tmpstr = str_replace('{$source}', $source, $tmpstr);
      $tmpstr = ii_creplace($tmpstr);
    }
    else $tmpstr = '';
    return $tmpstr;
  }
}

function mm_view_pop_input($source,$gid,$type='0')
{
  global $variable,$nurltype,$conn,$ngenre;
  $ttitle = api_get_related_title($ngenre,$gid,$source);
  if(ii_isnull($ttitle)) $ttitle = ii_itake('global.'.$source.':module.channel_title', 'lng');
  $tsid = api_get_related_sid($ngenre,$gid,$source);
  $ndatabase = $variable[ii_cvgenre($source) . '.ndatabase'];
  $nidfield = $variable[ii_cvgenre($source) . '.nidfield'];
  $nfpre = $variable[ii_cvgenre($source) . '.nfpre'];
  if(!ii_isnull($tsid) && !ii_isnull($ndatabase))
  {
    $tsqlstr = "select * from $ndatabase where $nidfield in ($tsid)";
    $trs = ii_conn_query($tsqlstr, $conn);
    $tmpstr = ii_itake('global.tpl_pops.view_inputs', 'tpl');
    if($type=='1') $tmpstr = ii_itake('global.tpl_pops.view_input', 'tpl');
    $tmpastr = ii_ctemplate($tmpstr, '{@}');
    $trows = ii_conn_fetch_all($trs);
    if(is_array($trows) && count($trows)>0)
    {
      foreach ($trows as $trow)
      {
        $tmptstr = $tmpastr;
        foreach ($trow as $key => $val)
        {
          $tkey = ii_get_lrstr($key, '_', 'rightr');
          $tval = $val;
          $GLOBALS['RST_' . $tkey] = $tval;
          $tmptstr = str_replace('{$' . $tkey . '}', $tval, $tmptstr);
        }
        $turl = ii_iurl('detail', $trow[$nidfield], $nurltype);
        $tmptstr = str_replace('{$id}', $trow[$nidfield], $tmptstr);
        $tmptstr = str_replace('{$url}', $turl, $tmptstr);
        $tmptstr = str_replace('{$source}', $source, $tmptstr);
        $tmptstr = ii_creplace($tmptstr);
        $tmprstr .= $tmptstr;
      }
      $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
      $tmpstr = str_replace('{$title}', $ttitle, $tmpstr);
      $tmpstr = str_replace('{$source}', $source, $tmpstr);
      $tmpstr = ii_creplace($tmpstr);
    }
    else $tmpstr = '';
    return $tmpstr;
  }
}

function mm_view_related_genrelist($genre,$source,$sid)
{
  global $variable,$nurltype,$conn;
  global $related_database, $related_idfield, $related_fpre;
  $ttitle = mm_get_genre_title($genre);
  $tsqlstr = "select * from $related_database where ".ii_cfnames($related_fpre,'sid')." = $sid and ".ii_cfnames($related_fpre,'genre')." = '$genre'";
  $trs = ii_conn_query($tsqlstr, $conn);
  $tmpstr = ii_itake('global.tpl_pops.view_related_genrelist', 'tpl');
  $tmpastr = ii_ctemplate($tmpstr, '{@}');
  while ($trow = ii_conn_fetch_array($trs))
  {
    $tmptstr = $tmpastr;
    $tid = $trow[ii_cfnames($related_fpre,'gid')];
    $turl = ii_iurl('detail', $tid, $nurltype);
    $topic = mm_get_field($genre,$tid,'topic');
    $tmptstr = str_replace('{$id}', $tid, $tmptstr);
    $tmptstr = str_replace('{$url}', $turl, $tmptstr);
    $tmptstr = str_replace('{$topic}', $topic, $tmptstr);
    $tmptstr = str_replace('{$genre}', $genre, $tmptstr);
    $tmptstr = ii_creplace($tmptstr);
    $tmprstr .= $tmptstr;
  }
  $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
  $tmpstr = str_replace('{$title}', $ttitle, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function mm_get_pop_iframe($source,$ctype='list')
{
  $tmpstr = ii_itake('global.tpl_pops.pop_list_iframe', 'tpl');
  $tmpstr = str_replace('{$ctype}', $ctype, $tmpstr);
  $tmpstr = str_replace('{$source}', $source, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function mm_get_pop_upload()
{
  $tmpstr = ii_ireplace('global.tpl_pops.pop_upload', 'tpl');
  return $tmpstr;
}

function wdja_cms_pop_list($source,$ctype='list')
{
  global $conn, $slng;
  global $variable,$nurltype,$ncreatefiletype;
  $nsource = $source;
  $nclstype = $variable[ii_cvgenre($nsource) . '.nclstype'];
  $npagesize = $variable[ii_cvgenre($nsource) . '.npagesize'];
  $nlisttopx = $variable[ii_cvgenre($nsource) . '.nlisttopx'];
  $ndatabase = $variable[ii_cvgenre($nsource) . '.ndatabase'];
  $nidfield = $variable[ii_cvgenre($nsource) . '.nidfield'];
  $nfpre = $variable[ii_cvgenre($nsource) . '.nfpre'];
  $toffset = ii_get_num($_GET['offset']);
  $search_keyword = ii_get_safecode($_GET['keyword']);
  if($ctype=='list') $tmpstr = ii_itake('global.tpl_pops.pop_list', 'tpl');
  else $tmpstr = ii_itake('global.tpl_pops.pop_input', 'tpl');
  $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_ida}');
  $tmprstr = '';
  if(!ii_isnull($ndatabase)){
    $tsqlstr = "select * from $ndatabase where " . ii_cfnames($nfpre,'hidden') . "=0";
    if (!ii_isnull($search_keyword)) $tsqlstr .= " and " . ii_cfnames($nfpre,'topic') . " like '%" . $search_keyword . "%'";
    $tsqlstr .= " order by " . ii_cfnames($nfpre,'time') . " desc";
    $tcp = new cc_cutepage;
    $tcp -> id = $nidfield;
    $tcp -> sqlstr = $tsqlstr;
    $tcp -> offset = $toffset;
    $tcp -> pagesize = $npagesize;
    $tcp -> rslimit = $nlisttopx;
    $tcp -> init();
    $trsary = $tcp -> get_rs_array();
    $font_disabled = ii_itake('global.tpl_config.font_disabled', 'tpl');
    $postfix_good = ii_ireplace('global.tpl_config.postfix_good', 'tpl');
    if (!(ii_isnull($search_keyword))) $font_red = ii_itake('global.tpl_config.font_red', 'tpl');
    if (is_array($trsary))
    {
      foreach($trsary as $trs)
      {
        $ttopic = ii_htmlencode($trs[ii_cfnames($nfpre,'topic')]);
        $title= ii_htmlencode($trs[ii_cfnames($nfpre,'topic')]);
        if (isset($font_red))
        {
          $font_red = str_replace('{$explain}', $search_keyword, $font_red);
          $ttopic = str_replace($search_keyword, $font_red, $ttopic);
        }
        $turl = '/'.$nsource.'/'.ii_iurl('detail',$trs[$nidfield], $nurltype);
        $tmptstr = str_replace('{$topic}', $ttopic, $tmpastr);
        $tmptstr = str_replace('{$title}', $title, $tmptstr);
        $tmptstr = str_replace('{$topicstr}', ii_encode_scripts(ii_htmlencode($trs[ii_cfnames($nfpre,'topic')])), $tmptstr);
        $tmptstr = str_replace('{$url}', $turl, $tmptstr);
        $tmptstr = str_replace('{$source}', $nsource, $tmptstr);
        $tmptstr = str_replace('{$time}', ii_get_date($trs[ii_cfnames($nfpre,'time')]), $tmptstr);
        $tmptstr = str_replace('{$id}', ii_get_num($trs[$nidfield]), $tmptstr);
        $tmprstr .= $tmptstr;
      }
    }
    $tmpstr = str_replace('{$cpagestr}', $tcp -> get_pagenum(), $tmpstr);
    $tmpstr  = str_replace('{$source}', $nsource, $tmpstr);
  }
  $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function api_save_related($genre,$gid,$source,$title,$sid){
  global $conn;
  global $variable;
  global $related_database, $related_idfield, $related_fpre;
  $tgenre = $genre;
  $tgid = ii_get_num($gid,0);
  $tsource = $source;
  $ttitle = $title;
  $tsid = $sid;
  $fdatabase = $related_database;
  $fidfield = $related_idfield;
  $ffpre = $related_fpre;
  if($gid !=0){
    if(api_search_related($tgenre,$tgid,$tsource)) api_update_related($tgenre,$tgid,$tsource,$ttitle,$tsid);
    else api_insert_related($tgenre,$tgid,$tsource,$ttitle,$tsid);
  }
}

function api_update_related($genre,$gid,$source,$title,$sid){
  global $conn;
  global $variable;
  global $related_database, $related_idfield, $related_fpre;
  $tgenre = $genre;
  $tgid = ii_get_num($gid,0);
  $tsource = $source;
  $ttitle = $title;
  $tsid = $sid;
  $fdatabase = $related_database;
  $fidfield = $related_idfield;
  $ffpre = $related_fpre;
  if($gid !=0){
      if(api_search_related($tgenre,$tgid,$tsource)){
        $tsqlstr = "update $fdatabase set
        " . ii_cfnames($ffpre,'title') . "='" . $ttitle . "',
        " . ii_cfnames($ffpre,'sid') . "='" . $tsid . "',
        " . ii_cfnames($ffpre,'update') . "='" . ii_now() . "'
        where " . ii_cfnames($ffpre,'genre') . "='".$tgenre."' and " . ii_cfnames($ffpre,'gid') . "= '".$tgid."' and " . ii_cfnames($ffpre,'source') . "='" . $tsource . "'";
        ii_conn_query($tsqlstr, $conn);
      }
      else api_insert_related($tgenre,$tgid,$tsource,$ttitle,$tsid);
  }
}

function api_insert_related($genre,$gid,$source,$title,$sid){
  global $conn,$slng;
  global $variable;
  global $related_database, $related_idfield, $related_fpre;
  $tgenre = $genre;
  $ttitle = $title;
  $tgid = ii_get_num($gid,0);
  $tsource = $source;
  $tsid = $sid;
  $fdatabase = $related_database;
  $fidfield = $related_idfield;
  $ffpre = $related_fpre;
  if($gid !=0){
    $tsqlstr = "insert into $fdatabase (
    " . ii_cfnames($ffpre,'genre') . ",
    " . ii_cfnames($ffpre,'gid') . ",
    " . ii_cfnames($ffpre,'source') . ",
    " . ii_cfnames($ffpre,'title') . ",
    " . ii_cfnames($ffpre,'sid') . ",
    " . ii_cfnames($ffpre,'time') . ",
    " . ii_cfnames($ffpre,'update') . ",
    " . ii_cfnames($ffpre,'lng') . "
    ) values (
    '" . $tgenre . "',
    '" . $tgid . "',
    '" . $tsource . "',
    '" . $ttitle . "',
    '" . $tsid . "',
    '" . ii_now() . "',
    '" . ii_now() . "',
    '$slng'
    )";
    ii_conn_query($tsqlstr, $conn);
  }
}

function api_search_related($genre,$gid,$source){
  global $conn,$slng;
  global $variable;
  global $related_database, $related_idfield, $related_fpre;
  $tgenre = $genre;
  $tgid = ii_get_num($gid,0);
  $tsource = $source;
  $fdatabase = $related_database;
  $fidfield = $related_idfield;
  $ffpre = $related_fpre;
  $res = false;
  if($gid !=0){
      $tsqlstr = 'select '.$fidfield.' from '. $fdatabase.' where '.ii_cfnames($ffpre,"genre").' = "' .$tgenre.'" and '.ii_cfnames($ffpre,"gid").' = "' .$tgid. '" and '.ii_cfnames($ffpre,"source").' = "' .$tsource. '"';
      $trs = ii_conn_query($tsqlstr, $conn);
      $trs = ii_conn_fetch_array($trs);
      if ($trs) $res = true;
  }
  return $res;
}

function mm_sel_genre_topic($genre,$vars)
{
  //获取指定模块内容标题,用下拉框显示,供关联模块内容时使用
  $ngenre = $genre;
  $tary = mm_get_genre_array($ngenre,$vars);
  $tid = ii_get_strvalue($vars, 'id');
  if (is_array($tary))
  {
    $trestr = ii_itake('global.tpl_config.sys_spsort', 'tpl');
    $option_pre = '';//'<option value="0" selected>'.ii_itake('global.lng_config.unselect', 'lng').'</option>';
    $option_unselected = ii_itake('global.tpl_config.option_unselect', 'tpl');
    $option_selected = ii_itake('global.tpl_config.option_select', 'tpl');
    $tmpstr = '';
    $treturnstr = '';
    foreach ($tary as $key => $val)
    {
      $tgourl = mm_get_field($ngenre,$val['id'],'gourl');
      if (!ii_isnull($tgourl)) continue;
      if (ii_cinstr($tid,$key,',')) $tmpstr = $option_selected;
      else $tmpstr = $option_unselected;
      $tmpstr = str_replace('{$explain}', $val['topic'], $tmpstr);
      $tmpstr = str_replace('{$value}', $val['id'], $tmpstr);
      $treturnstr .= $tmpstr;
    }
    return $option_pre.$treturnstr;
  }else{
    return $option_pre;
  }
}

function mm_get_genre_array($genre,$vars)
{
  global $conn, $variable, $nurlpre, $nlng;
  $ngenre = $genre;
  $tfid = ii_get_strvalue($vars, 'fid');
  $tffield = ii_get_strvalue($vars, 'ffield');
  $ndatabase = $variable[ii_cvgenre($ngenre) . '.ndatabase'];
  $nidfield = $variable[ii_cvgenre($ngenre) . '.nidfield'];
  $nfpre = $variable[ii_cvgenre($ngenre) . '.nfpre'];
  $tarys = Array();
  $tsqlstr = "select * from $ndatabase where " . ii_cfnames($nfpre, 'lng') . "='$nlng' and " . ii_cfnames($nfpre, 'hidden') . "=0";
  if (!ii_isnull($tfid) && !ii_isnull($tffield)) $tsqlstr .= " and " . ii_cfnames($nfpre, $tffield) . "='$tfid'";
  $tsqlstr .= " order by " . $nidfield . " asc";
  $trs = ii_conn_query($tsqlstr, $conn);
  while ($trow = ii_conn_fetch_array($trs))
  {
    $tary[$trow[$nidfield]]['id'] = $trow[$nidfield];
    $tary[$trow[$nidfield]]['topic'] = $trow[ii_cfnames($nfpre, 'topic')];
    $tary[$trow[$nidfield]]['keywords'] = $trow[ii_cfnames($nfpre, 'keywords')];
    $tary[$trow[$nidfield]]['description'] = $trow[ii_cfnames($nfpre, 'description')];
    $tary[$trow[$nidfield]]['image'] = $trow[ii_cfnames($nfpre, 'image')];
    $tary[$trow[$nidfield]]['content'] = $trow[ii_cfnames($nfpre, 'content')];
    $tary[$trow[$nidfield]]['gourl'] = $trow[ii_cfnames($nfpre, 'gourl')];
    $tarys += $tary;
  }
  return $tarys;
}

function mm_get_genre_title($genre)
{
  if (!ii_isnull($genre))
  {
    $tmpstr = @ii_itake('global.' . $genre . ':module.channel_title', 'lng');
    if (ii_isnull($tmpstr)) $tmpstr = @ii_itake('global.' . $genre . ':module.channel_title', 'lng');
    if (ii_isnull($tmpstr)) $tmpstr = '?';
    return $tmpstr;
  }
}

function mm_sel_genre($genres, $genre)
{
  if (!ii_isnull($genres))
  {
    $option_unselected = ii_itake('global.tpl_config.option_unselect', 'tpl');
    $option_selected = ii_itake('global.tpl_config.option_select', 'tpl');
    $tmodules = ii_get_valid_module('string');
    $tmpstr = '';
    $treturnstr = '';
    $tary = explode(',', $genres);
    foreach ($tary as $key => $val)
    {
      if (ii_cinstr($tmodules, $val, '|'))
      {
        if ($val == $genre) $tmpstr = $option_selected;
        else $tmpstr = $option_unselected;
        $tmpstr = str_replace('{$explain}', ii_itake('global.' . $val . ':module.channel_title', 'lng'), $tmpstr);
        $tmpstr = str_replace('{$value}', $val, $tmpstr);
        $treturnstr .= $tmpstr;
      }
    }
    return $treturnstr;
  }
}

//生成商品编号
function mm_get_shopnum() {
  date_default_timezone_set('PRC');
  return date('ymd').substr(time(),-4).substr(microtime(),2,5).mt_rand(10,99);
}
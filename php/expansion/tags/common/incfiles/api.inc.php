<?php
function api_tags_sum($tid)
{
//统计每个标签被调用次数
//计算标签数据表中对应的文章调用标签ID中是否存在当前标签ID.存在则+1
	global $conn;
	$sum = 0;
	$tgenre = 'expansion/tags';
	$tdatabase = mm_cndatabase(ii_cvgenre($tgenre), 'data');
	$tidfield = mm_cnidfield(ii_cvgenre($tgenre), 'data');
	$tfpre = mm_cnfpre(ii_cvgenre($tgenre), 'data');
	$tsqlstr = 'select * from '. $tdatabase;
	$trs = ii_conn_query($tsqlstr, $conn);
   while ($trow = ii_conn_fetch_array($trs))
    {
        $tids = $trow[ii_cfnames($tfpre,"tid")];
		$tid_array = explode(",",$tids);
		foreach ($tid_array as $key => $val)
		{
			if($tid == $val) $sum = $sum +1;
		}
    }
	return $sum;
}



function api_tags_array()
{
//标签数据转成标签和标签链接组成的数组
  global $conn, $ngenre, $nlng;
  $tgenre = 'expansion/tags';
  $tdatabase = mm_cndatabase(ii_cvgenre($tgenre));
  $tidfield = mm_cnidfield(ii_cvgenre($tgenre));
  $tfpre = mm_cnfpre(ii_cvgenre($tgenre));
  $tsqlstr = 'select * from '. $tdatabase.' where 1 = 1';
  $trs = ii_conn_query($tsqlstr, $conn);
  $res_array = array();
  while ($trow = ii_conn_fetch_array($trs)){
      $tid = $trow[$tidfield];
      $ttopic = $trow[ii_cfnames($tfpre,'topic')];
      $tgourl = $trow[ii_cfnames($tfpre,'gourl')];
      if(!ii_isnull($tgourl)) $turl = '<a href="'.$tgourl.'" alt="'.$ttopic.'" title="'.$ttopic.'" target="_blank">'.$ttopic.'</a>';
      else $turl = '<a href="/'.$tgenre.'/?type=detail&id='.$tid.'" alt="'.$ttopic.'" title="'.$ttopic.'" target="_blank">'.$ttopic.'</a>';
      $res_array[$ttopic] = $turl;
  }
  if(is_array($res_array)){
    uksort($res_array,function($a,$b){
      return isset($b[strlen($a)]);
    });
  }
  $tappstr = $tgenre.'_' . $nlng;
  $tappstr = str_replace('/', '_', $tappstr);
  if (ii_cache_is($tappstr))
  {
    ii_cache_get($tappstr, 1);
  }
  else
  {
    $tary =  $res_array;
    ii_cache_put($tappstr, 1, $tary);//缓存生成的热词数组
    $GLOBALS[$tappstr] = $tary;
  }
  return $GLOBALS[$tappstr];
}

function api_tags_replace_tags($str)
{
//循环替换内容中的标签为标签链接
  $rule = '/<img.*?src=[\'"](.*?)[\'"].*?>/';
  preg_match_all($rule, $str, $matches);
  $str_without_alt = preg_replace($rule, 'Its_Just_A_Mark', $str);
  $replaces = api_tags_array();
  $str = api_tags_replace_limit(array_keys($replaces),array_values($replaces),$str_without_alt,1);
  foreach ($matches[0] as $alt_content) {
    $str = preg_replace('/Its_Just_A_Mark/',$alt_content,$str,1);
  }
  return $str;
}

function api_tags_replace_limit($search, $replace, $str, $limit=-1)
{
//增强型替换函数
  if(is_array($search)){
    foreach($search as $k=>$v){
      $search[$k] = '\'(' . $search[$k] . '(?![^<]*<\/a>))\'si';
    } 
  }else{
    $search = '\'(' . $search . '(?![^<]*<\/a>))\'si';
  }
  $str = preg_replace($search, $replace, $str, $limit); 
  return $str;
}



function api_tags_add(){
//模块内容添加时调用,{$=api_tags_add()}
  $tmpstr = ii_itake('global.expansion/tags:api.api_tags_add', 'tpl');
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function api_save_tags($id){
//模块内容入库时同步保存标签数据
	global $conn, $ngenre;
	$tgenre = 'expansion/tags';
	$tdatabase = mm_cndatabase(ii_cvgenre($tgenre), 'data');
	$tidfield = mm_cnidfield(ii_cvgenre($tgenre), 'data');
	$tfpre = mm_cnfpre(ii_cvgenre($tgenre), 'data');
    $tags = $_POST['tags'];
    $gid = $id;
    $tags_array = explode(",",$tags);
    foreach ($tags_array as $key => $val)
    {
    	$tid .= api_save_tags_insert($val).',';
    }
    $tid = rtrim($tid, ",");
    if(!empty($tid)){
	    $tsqlstr = "insert into $tdatabase (
	    	" . ii_cfnames($tfpre,'genre') . ",
	    	" . ii_cfnames($tfpre,'gid') . ",
	    	" . ii_cfnames($tfpre,'tid') . "
	    	) values (
	    		'" . $ngenre . "',
	    		'" . $gid . "',
	    		'" . $tid . "'
	    		)";
	    $trs = ii_conn_query($tsqlstr, $conn);
    }
}

function api_update_tags($id){
//模块内容编辑更新时同步更新标签数据
	global $conn, $ngenre;
	$tgenre = 'expansion/tags';
	$tdatabase = mm_cndatabase(ii_cvgenre($tgenre), 'data');
	$tidfield = mm_cnidfield(ii_cvgenre($tgenre), 'data');
	$tfpre = mm_cnfpre(ii_cvgenre($tgenre), 'data');
    $tags = $_POST['tags'];
    $gid = $id;
    $tags_array = explode(",",$tags);
    foreach ($tags_array as $key => $val)
    {
    	$tid .= api_save_tags_insert($val).',';
    }
    $tid = rtrim($tid, ",");
    if(api_tags_data_search_field($ngenre,'genre',$gid))
    {
	    $tsqlstr = 'update '.$tdatabase.' set
	    ' . ii_cfnames($tfpre,'tid') . '="' . $tid . '"
	    where '.ii_cfnames($tfpre,'genre').'="'.$ngenre.'" and '.ii_cfnames($tfpre,'gid').'='.$gid;
	    $trs = ii_conn_query($tsqlstr, $conn);
    }elseif(!empty($tid)){
	    $tsqlstr = "insert into $tdatabase (
	    	" . ii_cfnames($tfpre,'genre') . ",
	    	" . ii_cfnames($tfpre,'gid') . ",
	    	" . ii_cfnames($tfpre,'tid') . "
	    	) values (
	    		'" . $ngenre . "',
	    		'" . $gid . "',
	    		'" . $tid . "'
	    		)";
	    $trs = ii_conn_query($tsqlstr, $conn);
    }
}

function api_tags_edit(){
//模块内容编辑时调用,{$=api_tags_edit()}
  global $conn, $ngenre;
  $tgenre = 'expansion/tags';
  $tdatabase = mm_cndatabase(ii_cvgenre($tgenre), 'data');
  $tidfield = mm_cnidfield(ii_cvgenre($tgenre), 'data');
  $tfpre = mm_cnfpre(ii_cvgenre($tgenre), 'data');
  $gid = $_GET['id'];
  $tmpstr = ii_itake('global.expansion/tags:api.api_tags_edit', 'tpl');
  $tid = array();
  $tsqlstr = 'select * from '. $tdatabase.' where '.ii_cfnames($tfpre,"gid").'='.$gid.' and '.ii_cfnames($tfpre,"genre").'="'.$ngenre.'"';
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  $tids = $trs[ii_cfnames($tfpre,"tid")];
  $tags = api_get_tags_topic($tids,$gid);
  $tmpstr = str_replace('{$tags}', $tags, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function api_tags_list($id=''){
//模块内容前台调用标签函数,列表页调用需传入$id参数,内容页调用无需传入.
  global $conn, $ngenre;
  $tgenre = 'expansion/tags';
  $tdatabase = mm_cndatabase(ii_cvgenre($tgenre), 'data');
  $tidfield = mm_cnidfield(ii_cvgenre($tgenre), 'data');
  $tfpre = mm_cnfpre(ii_cvgenre($tgenre), 'data');
  $gid = (empty($id)) ? $_GET['id'] : $id;
  $tmptstr = '';
  $tmpstr = ii_itake('global.expansion/tags:api.api_tags_list', 'tpl');
  $tid = array();
  $tsqlstr = 'select * from '. $tdatabase.' where '.ii_cfnames($tfpre,"gid").'='.$gid.' and '.ii_cfnames($tfpre,"genre").'="'.$ngenre.'"';
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  $tids = $trs[ii_cfnames($tfpre,"tid")];
  $tids_array = explode(",",$tids);
    foreach ($tids_array as $key => $val)
    {
    	$tag = api_tags_field_by_id($val,'topic');
    	$tgourl = api_tags_field_by_id($val,'gourl');
        if(!ii_isnull($tgourl)) $tmptstr = str_replace('{$turl}', $tgourl, $tmpstr);
    	else $tmptstr = str_replace('{$turl}', '/expansion/tags/?type=detail&id='.$val, $tmpstr);
    	$tmptstr2 .= str_replace('{$tag}', $tag, $tmptstr).',';
    }
  $tmptstr2 = rtrim($tmptstr2, ",");
  if(!empty($tmptstr2)) $tmptstr2 = ii_itake('global.expansion/tags:api.tags','lng').':'.$tmptstr2;
  $tmpstr = ii_creplace($tmptstr2);
  return $tmpstr;
}

function api_get_tags_topic($tids,$gid){
//拼接ID转拼接标签,用英文逗号分隔
	$res = '';
	$tid_array = explode(",",$tids);
	foreach ($tid_array as $key => $val)
	{
		$res .= api_tags_field_by_id($val,'topic').',';
	}
	$res = rtrim($res, ",");
	return $res;
}

function api_save_tags_insert($topic)
{
   global $conn, $ngenre;
	$tgenre = 'expansion/tags';
	$tdatabase = mm_cndatabase(ii_cvgenre($tgenre));
	$tidfield = mm_cnidfield(ii_cvgenre($tgenre));
	$tfpre = mm_cnfpre(ii_cvgenre($tgenre));
	if(empty($topic)) return;
	if(api_tags_search_field(trim($topic),'topic')) return api_tags_id_by_topic($topic);//已添加
    $tsqlstr = "insert into $tdatabase (" . ii_cfnames($tfpre,'topic') . "," . ii_cfnames($tfpre,'time') . ") values ('" . $topic . "','".ii_now()."')";
    $trs = ii_conn_query($tsqlstr, $conn);
    if ($trs) $tid = ii_conn_insert_id($conn);
    return $tid;
}

function api_tags_field_by_id($tid,$field)
{
	global $conn;
	$tgenre = 'expansion/tags';
	$tdatabase = mm_cndatabase(ii_cvgenre($tgenre));
	$tidfield = mm_cnidfield(ii_cvgenre($tgenre));
	$tfpre = mm_cnfpre(ii_cvgenre($tgenre));
	$tsqlstr = 'select * from '. $tdatabase.' where '.$tidfield.'='.$tid;
	$trs = ii_conn_query($tsqlstr, $conn);
	$trs = ii_conn_fetch_array($trs);
    return  $trs[ii_cfnames($tfpre,$field)];
}

function api_tags_id_by_topic($topic)
{
	global $conn;
	$tgenre = 'expansion/tags';
	$tdatabase = mm_cndatabase(ii_cvgenre($tgenre));
	$tidfield = mm_cnidfield(ii_cvgenre($tgenre));
	$tfpre = mm_cnfpre(ii_cvgenre($tgenre));
	$tsqlstr = 'select * from '. $tdatabase.' where '.ii_cfnames($tfpre,'topic').'="'.$topic.'"';
	$trs = ii_conn_query($tsqlstr, $conn);
	$trs = ii_conn_fetch_array($trs);
    return  $trs[$tidfield];
}

function api_tags_search_field($field_val,$field)
{
//查询标签表字段值是否重复
	global $conn;
	$res = false;
	$tgenre = 'expansion/tags';
	$tdatabase = mm_cndatabase(ii_cvgenre($tgenre));
	$tidfield = mm_cnidfield(ii_cvgenre($tgenre));
	$tfpre = mm_cnfpre(ii_cvgenre($tgenre));
	$tmpstr = '';
	$tsqlstr = 'select * from '. $tdatabase.' where '.ii_cfnames($tfpre,$field).' = "' .$field_val.'"';
	$trs = ii_conn_query($tsqlstr, $conn);
	$trs = ii_conn_fetch_array($trs);
	if($trs) $res = true;
	else $res = false;
	return $res;
}

function api_tags_data_search_field($field_val,$field,$gid)
{
//查询标签数据表字段值是否重复
	global $conn;
	$res = false;
	$tgenre = 'expansion/tags';
	$tdatabase = mm_cndatabase(ii_cvgenre($tgenre),'data');
	$tidfield = mm_cnidfield(ii_cvgenre($tgenre),'data');
	$tfpre = mm_cnfpre(ii_cvgenre($tgenre),'data');
	$tmpstr = '';
	$tsqlstr = 'select * from '. $tdatabase.' where '.ii_cfnames($tfpre,$field).' = "' .$field_val.'" and '.ii_cfnames($tfpre,"gid").'=' . $gid;
	$trs = ii_conn_query($tsqlstr, $conn);
	$trs = ii_conn_fetch_array($trs);
	if($trs) $res = true;
	else $res = false;
	return $res;
}
?>
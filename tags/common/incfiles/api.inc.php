<?php
function api_tags_all($num='20') {
  //模块内容前台调用标签函数,列表页调用需传入$id参数,内容页调用无需传入.
  global $conn,$variable,$nurltype,$nurlpre;
  $tgenre = 'tags';
  $tdatabase = mm_cndatabase(ii_cvgenre($tgenre));
  $tidfield = mm_cnidfield(ii_cvgenre($tgenre));
  $tfpre = mm_cnfpre(ii_cvgenre($tgenre));
  $tmptstr = '';
  $tmpstr = ii_itake('global.tags:module.api_tags_all', 'tpl');
  $tsqlstr = 'select * from '. $tdatabase.' order by ' .ii_cfnames($tfpre,"count").' desc limit 0,'.$num;
  $trs = ii_conn_query($tsqlstr, $conn);
  while ($trow = ii_conn_fetch_array($trs))
  {
    $tag = ii_htmlencode($trow[ii_cfnames($tfpre,'topic')]);
    $tgourl = ii_htmlencode($trow[ii_cfnames($tfpre,'gourl')]);
    $tid = ii_htmlencode($trow[$tidfield]);
    if (!ii_isnull($tgourl)) $tmptstr = str_replace('{$turl}', $tgourl, $tmpstr);
    else $tmptstr = str_replace('{$turl}', $nurlpre.'/'.$tgenre.'/'.ii_iurl('detail', $tid, $nurltype), $tmpstr);
    $tmptstr2 .= str_replace('{$tag}', $tag, $tmptstr);
  }
  if (!empty($tmptstr2)) $tmptstr2 = $tmptstr2;
  $tmpstr = ii_creplace($tmptstr2);
  return $tmpstr;
}

function api_tags_genre_lists(){
  global $ngenre, $variable;
  $nsearch_genre = $variable[ii_cvgenre($ngenre) . '.nsearch_genre'];
  $tgenres = explode(',', $nsearch_genre);
  $tmpstr = '';
  foreach ($tgenres as $genre)
  {
    $tmpstr .= api_tags_genre_list($genre);
  }
  return $tmpstr;
}

function api_tags_genre_list($genre,$num='10') {
  //调用指定模块内容
  global $conn,$variable,$nurltype;
  $tid = ii_get_num($_GET['id']);
  $tnum = ii_get_num($num);
  $ndatabase_genre = $variable[ii_cvgenre($genre) . '.ndatabase'];
  $nidfield_genre = $variable[ii_cvgenre($genre) . '.nidfield'];
  $nfpre_genre = $variable[ii_cvgenre($genre) . '.nfpre'];
  $tgids = api_tags_get_gids($genre,$tid);
  $tmpstr = '';
  if(!ii_isnull($tgids)){
      $tsqlstr = "select * from $ndatabase_genre where ".$nidfield_genre  . " in (". $tgids .") and ".ii_cfnames($nfpre_genre,'hidden')."=0 order by ".ii_cfnames($nfpre_genre,'time')." desc limit 0,".$tnum;
      $trs = ii_conn_query($tsqlstr, $conn);
      if(ii_conn_affected_rows($conn) > 0) {
          $tmpstr = ii_itake('global.tags:module.api_tags_genre_list', 'tpl');
          $tmpastr = ii_ctemplate($tmpstr, '{@}');
          $tmptstr = '';
          $tmprstr = '';
          while ($trow = ii_conn_fetch_array($trs))
          {
            $ttopic = ii_htmlencode($trow[ii_cfnames($nfpre_genre,'topic')]);
            $tmptstr = str_replace('{$topic}', $ttopic, $tmpastr);
            $tmptstr = str_replace('{$id}', ii_get_num($trow[$nidfield_genre]), $tmptstr);
            $tmptstr = str_replace('{$genre}', $genre, $tmptstr);
            $tmptstr = str_replace('{$urltype}', $nurltype, $tmptstr);
            $tmptstr = ii_creplace($tmptstr);
            $tmprstr .= $tmptstr;
          }
          $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
          $tmpstr = str_replace('{$genre}', $genre, $tmpstr);
          $tmpstr = ii_creplace($tmpstr);
      }
  }
  return $tmpstr;
}

function api_tags_get_gids($genre,$tid)
{
  //统计有调用标签的文章ID组
  global $conn, $nlng;
  $gids = '';
  $tgenre = 'tags';
  $tdatabase = mm_cndatabase(ii_cvgenre($tgenre), 'data');
  $tidfield = mm_cnidfield(ii_cvgenre($tgenre), 'data');
  $tfpre = mm_cnfpre(ii_cvgenre($tgenre), 'data');
  $tsqlstr = 'select * from '. $tdatabase.' where '.ii_cfnames($tfpre,"genre").' = "'.$genre.'"';
  $trs = ii_conn_query($tsqlstr, $conn);
  while ($trow = ii_conn_fetch_array($trs))
  {
    $gid = $trow[ii_cfnames($tfpre,"gid")];
    $tids = $trow[ii_cfnames($tfpre,"tid")];
    $tid_array = explode(",",$tids);
    foreach ($tid_array as $key => $val)
    {
      if ($tid == $val) $gids .= $gid.',';
    }
  }
  $gids = ii_left($gids, ii_strlen($gids)-1);
  return $gids;
}

function api_tags_sum($tid)
{
  //统计每个标签被调用次数
  //计算标签数据表中对应的文章调用标签ID中是否存在当前标签ID.存在则+1
  global $conn;
  $sum = 0;
  $tgenre = 'tags';
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
      if ($tid == $val) $sum = $sum +1;
    }
  }
  return $sum;
}

function api_tags_array()
{
  //标签数据转成标签和标签链接组成的数组
  global $conn, $ngenre, $nlng, $variable, $nurltype, $nurlpre;
  $tgenre = 'tags';
  $tdatabase = mm_cndatabase(ii_cvgenre($tgenre));
  $tidfield = mm_cnidfield(ii_cvgenre($tgenre));
  $tfpre = mm_cnfpre(ii_cvgenre($tgenre));
  $tappstr = 'sys_' . $tgenre.'_' . $nlng;
  $tappstr = str_replace('/', '_', $tappstr);
  if (ii_cache_is($tappstr))
  {
    ii_cache_get($tappstr, 1);
  }
  else
  {
    $tsqlstr = 'select '.$tidfield.','.ii_cfnames($tfpre,'topic').','.ii_cfnames($tfpre,'gourl').' from '. $tdatabase.' where '.ii_cfnames($tfpre,'lng').' = "'. $nlng.'" ';
    $trs = ii_conn_query($tsqlstr, $conn);
    $trs = ii_conn_fetch_all($trs);
    $tnum = count($trs);
    $nrs = array();
    for($i=0;$i<$tnum;$i++)
    {
      $tid = $trs[$i][$tidfield];
      $ttopic = $trs[$i][ii_cfnames($tfpre,'topic')];
      $tgourl =$trs[$i][ii_cfnames($tfpre,'gourl')];
      if (!ii_isnull($tgourl)) $turl = $tgourl;
      else $turl = $nurlpre.'/'.$tgenre.'/'.ii_iurl('detail', $tid, $nurltype);
      $nrs[$ttopic] = &$turl;
      unset($trs[$i]);
      unset($tid);
      unset($tgourl);
      unset($ttopic);
      unset($turl);
    }
    unset($tnum);
    unset($trs);
    if (is_array($nrs)) {
      uksort($nrs,function($a,$b) {
        return isset($b[strlen($a)]);
      });
    }
    ii_cache_put($tappstr, 1, $nrs);//缓存生成的热词数组
    $GLOBALS[$tappstr] = &$nrs;
    unset($nrs);
  }
  return $GLOBALS[$tappstr];
}

function api_tags_replace_tags($str)
{
  //循环替换内容中的标签为标签链接
  $rule_img = '/<img.*?src=[\'"](.*?)[\'"].*?>/';
  $rule_a = '/<a .*?>.*?<\/a>/';
  preg_match_all($rule_img, $str, $matches_img);//img标签字符串
  preg_match_all($rule_a, $str, $matches_a);//a标签字符串
  $str_without_img = preg_replace($rule_img, 'Its_Just_IMG_Mark', $str);
  $str_without_a = preg_replace($rule_a, 'Its_Just_A_Mark', $str_without_img);
  $replaces = api_tags_array();
  //性能优化,strtr不对需替换的词进行查询及次数限制,可以提升替换速度.但需避免关键被包含在a标签内.
  //$str = strtr($str_without_a,$replaces);
  $str = api_tags_replace_limit(array_keys($replaces),array_values($replaces),$str_without_a,1);
  foreach ($matches_img[0] as $alt_content) {
    $str = preg_replace('/Its_Just_IMG_Mark/',$alt_content,$str,1);
  }
  foreach ($matches_a[0] as $alt_content) {
    $str = preg_replace('/Its_Just_A_Mark/',$alt_content,$str,1);
  }
  unset($alt_content);
  unset($replaces);
  unset($str_without_img);
  unset($str_without_a);
  unset($matches_img);
  unset($matches_a);
  unset($rule_img);
  unset($rule_a);
  return $str;
}

function api_tags_replace_limit($search, $replace, $str, $limit=-1)
{
  //增强型替换函数
  if (is_array($search)) {
    foreach($search as $k=>$v) {
      //这里对替换的链接进行拼接
      $replace[$k] = '<a href="'.$replace[$k].'">'.$search[$k].'</a>';
      $search[$k] = '\'(' . $search[$k] . '(?![^<]*<\/a>))\'si';
    }
  }else{
    //这里对替换的链接进行拼接
    $replace = '<a href="'.$replace.'">'.$search.'</a>';
    $search = '\'(' . $search . '(?![^<]*<\/a>))\'si';
  }
  $str = preg_replace($search, $replace, $str, $limit);
  unset($search);
  unset($replace);
  unset($limit);
  unset($k);
  unset($v);
  return $str;
}

function api_tags_add() {
  //模块内容添加时调用,{$=api_tags_add()}
  $tmpstr = ii_itake('global.tags:manage.api_tags_add', 'tpl');
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function api_save_tags($id) {
  //模块内容入库时同步保存标签数据
  global $conn, $ngenre;
  $tgenre = 'tags';
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
  if (!empty($tid)) {
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

function api_update_tags($id) {
  //模块内容编辑更新时同步更新标签数据
  global $conn, $ngenre;
  $tgenre = 'tags';
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
  if (api_tags_data_search_field($ngenre,'genre',$gid))
  {
    $tsqlstr = 'update '.$tdatabase.' set
	    ' . ii_cfnames($tfpre,'tid') . '="' . $tid . '"
	    where '.ii_cfnames($tfpre,'genre').'="'.$ngenre.'" and '.ii_cfnames($tfpre,'gid').'='.$gid;
    $trs = ii_conn_query($tsqlstr, $conn);
  }elseif (!empty($tid)) {
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

function api_tags_edit() {
  //模块内容编辑时调用,{$=api_tags_edit()}
  global $conn, $ngenre;
  $tgenre = 'tags';
  $tdatabase = mm_cndatabase(ii_cvgenre($tgenre), 'data');
  $tidfield = mm_cnidfield(ii_cvgenre($tgenre), 'data');
  $tfpre = mm_cnfpre(ii_cvgenre($tgenre), 'data');
  $gid = $_GET['id'];
  $tmpstr = ii_itake('global.tags:manage.api_tags_edit', 'tpl');
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

function api_tags_list($id='',$genre='') {
  //模块内容前台调用标签函数,列表页调用需传入$id参数,内容页调用无需传入.
  global $conn, $ngenre, $variable, $nurltype, $nurlpre;
  if (!ii_isnull($genre)) $ngenre = $genre;
  $tgenre = 'tags';
  $tdatabase = mm_cndatabase(ii_cvgenre($tgenre), 'data');
  $tidfield = mm_cnidfield(ii_cvgenre($tgenre), 'data');
  $tfpre = mm_cnfpre(ii_cvgenre($tgenre), 'data');
  $gid = (empty($id)) ? $_GET['id'] : $id;
  $tmptstr = '';
  $tmpstr = ii_itake('global.tags:module.api_tags_list', 'tpl');
  $tid = array();
  $tsqlstr = 'select * from '. $tdatabase.' where '.ii_cfnames($tfpre,"gid").'='.$gid.' and '.ii_cfnames($tfpre,"genre").'="'.$ngenre.'"';
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs) {
    $tstrplus = ii_itake('global.tags:manage.tags','lng').':';
    $tids = $trs[ii_cfnames($tfpre,"tid")];
    $tids_array = explode(",",$tids);
    foreach ($tids_array as $key => $val)
    {
      $tag = api_tags_field_by_id($val,'topic');
      $tgourl = api_tags_field_by_id($val,'gourl');
      if (!ii_isnull($tgourl)) $tmptstr = str_replace('{$turl}', $tgourl, $tmpstr);
      else $tmptstr = str_replace('{$turl}', $nurlpre.'/'.$tgenre.'/'.ii_iurl('detail', $val, $nurltype), $tmpstr);
      if (!ii_isnull($tag))$tmptstr2 .= str_replace('{$tag}', $tag, $tmptstr).',';
    }
    $tmptstr2 = rtrim($tmptstr2, ",");
  }
  if (!empty($tmptstr2)) {
    $tmptstr2 = $tstrplus.$tmptstr2;
  }
  $tmpstr = ii_creplace($tmptstr2);
  return $tmpstr;
}

function api_get_tags_topic($tids,$gid) {
  //拼接ID转拼接标签,用英文逗号分隔
  $res = '';
  $tid_array = explode(",",$tids);
  foreach ($tid_array as $key => $val)
  {
      $topic = api_tags_field_by_id($val,'topic');
      if(!ii_isnull($topic)) $res .= $topic.',';
  }
  $res = rtrim($res, ",");
  return $res;
}

function api_save_tags_insert($topic)
{
  global $conn, $ngenre, $slng;
  $tgenre = 'tags';
  $tdatabase = mm_cndatabase(ii_cvgenre($tgenre));
  $tidfield = mm_cnidfield(ii_cvgenre($tgenre));
  $tfpre = mm_cnfpre(ii_cvgenre($tgenre));
  if (empty($topic)) return;
  if (api_tags_search_field(trim($topic),'topic')) return api_tags_id_by_topic($topic);//已添加
  $tsqlstr = "insert into $tdatabase (
        " . ii_cfnames($tfpre,'topic') . ",
        " . ii_cfnames($tfpre,'time') . ",
        " . ii_cfnames($tfpre,'lng') . "
        ) values (
        '" . $topic . "',
        '".ii_now()."',
        '$slng'
        )";
  $trs = ii_conn_query($tsqlstr, $conn);
  if ($trs) $tid = ii_conn_insert_id($conn);
  return $tid;
}

function api_tags_field_by_id($tid,$field)
{
  global $conn;
  $tgenre = 'tags';
  $tids = ii_get_num($tid);
  $res = '';
  if($tids != 0){
      $tdatabase = mm_cndatabase(ii_cvgenre($tgenre));
      $tidfield = mm_cnidfield(ii_cvgenre($tgenre));
      $tfpre = mm_cnfpre(ii_cvgenre($tgenre));
      $tsqlstr = 'select * from '. $tdatabase.' where '.$tidfield.'='.$tids;
      $trs = ii_conn_query($tsqlstr, $conn);
      $trs = ii_conn_fetch_array($trs);
      $res = $trs[ii_cfnames($tfpre,$field)];
  }
  return $res;
}

function api_tags_id_by_topic($topic)
{
  global $conn;
  $tgenre = 'tags';
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
  global $conn,$slng;
  $res = false;
  $tgenre = 'tags';
  $tdatabase = mm_cndatabase(ii_cvgenre($tgenre));
  $tidfield = mm_cnidfield(ii_cvgenre($tgenre));
  $tfpre = mm_cnfpre(ii_cvgenre($tgenre));
  $tmpstr = '';
  $tsqlstr = 'select * from '. $tdatabase.' where '.ii_cfnames($tfpre,$field).' = "' .$field_val.'" and '.ii_cfnames($tfpre,'lng').' = "' .$slng.'"';
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs) $res = true;
  else $res = false;
  return $res;
}

function api_tags_data_search_field($field_val,$field,$gid)
{
  //查询标签数据表字段值是否重复
  global $conn;
  $res = false;
  $tgenre = 'tags';
  $tdatabase = mm_cndatabase(ii_cvgenre($tgenre),'data');
  $tidfield = mm_cnidfield(ii_cvgenre($tgenre),'data');
  $tfpre = mm_cnfpre(ii_cvgenre($tgenre),'data');
  $tmpstr = '';
  $tsqlstr = 'select * from '. $tdatabase.' where '.ii_cfnames($tfpre,$field).' = "' .$field_val.'" and '.ii_cfnames($tfpre,"gid").'=' . $gid;
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs) $res = true;
  else $res = false;
  return $res;
}
?>
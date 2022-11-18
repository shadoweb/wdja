<?php
function search_data_insert($tshkeyword) {
  global $conn,$nlng,$variable;
  $ngenre = 'search';
  $ndatabase = mm_cndatabase(ii_cvgenre($ngenre));
  $nidfield = mm_cnidfield(ii_cvgenre($ngenre));
  $nfpre = mm_cnfpre(ii_cvgenre($ngenre));
  if (mm_search_field($ngenre,$tshkeyword,'topic')) $count = search_data_get_field($tshkeyword) + 1;
  else $count = 1;
  $ip = ii_get_client_ip();
  $time = ii_now();
  $topic = ii_get_safecode($tshkeyword);
  $content = ii_encode_article($tshkeyword);
  $infos = ii_getAllHeaders();
  $infos = $infos['User-Agent'];
  $tsqlstr = "insert into $ndatabase (
  " . ii_cfnames($nfpre,'topic') . ",
  " . ii_cfnames($nfpre,'ip') . ",
  " . ii_cfnames($nfpre,'content') . ",
  " . ii_cfnames($nfpre,'infos') . ",
  " . ii_cfnames($nfpre,'count') . ",
  " . ii_cfnames($nfpre,'time') . ",
  " . ii_cfnames($nfpre,'update') . ",
  " . ii_cfnames($nfpre,'hidden') . ",
  " . ii_cfnames($nfpre,'lng') . "
  ) values (
  '" . $topic . "',
  '" . $ip . "',
  '" . $content . "',
  '" . $infos . "',
  '" . $count . "',
  '" . ii_now() . "',
  '" . ii_now() . "',
  '1',
  '$nlng'
  )";
  $trs = ii_conn_query($tsqlstr, $conn);
  if($trs) return ii_conn_insert_id($conn);
}

function search_data_get_field($tshkeyword) {
  global $conn,$nlng,$variable;
  $count = 0;
  $ngenre = 'search';
  $ndatabase = mm_cndatabase(ii_cvgenre($ngenre));
  $nidfield = mm_cnidfield(ii_cvgenre($ngenre));
  $nfpre = mm_cnfpre(ii_cvgenre($ngenre));
  $tsqlstr = 'select '.ii_cfnames($nfpre,"count").' from '. $ndatabase.' where '.ii_cfnames($nfpre,"topic").' = "' .$tshkeyword.'" order by '.ii_cfnames($nfpre,'time').' desc';
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs) $count = $trs[ii_cfnames($nfpre,'count')];
  return $count;
}

function search_data_view_all($num = 10) {
  global $nurlpre;
  $top = search_data_top_cache();
  $top = $top['data'];
  $res = '';
  if(is_array($top)){
    $ntrs = array_slice($top,0,$num);
    $dom = '<a href="'.$nurlpre.'/search/?type=list&keyword={$topic}" data="{$count}">{$topic}</a> ';
    $doma = '';
    foreach($ntrs as $key=>$val){
      $doma = str_replace('{$topic}',$val['topic'],$dom);
      $doma = str_replace('{$count}',$val['count'],$doma);
      $res .= $doma;
    }
  }
  return $res;
}

function search_data_top_cache() {
  global $nlng;
  $ngenre = 'search';
  $tappstr = 'sys_' . $ngenre.'_' . $nlng;
  $tappstr = str_replace('/', '_', $tappstr);
  if (ii_cache_is($tappstr))
  {
    ii_cache_get($tappstr, 1);
    $tdata = $GLOBALS[$tappstr];
    $ttime = $tdata['time'];//原缓存生成时间
    if (ii_check_expireDate($ttime,'30','5')) {
        //如果超过30分钟,则删除缓存,再重新生成后获取
        ii_cache_remove($tappstr);
        search_data_top_putCache($tappstr);
    }
  }
  else search_data_top_putCache($tappstr);
  return $GLOBALS[$tappstr];
}

function search_data_top_putCache($tappstr) {
  global $conn,$nlng;
  $ngenre = 'search';
  $ndatabase = mm_cndatabase(ii_cvgenre($ngenre));
  $nidfield = mm_cnidfield(ii_cvgenre($ngenre));
  $nfpre = mm_cnfpre(ii_cvgenre($ngenre));
  $tsqlstr = 'select distinct '.ii_cfnames($nfpre,"topic").' from '. $ndatabase.' where '.ii_cfnames($nfpre,"hidden").' = 0';
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_all($trs);
  if ($trs){
    $ntrs=Array();
    foreach($trs as $k=>$v){
      $count = search_data_get_field($v[ii_cfnames($nfpre,"topic")]);
      $ntrs[$k]['topic'] = $v[ii_cfnames($nfpre,"topic")];
      $ntrs[$k]['count'] = $count;
    }
    $topic=Array();
    $count=Array();
    foreach($ntrs as $key => $val){
      $topic[$key] = $val['topic'];
      $count[$key] = $val['count'];
    }
    array_multisort($count,SORT_DESC,$topic,SORT_ASC,$ntrs);
    $tres['data'] = $ntrs;
    $tres['time'] = ii_now();
    ii_cache_put($tappstr, 1, $tres);//缓存生成的热词数组
    $GLOBALS[$tappstr] = &$tres;
    unset($ntrs);
  }
  return $GLOBALS[$tappstr];
}

?>
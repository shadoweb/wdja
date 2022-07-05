<?php
function mm_baidu_push($type,$genre,$topic,$gid) {
  ii_conn_init();
  $baidu_offon = ii_itake('global.support/global:seo.baidupush_switch','lng');//推送配置开关
  $baidu_url = ii_itake('global.support/global:seo.baidupush_url','lng');//推送配置url
  $baidu_token = ii_itake('global.support/global:seo.baidupush_token','lng');//推送配置token
  if ($baidu_offon == 0) return;
  //推送方式:推送 urls 更新 update 删除 del
  global $nurlpre,$nurltype;
  $url = $nurlpre.'/'.$genre.'/'.ii_iurl('detail', $gid, $nurltype);
  $turl = str_replace('&amp;', '&', $url);
  if (ii_isnull($baidu_url)) $baidu_url = $nurlpre;
  $urls = array(
    $turl,
  );
  $api = 'http://data.zz.baidu.com/'.$type.'?site='.$baidu_url.'&token='.$baidu_token;
  $ch = curl_init();
  $options =  array(
    CURLOPT_URL => $api,
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POSTFIELDS => implode("\n", $urls),
    CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
  );
  curl_setopt_array($ch, $options);
  $result = curl_exec($ch);
  $res = json_decode($result, true);
  if (ii_isnull($res['error']) && !ii_isnull($res['success'])) {
    $state = 1;
    if ($type == 'urls') baidu_insert($genre,$topic,$gid, $result,$state);
    if ($type == 'update') baidu_update($genre,$gid, $result,$state);
    if ($type == 'del') baidu_del($genre,$gid, $result,$state);
  }else{
    $state = 0;
    if ($type == 'urls') baidu_insert($genre,$topic,$gid, $result,$state);
    if ($type == 'update') baidu_update($genre,$gid, $result,$state);
    if ($type == 'del') baidu_del($genre,$gid, $result,$state);
  }
}

function baidu_insert($genre,$topic,$gid,$content,$state)
{
  global $conn, $variable, $nurlpre, $nurltype;
  ii_conn_init();
  $ngenre = 'expansion/baidupush';
  $nlng = 'chinese';
  $ndatabase = $variable[ii_cvgenre($ngenre) . '.ndatabase'];
  $nidfield = $variable[ii_cvgenre($ngenre) . '.nidfield'];
  $nfpre = $variable[ii_cvgenre($ngenre) . '.nfpre'];
  $turl = $nurlpre.'/'.$genre.'/'.ii_iurl('detail',$gid, $nurltype);
  $turl = str_replace('&amp;', '&', $turl);
  $ttype = 'urls';
  $ttime = ii_now();
  $tupdate = ii_now();
  $tcount = 1 ;
  $tsqlstr = "insert into $ndatabase (
    " . ii_cfnames($nfpre,'genre') . ",
    " . ii_cfnames($nfpre,'gid') . ",
    " . ii_cfnames($nfpre,'topic') . ",
    " . ii_cfnames($nfpre,'url') . ",
    " . ii_cfnames($nfpre,'content') . ",
    " . ii_cfnames($nfpre,'count') . ",
    " . ii_cfnames($nfpre,'type') . ",
    " . ii_cfnames($nfpre,'state') . ",
    " . ii_cfnames($nfpre,'time') . ",
    " . ii_cfnames($nfpre,'update') . ",
    " . ii_cfnames($nfpre,'lng') . "
    ) values (
    '" . ii_left($genre, 50) . "',
    '" . $gid . "',
    '" . ii_left($topic, 50) . "',
    '" . $turl . "',
    '" . $content . "',
    '" . ii_get_num($tcount) . "',
    '" . $ttype . "',
    '" . $state . "',
    '$ttime',
    '$tupdate',
    '$nlng'
    )";
  $trs = ii_conn_query($tsqlstr, $conn);
  if ($trs) {
    $bid = ii_conn_insert_id($conn);
    baidu_data_insert($bid,$tcount,$ttype,$state,$content);
  }
}

function baidu_update($genre,$gid, $result,$state)
{
  global $conn, $variable;
  ii_conn_init();
  $ngenre = 'expansion/baidupush';
  $ndatabase = $variable[ii_cvgenre($ngenre) . '.ndatabase'];
  $nidfield = $variable[ii_cvgenre($ngenre) . '.nidfield'];
  $nfpre = $variable[ii_cvgenre($ngenre) . '.nfpre'];
  $ttype = 'update';
  $tupdate = ii_now();
  $tsqlstr = 'update '.$ndatabase.' set 
  '.ii_cfnames($nfpre, "count") . '=' . ii_cfnames($nfpre, "count") . '+1,
  '.ii_cfnames($nfpre, "state") . '= "'.$state.'" ,
  '.ii_cfnames($nfpre, "content") . '= \''.$result.'\' ,
  '.ii_cfnames($nfpre, "type") . '= "'.$ttype.'" ,
  '.ii_cfnames($nfpre, "update") . '= "'.$tupdate.'" 
  where ' . ii_cfnames($nfpre, "genre") . '="'.$genre.'" and ' . ii_cfnames($nfpre, "gid") . '="'.$gid.'"';
  $trs = ii_conn_query($tsqlstr, $conn);
  if ($trs) {
    $nsqlstr = 'select '.$nidfield.' from '. $ndatabase.' where ' . ii_cfnames($nfpre, "genre") . '="'.$genre.'" and ' . ii_cfnames($nfpre, "gid") . '="'.$gid.'"';
    $nrs = ii_conn_query($nsqlstr, $conn);
    $nrs = ii_conn_fetch_array($nrs);
    $bid = $nrs[$nidfield];
    $tcount = baidu_get_field($bid,"count");
    baidu_data_insert($bid,$tcount,$ttype,$state,$result);
  }
}

function baidu_del($genre,$gid, $result,$state)
{
  global $conn, $variable;
  ii_conn_init();
  $ngenre = 'expansion/baidupush';
  $ndatabase = $variable[ii_cvgenre($ngenre) . '.ndatabase'];
  $nidfield = $variable[ii_cvgenre($ngenre) . '.nidfield'];
  $nfpre = $variable[ii_cvgenre($ngenre) . '.nfpre'];
  $ttype = 'del';
  $tupdate = ii_now();
  $tsqlstr = 'update '.$ndatabase.' set '.ii_cfnames($nfpre, "count") . '=' . ii_cfnames($nfpre, "count") . '+1,'.ii_cfnames($nfpre, "state") . '= "'.$state.'" ,'.ii_cfnames($nfpre, "content") . '= \''.$result.'\' ,'.ii_cfnames($nfpre, "type") . '= "'.$ttype.'" ,'.ii_cfnames($nfpre, "update") . '= "'.$tupdate.'" where ' . ii_cfnames($nfpre, "genre") . '="'.$genre.'" and ' . ii_cfnames($nfpre, "gid") . '="'.$gid.'"';
  $trs = ii_conn_query($tsqlstr, $conn);
  if ($trs) {
    $nsqlstr = 'select '.$nidfield.' from '. $ndatabase.' where ' . ii_cfnames($nfpre, "genre") . '="'.$genre.'" and ' . ii_cfnames($nfpre, "gid") . '="'.$gid.'"';
    $nrs = ii_conn_query($nsqlstr, $conn);
    $nrs = ii_conn_fetch_array($nrs);
    $bid = $nrs[$nidfield];
    $tcount = baidu_get_field($bid,"count");
    baidu_data_insert($bid,$tcount,$ttype,$state,$result);
  }
}

function mm_search_baidu($array)
{
  //查询是否已推送过
  global $conn, $variable;
  ii_conn_init();
  $res = false;
  $tgenre = 'expansion/baidupush';
  $tdatabase = $variable[ii_cvgenre($tgenre) . '.ndatabase'];
  $tidfield = $variable[ii_cvgenre($tgenre) . '.nidfield'];
  $tfpre = $variable[ii_cvgenre($tgenre) . '.nfpre'];
  $genre = $array['genre'];
  $gid = $array['gid'];
  $tmpstr = '';
  $tsqlstr = 'select * from '. $tdatabase.' where '.ii_cfnames($tfpre,'genre').' = "' .$genre.'" and '.ii_cfnames($tfpre,'gid').' = "' .$gid.'"';
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs) $res = true;
  else $res = false;
  return $res;
}

function baidu_data_insert($bid,$order,$type,$state,$content)
{
  global $conn, $variable, $nurlpre;
  ii_conn_init();
  $ngenre = 'expansion/baidupush';
  $nlng = 'chinese';
  $ndatabase = $variable[ii_cvgenre($ngenre) . '.ndatabase_data'];
  $nidfield = $variable[ii_cvgenre($ngenre) . '.nidfield_data'];
  $nfpre = $variable[ii_cvgenre($ngenre) . '.nfpre_data'];
  $ttime = ii_now();
  $tcount = 1 ;
  $tsqlstr = "insert into $ndatabase (
    " . ii_cfnames($nfpre,'bid') . ",
    " . ii_cfnames($nfpre,'order') . ",
    " . ii_cfnames($nfpre,'type') . ",
    " . ii_cfnames($nfpre,'state') . ",
    " . ii_cfnames($nfpre,'content') . ",
    " . ii_cfnames($nfpre,'time') . ",
    " . ii_cfnames($nfpre,'lng') . "
    ) values (
    '" . $bid . "',
    '" . $order . "',
    '" . $type . "',
    '" . $state . "',
    '" . $content . "',
    '$ttime',
    '$nlng'
    )";
  $trs = ii_conn_query($tsqlstr, $conn);
}

function baidu_get_field($bid,$field)
{
  global $conn, $variable;
  ii_conn_init();
  $ngenre = 'expansion/baidupush';
  $nlng = 'chinese';
  $ndatabase = $variable[ii_cvgenre($ngenre) . '.ndatabase'];
  $nidfield = $variable[ii_cvgenre($ngenre) . '.nidfield'];
  $nfpre = $variable[ii_cvgenre($ngenre) . '.nfpre'];
  $tsqlstr = 'select '.ii_cfnames($nfpre, $field).' from '.$ndatabase.' where ' . $nidfield . '="'.$bid.'"';
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs)
  {
    $res =$trs[ii_cfnames($nfpre, $field)];
  }
  return $res;
}
?>
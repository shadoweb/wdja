<?php
require('ip.inc.php');
require('baidu.inc.php');
require('save_images.inc.php');
require('allpay.inc.php');
require('oss.inc.php');

function mm_get_detail_check($genre,$gid,$url)
{
  $tmpstr = ii_itake('global.check:module.detail_check', 'tpl');
  $tmpstr = str_replace('{$genre}', $genre, $tmpstr);
  $tmpstr = str_replace('{$id}', $gid, $tmpstr);
  $tmpstr = str_replace('{$url}', $url, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function mm_get_detail_check_history($genre,$gid)
{
  global $conn, $nlng, $variable;
  $ngenre = 'check';
  $ndatabase = $variable[ii_cvgenre($ngenre) . '.ndatabase'];
  $nidfield = $variable[ii_cvgenre($ngenre) . '.nidfield'];
  $nfpre = $variable[ii_cvgenre($ngenre) . '.nfpre'];
  $npagesize = $variable[ii_cvgenre($ngenre) . '.npagesize'];
  $nlisttopx = $variable[ii_cvgenre($ngenre) . '.nlisttopx'];
  $toffset = ii_get_num($_GET['offset']);
  $tmpstr = ii_itake('global.check:module.detail_check_history', 'tpl');
  $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_ida}');
  $tmprstr = '';
  $tsqlstr = "select * from $ndatabase where " . ii_cfnames($nfpre,'genre') . "='$genre' and " . ii_cfnames($nfpre,'gid') . "='$gid' and " . ii_cfnames($nfpre,'hidden') . "=0 and " . ii_cfnames($nfpre,'lng') . "='$nlng' order by " . ii_cfnames($nfpre,'time') . " desc";
  $tcp = new cc_cutepage;
  $tcp -> id = $nidfield;
  $tcp -> pagesize = $npagesize;
  $tcp -> rslimit = $nlisttopx;
  $tcp -> sqlstr = $tsqlstr;
  $tcp -> offset = $toffset;
  $tcp -> init();
  $trsary = $tcp -> get_rs_array();
  if (is_array($trsary))
  {
    foreach($trsary as $trs)
    {
      if (ii_isnull($trs[ii_cfnames($nfpre,'reply')])) $treplyis = 0;
      else $treplyis = 1;
      $tmptstr = mm_cvalhtml($tmpastr, $treplyis, '{@admin_reply}');
      foreach ($trs as $key => $val)
      {
        $tkey = ii_get_lrstr($key, '_', 'rightr');
        $GLOBALS['RS_' . $tkey] = $val;
        $tmptstr = str_replace('{$' . $tkey . '}', ii_htmlencode($val), $tmptstr);
      }
      $tmptstr = str_replace('{$id}', $trs[$nidfield], $tmptstr);
      $tmptstr = str_replace('{$gid}', $gid, $tmptstr);
      $tmptstr = ii_creplace($tmptstr);
      $tmprstr .= $tmptstr;
    }
  }
  $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
  $tmpstr = str_replace('{$cpagestr}', $tcp -> get_pagenum(), $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function api_userGroupPrice_add() {
  $outputstr = '';
  $txinfostr = 'global.user:sel_group.all';
  $trxinfoary = ii_replace_xinfo_ary($txinfostr, 'sel');
  $troute = $trxinfoary[0];
  $tinputary = ii_get_xinfo($troute, $nlng);
  if (is_array($tinputary))
  {
    $input = ii_itake('global.tpl_config.xmlinput_input', 'tpl');
    foreach ($tinputary as $ugid => $ugname)
    {
        $outputstr = str_replace('{$ugid}', $ugid, $input);
        $outputstr = str_replace('{$ugname}', $ugname, $outputstr);
        $outputstr = str_replace('{$value}', '', $outputstr);
        $toutputstr .= $outputstr;
    }
    $outputstr = ii_creplace($toutputstr);
  }
  return $outputstr;
}

function api_userGroupPrice_edit($shopid) {
    //通过商品ID获取会员价格进行后台添加时输出
  $outputstr = '';
  $txinfostr = 'global.user:sel_group.all';
  $trxinfoary = ii_replace_xinfo_ary($txinfostr, 'sel');
  $troute = $trxinfoary[0];
  $tinputary = ii_get_xinfo($troute, $nlng);
  if (is_array($tinputary))
  {
    $input = ii_itake('global.tpl_config.xmlinput_input', 'tpl');
    foreach ($tinputary as $ugid => $ugname)
    {
        $outputstr = str_replace('{$ugid}', $ugid, $input);
        $outputstr = str_replace('{$ugname}', $ugname, $outputstr);
        $outputstr = str_replace('{$value}', api_get_userGroupPrice($shopid,$ugid), $outputstr);
        $toutputstr .= $outputstr;
    }
    $outputstr = ii_creplace($toutputstr);
  }
  return $outputstr;
}

function api_view_userGroupPrice($shopid) {
    //通过商品ID获取会员价格进行前台输出
  $outputstr = '';
  $txinfostr = 'global.user:sel_group.all';
  $trxinfoary = ii_replace_xinfo_ary($txinfostr, 'sel');
  $troute = $trxinfoary[0];
  $tinputary = ii_get_xinfo($troute, $nlng);
  if (is_array($tinputary))
  {
    $input = ii_itake('global.tpl_config.shop_price', 'tpl');
    foreach ($tinputary as $ugid => $ugname)
    {
        $outputstr = str_replace('{$ugid}', $ugid, $input);
        $outputstr = str_replace('{$ugname}', $ugname, $outputstr);
        $outputstr = str_replace('{$value}', api_get_userGroupPrice($shopid,$ugid), $outputstr);
        $toutputstr .= $outputstr;
    }
    $outputstr = ii_creplace($toutputstr);
  }
  return $outputstr;
}

function api_get_userGroupPrice($shopid,$ugid) {
  global $conn, $variable, $slng;
  $tgenre = 'shop';
  if (ii_isnull($ugid)) return mm_get_field($tgenre,$shopid,'wprice');//未登录使用商品销售价添加进购物车
  $tdatabase = $variable[ii_cvgenre($tgenre) . '.ndatabase_price'];
  $tidfield = $variable[ii_cvgenre($tgenre) . '.nidfield_price'];
  $tfpre = $variable[ii_cvgenre($tgenre) . '.nfpre_price'];
  $tsqlstr = "select " . ii_cfnames($tfpre, 'group_price') . " from $tdatabase where " . ii_cfnames($tfpre, 'shop_id') . " = $shopid and " . ii_cfnames($tfpre, 'group_id') . " = $ugid";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if (is_array($trs)) return ii_get_num($trs[ii_cfnames($tfpre, 'group_price')]);
}

function api_userGroupPrice_adddisp($shopid) {
    //添加
  $outputstr = '';
  $txinfostr = 'global.user:sel_group.all';
  $trxinfoary = ii_replace_xinfo_ary($txinfostr, 'sel');
  $troute = $trxinfoary[0];
  $tinputary = ii_get_xinfo($troute, $nlng);
  if (is_array($tinputary))
  {
    foreach ($tinputary as $ugid => $ugname)
    {
        api_insert_userGroupPrice($shopid,$ugid);//循环插入数据
    }
  }
}

function api_userGroupPrice_editdisp($shopid) {
    //编辑
  $outputstr = '';
  $txinfostr = 'global.user:sel_group.all';
  $trxinfoary = ii_replace_xinfo_ary($txinfostr, 'sel');
  $troute = $trxinfoary[0];
  $tinputary = ii_get_xinfo($troute, $nlng);
  if (is_array($tinputary))
  {
    foreach ($tinputary as $ugid => $ugname)
    {
        api_update_userGroupPrice($shopid,$ugid);//循环更新数据
    }
  }
}

function api_insert_userGroupPrice($shopid,$ugid) {
  global $conn, $variable, $slng;
  $tgenre = 'shop';
  $tdatabase = $variable[ii_cvgenre($tgenre) . '.ndatabase_price'];
  $tidfield = $variable[ii_cvgenre($tgenre) . '.nidfield_price'];
  $tfpre = $variable[ii_cvgenre($tgenre) . '.nfpre_price'];
  $tshop_id = $shopid;
  $tgroup_id = $ugid;
  $tgroup_price = $_POST['ugid_'.$ugid];
    $tsqlstr = "insert into $tdatabase (
    " . ii_cfnames($tfpre,'shop_id') . ",
    " . ii_cfnames($tfpre,'group_id') . ",
    " . ii_cfnames($tfpre,'group_price') . ",
    " . ii_cfnames($tfpre,'time') . ",
    " . ii_cfnames($tfpre,'update') . ",
    " . ii_cfnames($tfpre,'lng') . "
    ) values (
    '" . $tshop_id . "',
    '" . $tgroup_id . "',
    '" . $tgroup_price . "',
    '" . ii_now() . "',
    '" . ii_now() . "',
    '$slng'
    )";
    $trs = ii_conn_query($tsqlstr, $conn);
}

function api_update_userGroupPrice($shopid,$ugid) {
  global $conn, $variable, $slng;
  $tgenre = 'shop';
  $tdatabase = $variable[ii_cvgenre($tgenre) . '.ndatabase_price'];
  $tidfield = $variable[ii_cvgenre($tgenre) . '.nidfield_price'];
  $tfpre = $variable[ii_cvgenre($tgenre) . '.nfpre_price'];
  $tshop_id = $shopid;
  $tgroup_id = $ugid;
  if (api_ischeck_userGroupPrice($shopid,$ugid)) {
  $tgroup_price = $_POST['ugid_'.$ugid];
    $tsqlstr = "update $tdatabase set
    " . ii_cfnames($tfpre,'group_price') . "=" . $tgroup_price . ",
    " . ii_cfnames($tfpre,'update') . "='" . ii_now() . "'
    where " . ii_cfnames($tfpre,'shop_id') . "='".$tshop_id."' and " . ii_cfnames($tfpre,'group_id') . "= '".$tgroup_id."'";
    $trs = ii_conn_query($tsqlstr, $conn);
  }
  else api_insert_userGroupPrice($shopid,$ugid);
}

function api_ischeck_userGroupPrice($shopid,$ugid) {
  global $conn, $variable, $slng;
  $bool = false;
  $tgenre = 'shop';
  $tdatabase = $variable[ii_cvgenre($tgenre) . '.ndatabase_price'];
  $tidfield = $variable[ii_cvgenre($tgenre) . '.nidfield_price'];
  $tfpre = $variable[ii_cvgenre($tgenre) . '.nfpre_price'];
  $tsqlstr = "select " . ii_cfnames($tfpre, 'group_price') . " from $tdatabase where " . ii_cfnames($tfpre, 'shop_id') . " = $shopid and " . ii_cfnames($tfpre, 'group_id') . " = $ugid";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if (is_array($trs)) $bool = true;
  return $bool;
}

function mm_get_vuser($vid,$nid)
{
  //获取虚拟用户名
  global $conn, $ngenre, $variable, $nurlpre, $nlng;
  $tvuser = mm_get_field($ngenre,$nid,'vuser');
  $tgenre = 'expansion/vuser';
  $tdatabase = $variable[ii_cvgenre($tgenre) . '.ndatabase'];
  $tidfield = $variable[ii_cvgenre($tgenre) . '.nidfield'];
  $tfpre = $variable[ii_cvgenre($tgenre) . '.nfpre'];
  $trsPre = ii_itake('global.' . $tgenre . ':manage.upload_user', 'lng');
  $trsNext = ii_itake('global.' . $tgenre . ':manage.tips_user', 'lng');
  $tsqlstr = "select " . ii_cfnames($tfpre, 'topic') . " from $tdatabase where $tidfield = $vid";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if (is_array($trs) && $tvuser == 1) return $trsPre.$trs[ii_cfnames($tfpre, 'topic')].$trsNext;
}

function mm_get_rand_vuser()
{
  //随机获取虚拟用户ID
  global $conn, $variable, $nurlpre, $nlng;
  $ngenre = 'expansion/vuser';
  $ndatabase = $variable[ii_cvgenre($ngenre) . '.ndatabase'];
  $nidfield = $variable[ii_cvgenre($ngenre) . '.nidfield'];
  $nfpre = $variable[ii_cvgenre($ngenre) . '.nfpre'];
  $tsqlstr = "select $nidfield from $ndatabase order by rand() desc";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  return $trs[$nidfield];
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

function mm_get_content_image($genre,$content,$image) {
  global $global_images_route,$nskin;
  preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER); 
  $n = count($strResult[1]);
  if (strpos($image,'/noimg.gif') !== false || ii_isnull($image)) {
      if ($n > 0) {
          $img_url = $strResult[1][0]; 
       }else{
       $random = mt_rand(1, 25);
       $img_url = $global_images_route.'theme/'.$nskin.'/random/'. $random .'.jpg';
       }
    }else{
      $img_url = $image; 
      return $img_url;
    }
  return $img_url;
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

//生成商品编号
function mm_get_shopnum() {
  date_default_timezone_set('PRC');
  return date('ymd').substr(time(),-4).substr(microtime(),2,5).mt_rand(10,99);
}

function mm_get_myaddressary($nusername, $lng)
{
  global $conn;
  global $address_database, $address_idfield, $address_fpre;
  $tarys = Array();
  $tusername = ii_get_safecode($nusername);
  $tlng = ii_get_safecode($lng);
  $tsqlstr = "select * from $address_database where " . ii_cfnames($address_fpre, 'username') . "='$tusername' and " . ii_cfnames($address_fpre, 'lng') . "='$tlng' order by " . ii_cfnames($address_fpre, 'time') . " asc";
  $trs = ii_conn_query($tsqlstr, $conn);
  while ($trow = ii_conn_fetch_array($trs))
  {
    $tary[$trow[$address_idfield]]['id'] = $trow[$address_idfield];
    $tary[$trow[$address_idfield]]['name'] = $trow[ii_cfnames($address_fpre, 'name')];
    $tarys += $tary;
  }
  return $tarys;
}

function mm_get_addressary($nusername, $lng)
{
  $tary = mm_get_myaddressary($nusername, $lng);
  $GLOBALS[$tappstr] = $tary;
  return $GLOBALS[$tappstr];
}

function mm_sel_address($nusername,$tid = '0')
{
  global $nusername, $nlng;
  $tary = mm_get_addressary($nusername, $nlng);
  if (is_array($tary))
  {
    $trestr = ii_itake('global.tpl_config.sys_spsort', 'tpl');
    $option_unselected = ii_itake('global.tpl_config.option_unselect', 'tpl');
    $option_selected = ii_itake('global.tpl_config.option_select', 'tpl');
    $tmpstr = '';
    $treturnstr = '';
    foreach ($tary as $key => $val)
    {
      if ($key == $tid) $tmpstr = $option_selected;
      else $tmpstr = $option_unselected;
      $tmpstr = str_replace('{$explain}', $val['name'], $tmpstr);
      $tmpstr = str_replace('{$value}', $val['id'], $tmpstr);
      $treturnstr .= $tmpstr;
    }
    return $treturnstr;
  }
}

function mm_content_mip($content) {
  //百度mip移动框架使用
  global $nurlpre;
  //以下代码可根据需要修改/删除
  $content = preg_replace('/(width|height)="\d*"\s/', '', $content);//移除图片 width|height
  $content = preg_replace('/ style=\".*?\"/', '',$content);//移除图片 style
  $content = preg_replace('/ class=\".*?\"/', '',$content);//移除图片 class
  //以上代码可根据需要修改/删除
  preg_match_all('/<img (.*?)\>/', $content, $images);
  if (!is_null($images)) {
    foreach($images[1] as $index => $value) {
      $mip_img = str_replace(array('src="' ,'<img'),array('src="'.$nurlpre,'<mip-img popup') , $images[0][$index]);//图片绝对路径，根据自己的实际情况选用
      $mip_img = str_replace('>', '></mip-img>', $mip_img);
      $content = str_replace($images[0][$index], $mip_img, $content);
    }
  }
  return $content;
}

function mm_update_field($genre,$id,$field,$val)
{
  //更新任意字段
  global $conn, $variable;
  ii_conn_init();
  $bool = false;
  $tgenre = $genre;
  $tdatabase = mm_cndatabase(ii_cvgenre($tgenre));
  $tidfield = mm_cnidfield(ii_cvgenre($tgenre));
  $tfpre = mm_cnfpre(ii_cvgenre($tgenre));
  $tsqlstr = 'update '. $tdatabase.' set '.ii_cfnames($tfpre,$field).' = '.$val.' where '.$tidfield.' = ' .$id;
  $trs = ii_conn_query($tsqlstr, $conn);
  if (ii_conn_affected_rows($conn) > 0) $bool = true;
  return $bool;
}

function mm_search_field($genre,$field_val,$field,$id = '0')
{
  //查询字段值是否重复
  global $conn, $variable;
  ii_conn_init();
  $res = false;
  $tgenre = $genre;
  $tdatabase = $variable[ii_cvgenre($tgenre) . '.ndatabase'];
  $tidfield = $variable[ii_cvgenre($tgenre) . '.nidfield'];
  $tfpre = $variable[ii_cvgenre($tgenre) . '.nfpre'];
  $tmpstr = '';
  if ($id == '0') $tsqlstr = 'select * from '. $tdatabase.' where '.ii_cfnames($tfpre,$field).' = "' .$field_val.'"';
  else $tsqlstr = 'select * from '. $tdatabase.' where '.ii_cfnames($tfpre,$field).' = "' .$field_val.'" and ' . $tidfield . ' <> ' . $id;
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs) $res = true;
  else $res = false;
  return $res;
}

function mm_get_field($genre,$id,$field)
{
  //获取模块任意字段
  global $conn, $variable;
  ii_conn_init();
  $tgenre = $genre;
  $tdatabase = $variable[ii_cvgenre($tgenre) . '.ndatabase'];
  $tidfield = $variable[ii_cvgenre($tgenre) . '.nidfield'];
  $tfpre = $variable[ii_cvgenre($tgenre) . '.nfpre'];
  $tmpstr = '';
  $result = '';
  if(!ii_isnull($tdatabase) && !ii_isnull($tidfield) && !ii_isnull($tfpre) && !ii_isnull($id)){
      $tsqlstr = 'select * from '. $tdatabase.' where '.$tidfield.' = ' .$id;
      $trs = ii_conn_query($tsqlstr, $conn);
      $trs = ii_conn_fetch_array($trs);
      $result = $trs[ii_cfnames($tfpre,$field)];
  }
  return $result;
}

function mm_get_id($genre,$field_val,$field)
{
  //获取模块数据ID
  global $conn, $variable;
  ii_conn_init();
  $tgenre = $genre;
  $tdatabase = $variable[ii_cvgenre($tgenre) . '.ndatabase'];
  $tidfield = $variable[ii_cvgenre($tgenre) . '.nidfield'];
  $tfpre = $variable[ii_cvgenre($tgenre) . '.nfpre'];
  $tmpstr = '';
  $tsqlstr = 'select * from '. $tdatabase.' where '.ii_cfnames($tfpre,$field).' = "' .$field_val .'"';
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  return $trs[$tidfield];
}

function mm_ip_map($ip,$type=0) {
  //离线查询IP所属区域信息,国内精确到城市.国外精确到省份.
  ini_set('memory_limit', '1G');
  spl_autoload_register(function ($class)
                        {
                          $class = str_replace("\\","/",$class);
                          if (strpos($class, 'ipip/db') !== FALSE)
                          {
                            require __DIR__.'/ip/'.$class.'.php';
                          }
                        }, true, true);
  $city = new ipip\db\City(__DIR__.'/ip/ipipfree.ipdb');
  if (ii_isnull($ip)) $ip = ii_get_client_ip();
  $ip_array = $city->findMap($ip, 'CN');
  $country = $ip_array['country_name'];
  $region = $ip_array['region_name'];
  $city = $ip_array['city_name'];
  $int = '';
  switch ($type)
  {
    case 0:
      $int = '';
      break;
    case 1:
      $int = '-';
      break;
    case 2:
      $int = '|';
      break;
    case 3:
      $int = '/';
      break;
    case 4:
      $int = '·';
      break;
    default:
      $int = '-';
      break;
  }
  if (ii_isnull($country)) {
    $res = '';
  }elseif (ii_isnull($region)) {
    $res = $country;
  }elseif (ii_isnull($city)) {
    $res = $country .$int .$region;
  }else{
    $res = $country .$int .$region .$int .$city;
  }
  return $res;
}

function deny_mirrored_websites() {
  global $nurls;
  $mirror_url = ii_itake('global.support/global:basic.mirror_url','lng');
  if(!ii_isnull($mirror_url)) $nurls = $mirror_url;
  $currentDomain = ii_get_lrstr($nurls, '.', 'leftr').'." + "'.ii_get_lrstr($nurls, '.', 'right');
  $res = '<img style="display:none" src=" " onerror=\'this.onerror=null;var str1="'.$currentDomain.'";str2="docu"+"ment.loca"+"tion.host";str3=eval(str2);if( str1!=str3 ){ do_action = "loca" + "tion." + "href = loca" + "tion.href" + ".rep" + "lace(docu" +"ment"+".loca"+"tion.ho"+"st," + "\"' . $currentDomain .'\"" + ")";eval(do_action) }\' />';
  return $res;
}

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

function mm_nav_dict($mgroup, $baseurl, $id)
{
  global $conn,$slng, $variable;
  ii_conn_init();
  $tgenre = 'support/dict';
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

function mm_get_mydictary($group,$lng,$fsid)
{
  global $conn, $variable;
  ii_conn_init();
  $tgenre = 'support/dict';
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
    $tarys += mm_get_mydictary($tgroup, $tlng, $trow[$nidfield]);
  }
  return $tarys;
}

function mm_get_dictary($group,$lng)
{
    $tary = mm_get_mydictary($group, $lng, 0);
    $GLOBALS[$tappstr] = $tary;
    return $GLOBALS[$tappstr];
}

function mm_sel_dict($group,$lng,$fsid)
{
  $tary = mm_get_dictary($group,$lng);
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

function mm_get_askary($id = '')
{
  global $conn, $variable, $nurlpre, $nlng;
  $ngenre = 'ask';
  $ndatabase = $variable[ii_cvgenre($ngenre) . '.ndatabase'];
  $nidfield = $variable[ii_cvgenre($ngenre) . '.nidfield'];
  $nfpre = $variable[ii_cvgenre($ngenre) . '.nfpre'];
  $tarys = Array();
  if (!ii_isnull($id)) $tsqlstr = "select * from $ndatabase where " . $nidfield . "='$id' and " . ii_cfnames($nfpre, 'lng') . "='$nlng' and " . ii_cfnames($nfpre, 'hidden') . "=0 order by " . $nidfield . " desc limit 0,20";//只显示当前问题
  else $tsqlstr = "select * from $ndatabase where " . ii_cfnames($nfpre, 'lng') . "='$nlng' and " . ii_cfnames($nfpre, 'hidden') . "=0 order by " . $nidfield . " desc limit 0,20";//只显示最新20个问题
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

function mm_sel_ask_list($id='')
{
  $ngenre = 'ask';
  $tary = mm_get_askary($id);
  if (is_array($tary))
  {
    $tid = ii_get_safecode($id);
    $trestr = ii_itake('global.tpl_config.sys_spsort', 'tpl');
    $option_pre = '';//'<option value="0" selected>'.ii_itake('global.lng_config.unselect', 'lng').'</option>';
    $option_unselected = ii_itake('global.tpl_config.option_unselect', 'tpl');
    $option_selected = ii_itake('global.tpl_config.option_select', 'tpl');
    $tmpstr = '';
    $treturnstr = '';
    foreach ($tary as $key => $val)
    {
      $tgourl = mm_get_field($ngenre,$val['id'],'gourl');
      if (!ii_isnull($tgourl)) continue;//如果问题跳转则不显示在列表
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
?>
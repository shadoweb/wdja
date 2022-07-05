<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
wdja_cms_admin_init();
$nsearch = 'topic,id';
$ncontrol = 'select,delete';

function pp_manage_navigation()
{
  return ii_ireplace('manage.navigation', 'tpl');
}

function wdja_cms_admin_manage_adddisp()
{
  global $conn;
  global $ngenre, $slng;
  global $ndatabase, $nidfield, $nfpre;
  $tbackurl = $_GET['backurl'];
  if (ii_isnull($_POST['genre']) || ii_isnull($_POST['gid'])) mm_client_alert(ii_itake('global.lng_public.sudd', 'lng'), -1);
  $tcontent =ii_left(ii_cstr($_POST['content']), 100000);
  $tcount = 1;
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
    '" . ii_left(ii_cstr($_POST['genre']), 255) . "',
    '" . ii_get_num($_POST['gid']) . "',
    '" . ii_left(ii_cstr($_POST['topic']), 255) . "',
    '" . ii_left(ii_cstr($_POST['url']), 255) . "',
    '" . $tcontent . "',
    '" . $tcount . "',
    '" . ii_get_num($_POST['type']) . "',
    '" . ii_get_num($_POST['state']) . "',
    '".ii_now()."',
    '".ii_now()."',
    '$slng'
    )";
  $trs = ii_conn_query($tsqlstr, $conn);
  if ($trs)
  {
    $upfid = ii_conn_insert_id($conn);
    mm_baidu_push('urls',ii_left(ii_cstr($_POST['genre']), 255),ii_get_num($_POST['gid']));
    wdja_cms_admin_msg(ii_itake('global.lng_public.add_succeed', 'lng'), $tbackurl, 1);
  }
  else wdja_cms_admin_msg(ii_itake('global.lng_public.add_failed', 'lng'), $tbackurl, 1);
}

function wdja_cms_admin_manage_editdisp()
{
  global $conn;
  global $ngenre;
  global $ndatabase, $nidfield, $nfpre;
  $tbackurl = $_GET['backurl'];
  if (ii_isnull($_POST['genre']) || ii_isnull($_POST['gid'])) mm_client_alert(ii_itake('global.lng_public.sudd', 'lng'), -1);
  $tcontent = ii_left(ii_cstr($_POST['content']), 100000);
  $tid = ii_get_num($_GET['id']);
  $tsqlstr = "update $ndatabase set
  " . ii_cfname('topic') . "='" . ii_left(ii_cstr($_POST['topic']), 50) . "',
  " . ii_cfname('url') . "='" . ii_left(ii_cstr($_POST['url']), 150) . "',
  " . ii_cfname('content') . "='$tcontent',
  " . ii_cfname('count') . "='" . ii_get_num($_POST['count']) . "',
  " . ii_cfname('type') . "='" . $_POST['type'] . "',
  " . ii_cfname('state') . "='" . ii_get_num($_POST['state']) . "',
  " . ii_cfname('time') . "='" . ii_get_date(ii_cstr($_POST['time'])) . "',
  " . ii_cfname('update') . "='" . ii_now() . "'
  where $nidfield=$tid";
  $trs = ii_conn_query($tsqlstr, $conn);
  if ($trs)
  {
    $upfid = $tid;
    if ($_POST['type'] == 'update') mm_baidu_push('update',ii_left(ii_cstr($_POST['genre']), 50),ii_left(ii_cstr($_POST['topic']), 50),ii_get_num($_POST['gid']));
    if ($_POST['type'] == 'del') mm_baidu_push('del',ii_left(ii_cstr($_POST['genre']), 50),ii_left(ii_cstr($_POST['topic']), 50),ii_get_num($_POST['gid']));
    wdja_cms_admin_msg(ii_itake('global.lng_public.edit_succeed', 'lng'), $tbackurl, 1);
  }
  else wdja_cms_admin_msg(ii_itake('global.lng_public.edit_failed', 'lng'), $tbackurl, 1);
}

//导出
function wdja_cms_admin_manage_export() {
  header("Content-type: text/html; charset=utf-8"); 
  set_time_limit(0);
  ini_set('memory_limit','1024M');//设置导出最大内存
  $ranking =array();
  $ranking = wdja_cms_admin_manage_getAll();
  //输出的表头
  $_pre    = array(
    "bid"           =>  "ID",
    "b_genre"       =>  "模块",
    "b_topic"       =>  "标题",
    "b_url"        =>  "网址",
    "b_type"       =>  "方式",
    "b_update"     =>  "更新时间",
  );
  $date = date("YmdHis");//日期作为输出文件后缀
  $content = getXLSFromList($_pre,$ranking);//获得输出的表格内容
  header("Content-type:application/vnd.ms-excel;charset=gb2312");//设置导出格式
  header("Content-Disposition:attactment;filename=".$date.".xls");//设置导出文件名
  header("Pragma: no-cache");
  header("Expires: 0");
  echo $content;
  exit;
}
function getXLSFromList($pres,$lists) {
  //header("Content-type: text/html; charset=utf-8"); 
  // 内容太大建议搜索少量再导出
  //    if (count($lists)>=20000)
  //    {
  //        header("Content-Type:text/html;charset=utf-8");
  //        echo "<br/><h1 style='color:red'>Export data is too large, please narrow your search!</h1><br/>";
  //        exit;
  //    }
  $keys=array_keys($pres);//获取表头的键名
  $content='<meta http-equiv="Content-Type" content="text/html; charset=gb2312">';
  $content.="<table border='1'><tr>";
  //输出表头键值
  foreach($pres as $_pre) {
    $val = iconv('utf-8','gb2312',$_pre);
    $content.="<td>$val</td>";
  }
  $content.="</tr>";
  foreach($lists as $_list) {
    $content.= "<tr>";
    foreach($keys as $key) {
      $val = iconv('utf-8','gb2312',$_list[$key]);
      $content.= "<td style='vnd.ms-excel.numberformat:@'>".$val."</td>"; //style样式将导出的内容都设置为文本格式 输出对应键名的键值 即内容
    }
    $content.="</tr>";
  }
  $content.="</table>";
  return $content;
}
function wdja_cms_admin_manage_getAll()
{
  global $conn, $slng;
  global $ngenre;
  global $ndatabase, $nidfield, $nfpre;
  $tmprstr = '';
  $tsqlstr = "select * from $ndatabase where " . ii_cfname('lng') . "='$slng'";
  $tsqlstr .= " order by " . ii_cfname('time') . " desc";
  $trs = ii_conn_query($tsqlstr, $conn);
  $array = array();
  $i=0;
  while($arr=mysqli_fetch_assoc($trs)) {
    $array[$i] = $arr;
    $i++;
  }
  $res = $array;
  return $res;
}
//导出

function wdja_cms_admin_manage_action()
{
  global $ndatabase, $nidfield, $nfpre, $ncontrol;
  switch($_GET['action'])
  {
    case 'add':
      wdja_cms_admin_manage_adddisp();
      break;
    case 'edit':
      wdja_cms_admin_manage_editdisp();
      break;
    case 'delete':
      wdja_cms_admin_deletedisp($ndatabase, $nidfield);
      break;
    case 'export':
      wdja_cms_admin_manage_export();
      break;
    case 'control':
      wdja_cms_admin_controldisp($ndatabase, $nidfield, $nfpre, $ncontrol);
      break;
    case 'upload':
      uu_upload_files();
      break;
  }
}

function wdja_cms_admin_manage_view()
{
  global $conn;
  global $ndatabase, $nidfield, $nfpre, $nupsimg, $nupsimgs;
  $tid = ii_get_num($_GET['id']);
  $tsqlstr = "select * from $ndatabase where $nidfield=$tid";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs)
  {
    $tmpstr = ii_itake('manage.view', 'tpl');
    foreach ($trs as $key => $val)
    {
      $tkey = ii_get_lrstr($key, '_', 'rightr');
      $GLOBALS['RS_' . $tkey] = $val;
      $tmpstr = str_replace('{$genre}', ii_itake('global.'.ii_htmlencode($trs[ii_cfname('genre')]).':module.channel_title', 'lng'), $tmpstr);
      if ($tkey == 'type') $tmpstr = str_replace('{$type}', ii_itake('sel_type.'.ii_htmlencode($trs[ii_cfname('type')]), 'lng'), $tmpstr);
      else $tmpstr = str_replace('{$' . $tkey . '}', ii_htmlencode($val), $tmpstr);
    }
    $tmpstr = str_replace('{$id}', $trs[$nidfield], $tmpstr);
    $tmpstr = str_replace('{$list_data}', wdja_cms_admin_manage_list_data($trs[$nidfield]), $tmpstr);
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
  }
  else mm_client_alert(ii_itake('global.lng_public.sudd', 'lng'), -1);
}
function wdja_cms_admin_manage_add()
{
  global $nupsimg, $nupsimgs;
  $tmpstr = ii_itake('manage.add', 'tpl');
  $tmpstr = str_replace('{$upsimg}', $nupsimg, $tmpstr);
  $tmpstr = str_replace('{$upsimgs}', $nupsimgs, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function wdja_cms_admin_manage_edit()
{
  global $conn;
  global $ndatabase, $nidfield, $nfpre, $nupsimg, $nupsimgs;
  $tid = ii_get_num($_GET['id']);
  $tsqlstr = "select * from $ndatabase where $nidfield=$tid";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs)
  {
    $tmpstr = ii_itake('manage.edit', 'tpl');
    foreach ($trs as $key => $val)
    {
      $tkey = ii_get_lrstr($key, '_', 'rightr');
      $GLOBALS['RS_' . $tkey] = $val;
      $tmpstr = str_replace('{$' . $tkey . '}', ii_htmlencode($val), $tmpstr);
    }
    $tmpstr = str_replace('{$id}', $trs[$nidfield], $tmpstr);
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
  }
  else mm_client_alert(ii_itake('global.lng_public.sudd', 'lng'), -1);
}

function wdja_cms_admin_manage_list()
{
  global $conn, $slng;
  global $ngenre, $nclstype, $npagesize, $nlisttopx;
  global $ndatabase, $nidfield, $nfpre;
  $toffset = ii_get_num($_GET['offset']);
  $search_field = ii_get_safecode($_GET['field']);
  $search_keyword = ii_get_safecode($_GET['keyword']);
  $tmpstr = ii_itake('manage.list', 'tpl');
  $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_ida}');
  $tmprstr = '';
  $tsqlstr = "select * from $ndatabase where " . ii_cfname('lng') . "='$slng'";
  if ($search_field == 'topic') $tsqlstr .= " and " . ii_cfname('topic') . " like '%" . $search_keyword . "%'";
  if ($search_field == 'good') $tsqlstr .= " and " . ii_cfname('good') . "=" . ii_get_num($search_keyword);
  if ($search_field == 'hidden') $tsqlstr .= " and " . ii_cfname('hidden') . "=" . ii_get_num($search_keyword);
  if ($search_field == 'id') $tsqlstr .= " and $nidfield=" . ii_get_num($search_keyword);
  $tsqlstr .= " order by " . ii_cfname('time') . " desc";
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
  if (!(ii_isnull($search_keyword)) && $search_field == 'topic') $font_red = ii_itake('global.tpl_config.font_red', 'tpl');
  if (is_array($trsary))
  {
    foreach($trsary as $trs)
    {
      $ttopic = ii_htmlencode($trs[ii_cfname('topic')]);
      if (isset($font_red))
      {
        $font_red = str_replace('{$explain}', $search_keyword, $font_red);
        $ttopic = str_replace($search_keyword, $font_red, $ttopic);
      }
      if ($trs[ii_cfname('hidden')] == 1) $ttopic = str_replace('{$explain}', $ttopic, $font_disabled);
      if ($trs[ii_cfname('good')] == 1) $ttopic .= $postfix_good;
      $tmptstr = str_replace('{$topic}', $ttopic, $tmpastr);
      $tmptstr = str_replace('{$topicstr}', ii_encode_scripts(ii_htmlencode($trs[ii_cfname('topic')])), $tmptstr);
      $tmptstr = str_replace('{$genre}', ii_itake('global.'.ii_htmlencode($trs[ii_cfname('genre')]).':module.channel_title', 'lng'), $tmptstr);
      $tmptstr = str_replace('{$url}', ii_htmlencode($trs[ii_cfname('url')]), $tmptstr);
      $tmptstr = str_replace('{$content}', ii_htmlencode($trs[ii_cfname('content')]), $tmptstr);
      $tmptstr = str_replace('{$count}', ii_htmlencode($trs[ii_cfname('count')]), $tmptstr);
      $tmptstr = str_replace('{$type}', ii_itake('sel_type.'.ii_htmlencode($trs[ii_cfname('type')]), 'lng'), $tmptstr);
      $tmptstr = str_replace('{$state}', ii_itake('sel_state.'.ii_htmlencode($trs[ii_cfname('state')]), 'sel'), $tmptstr);
      $tmptstr = str_replace('{$time}', ii_get_date($trs[ii_cfname('time')]), $tmptstr);
      $tmptstr = str_replace('{$update}', ii_get_date($trs[ii_cfname('update')]), $tmptstr);
      $tmptstr = str_replace('{$id}', ii_get_num($trs[$nidfield]), $tmptstr);
      $tmprstr .= $tmptstr;
    }
  }
  $tmpstr = str_replace('{$cpagestr}', $tcp -> get_pagenum(), $tmpstr);
  $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function wdja_cms_admin_manage_list_data($bid)
{
  global $conn, $slng;
  global $ngenre, $nclstype, $npagesize, $nlisttopx;
  global $ndatabase_data, $nidfield_data, $nfpre_data;
  $toffset = ii_get_num($_GET['offset']);
  $tmpstr = ii_itake('manage.data', 'tpl');
  $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_ida}');
  $tmprstr = '';
  $tsqlstr = "select * from $ndatabase_data where " . ii_cfnames($nfpre_data,'lng') . "='$slng' and  ". ii_cfnames($nfpre_data,'bid') . "='$bid'";
  $tsqlstr .= " order by " . ii_cfnames($nfpre_data,'time') . " desc";
  $tcp = new cc_cutepage;
  $tcp -> id = $nidfield_data;
  $tcp -> sqlstr = $tsqlstr;
  $tcp -> offset = $toffset;
  $tcp -> pagesize = $npagesize;
  $tcp -> rslimit = $nlisttopx;
  $tcp -> init();
  $trsary = $tcp -> get_rs_array();
  if (is_array($trsary))
  {
    foreach($trsary as $trs)
    {
      $tmptstr = str_replace('{$bid}', ii_htmlencode($trs[ii_cfnames($nfpre_data,'bid')]), $tmpastr);
      $tmptstr = str_replace('{$order}', ii_htmlencode($trs[ii_cfnames($nfpre_data,'order')]), $tmptstr);
      $tmptstr = str_replace('{$type}', ii_itake('sel_type.'.ii_htmlencode($trs[ii_cfnames($nfpre_data,'type')]), 'lng'), $tmptstr);
      $tmptstr = str_replace('{$state}', ii_itake('sel_state.'.ii_htmlencode($trs[ii_cfnames($nfpre_data,'state')]), 'sel'), $tmptstr);
      $tmptstr = str_replace('{$content}', ii_htmlencode($trs[ii_cfnames($nfpre_data,'content')]), $tmptstr);
      $tmptstr = str_replace('{$time}', ii_get_date($trs[ii_cfnames($nfpre_data,'time')]), $tmptstr);
      $tmptstr = str_replace('{$id}', ii_get_num($trs[$nidfield_data]), $tmptstr);
      $tmprstr .= $tmptstr;
    }
  }
  $tmpstr = str_replace('{$cpagestr}', $tcp -> get_pagenum(), $tmpstr);
  $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function wdja_cms_admin_manage()
{
  switch($_GET['type'])
  {
    case 'add':
      return wdja_cms_admin_manage_add();
      break;
    case 'edit':
      return wdja_cms_admin_manage_edit();
      break;
    case 'view':
      return wdja_cms_admin_manage_view();
      break;
    case 'list':
      return wdja_cms_admin_manage_list();
      break;
    case 'upload':
      uu_upload_files_html('upload_html');
      break;
    default:
      return wdja_cms_admin_manage_list();
      break;
  }
}
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
?>
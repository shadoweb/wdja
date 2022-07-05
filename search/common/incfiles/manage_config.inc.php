<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
wdja_cms_admin_init();
$nsearch = 'search';
$ncontrol = 'select,delete';

function pp_manage_navigation()
{
  return ii_ireplace('manage.navigation', 'tpl');
}

function pp_manage_batch_menu()
{
  return ii_ireplace('manage.batch_menu', 'tpl');
}

function wdja_cms_interface_check_topic()
{
  global $ngenre;
  $bool = false;
  $tid = ii_get_safecode($_GET['id']);
  $ttopic = ii_get_safecode($_GET['topic']);
  if (!ii_isnull($tid)) $bool = mm_search_field($ngenre,$ttopic,'topic',$tid);
  else $bool = mm_search_field($ngenre,$ttopic,'topic');
  if ($bool) echo '1';
  else echo '0';
  exit;
}

//导出
function wdja_cms_admin_manage_export() {
  header("Content-type: text/html; charset=utf-8"); 
  set_time_limit(0);
  ini_set('memory_limit','1024M');//设置导出最大内存
  $ranking =array();
  $ranking = wdja_cms_admin_manage_getAll();
  $nary = array();
  $narys = array();
  foreach($ranking as $ary){
      $nary = $ary;
      foreach($ary as $k => $v){
          if($k == 's_ip') $nary['s_area'] = mm_ip_map($v);
      }
      array_push($narys,$nary);
  }
  //输出的表头
  $_pre    = array(
    "sid"           =>  "ID",
    "s_topic"       =>  "搜索词",
    "s_count"        =>  "搜索次数",
    "s_area"       =>  "国家地区",
    "s_ip"       =>  "IP地址",
    "s_time"     =>  "搜索时间",
  );
  $date = date("YmdHis");//日期作为输出文件后缀
  $content = getXLSFromList($_pre,$narys);//获得输出的表格内容
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
    case 'delete':
      wdja_cms_admin_deletedisp($ndatabase, $nidfield);
      break;
    case 'control':
      wdja_cms_admin_controldisp($ndatabase, $nidfield, $nfpre, $ncontrol);
      break;
    case 'batch_shift':
      wdja_cms_admin_batch_shiftdisp($ndatabase, $nidfield, $nfpre);
      break;
    case 'batch_delete':
      wdja_cms_admin_batch_deletedisp($ndatabase, $nidfield, $nfpre);
      break;
    case 'export':
      wdja_cms_admin_manage_export();
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
      $tmpstr = str_replace('{$' . $tkey . '}', mm_encode_content($val), $tmpstr);
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
  $tsqlstr = "select * from $ndatabase where 1=1";
  if ($search_field == 'search') $tsqlstr .= " and $ndatabase." . ii_cfname('topic') . " like '%" . $search_keyword . "%'";
  $tsqlstr .= " order by $ndatabase." . ii_cfname('time') . " desc";
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
  if (!(ii_isnull($search_keyword)) && $search_field == 'search') $font_red = ii_itake('global.tpl_config.font_red', 'tpl');
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
      $tmptstr = str_replace('{$ip}', $trs[ii_cfname('ip')].'('.mm_ip_map($trs[ii_cfname('ip')]).')', $tmptstr);
      $tmptstr = str_replace('{$content}', mm_encode_content($trs[ii_cfname('content')]), $tmptstr);
      $tmptstr = str_replace('{$infos}', ii_htmlencode($trs[ii_cfname('infos')]), $tmptstr);
      $tmptstr = str_replace('{$count}', ii_get_num($trs[ii_cfname('count')]), $tmptstr);
      $tmptstr = str_replace('{$time}', ii_get_date($trs[ii_cfname('time')]), $tmptstr);
      $tmptstr = str_replace('{$id}', ii_get_num($trs[$nidfield]), $tmptstr);
      $tmprstr .= $tmptstr;
    }
  }
  $tmpstr = str_replace('{$cpagestr}', $tcp -> get_pagenum(), $tmpstr);
  $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function wdja_cms_admin_manage_batch_shift()
{
  $tmpstr = ii_ireplace('manage.batch_shift', 'tpl');
  return $tmpstr;
}

function wdja_cms_admin_manage_batch_delete()
{
  $tmpstr = ii_ireplace('manage.batch_delete', 'tpl');
  return $tmpstr;
}

function wdja_cms_admin_manage_displace()
{
  switch($_GET['mtype'])
  {
    case 'batch_shift':
      return wdja_cms_admin_manage_batch_shift();
      break;
    case 'batch_delete':
      return wdja_cms_admin_manage_batch_delete();
      break;
    default:
      return wdja_cms_admin_manage_batch_shift();
      break;
  }
}

function wdja_cms_admin_manage()
{
  switch($_GET['type'])
  {
    case 'check_topic':
      return wdja_cms_interface_check_topic();
      break;
    case 'view':
      return wdja_cms_admin_manage_view();
      break;
    case 'list':
      return wdja_cms_admin_manage_list();
      break;
    case 'displace':
      return wdja_cms_admin_manage_displace();
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
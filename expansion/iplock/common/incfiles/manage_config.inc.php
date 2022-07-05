<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
wdja_cms_admin_init();
$nsearch = 'ip,content';
$ncontrol = 'select,lock,out,delete';

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
  $tckstr = 'ip:' . ii_itake('manage.ip', 'lng');
  $tary = explode(',', $tckstr);
  $Err = array();
  foreach ($tary as $val)
  {
    $tvalary = explode(':', $val);
    if (ii_isnull($_POST[$tvalary[0]])) $Err[count($Err)] = str_replace('[]', '[' . $tvalary[1] . ']', ii_itake('global.lng_error.insert_empty', 'lng'));
  }
  if (is_array($Err) && count($Err) > 0) wdja_cms_admin_msg($Err[0], $tbackurl, 1);
  $tsqlstr = "insert into $ndatabase (
  " . ii_cfname('area') . ",
  " . ii_cfname('robots') . ",
  " . ii_cfname('ip') . ",
  " . ii_cfname('content') . ",
  " . ii_cfname('lock') . ",
  " . ii_cfname('out') . ",
  " . ii_cfname('time') . ",
  " . ii_cfname('update') . ",
  " . ii_cfname('count') . ",
  " . ii_cfname('lng') . "
  ) values (
  '" . ii_left(ii_cstr($_POST['area']), 50) . "',
  '" . ii_left(ii_cstr($_POST['robots']), 50) . "',
  '" . ii_left(ii_cstr($_POST['ip']), 150) . "',
  '" . ii_left(ii_cstr($_POST['content']), 250) . "',
  " . ii_get_num($_POST['lock']) . ",
  " . ii_get_num($_POST['out']) . ",
  '" . ii_now() . "',
  '" . ii_now() . "',
  " . ii_get_num($_POST['count']) . ",
  '$slng'
  )";
  $trs = ii_conn_query($tsqlstr, $conn);
  if ($trs)
  {
    wdja_cms_admin_msg(ii_itake('global.lng_public.add_succeed', 'lng'), $tbackurl, 1);
  }
  else wdja_cms_admin_msg(ii_itake('global.lng_public.add_failed', 'lng'), $tbackurl, 1);
}

function wdja_cms_admin_manage_editdisp()
{
  global $conn;
  global $ngenre;
  global $ndatabase, $nidfield, $nfpre, $nsaveimages;
  $tbackurl = $_GET['backurl'];
  $tid = ii_get_num($_GET['id']);
  $tckstr = 'ip:' . ii_itake('manage.ip', 'lng');
  $tary = explode(',', $tckstr);
  $Err = array();
  foreach ($tary as $val)
  {
    $tvalary = explode(':', $val);
    if (ii_isnull($_POST[$tvalary[0]])) $Err[count($Err)] = str_replace('[]', '[' . $tvalary[1] . ']', ii_itake('global.lng_error.insert_empty', 'lng'));
  }
  if (is_array($Err) && count($Err) > 0) wdja_cms_admin_msg($Err[0], $tbackurl, 1);
  $tsqlstr = "update $ndatabase set
  " . ii_cfname('area') . "='" . ii_left(ii_cstr($_POST['area']), 50) . "',
  " . ii_cfname('robots') . "='" . ii_left(ii_cstr($_POST['robots']), 50) . "',
  " . ii_cfname('ip') . "='" . ii_left(ii_cstr($_POST['ip']), 150) . "',
  " . ii_cfname('content') . "='" . ii_left(ii_cstr($_POST['content']), 250) . "',
  " . ii_cfname('lock') . "=" . ii_get_num($_POST['lock']) . ",
  " . ii_cfname('out') . "=" . ii_get_num($_POST['out']) . ",
  " . ii_cfname('time') . "='" . ii_get_date(ii_cstr($_POST['time'])) . "',
  " . ii_cfname('update') . "='" . ii_now() . "',
  " . ii_cfname('count') . "=" . ii_get_num($_POST['count']) . "
  where $nidfield=$tid";
  $trs = ii_conn_query($tsqlstr, $conn);
  if ($trs)
  {
    wdja_cms_admin_msg(ii_itake('global.lng_public.edit_succeed', 'lng'), $tbackurl, 1);
  }
  else wdja_cms_admin_msg(ii_itake('global.lng_public.edit_failed', 'lng'), $tbackurl, 1);
}


function pp_add($ip,$robots,$lock,$out)
{
  global $ngenre,$slng;
  global $conn;
  global $ndatabase, $nidfield, $nfpre;
  if (!ii_isnull($ip))
  {
   if (mm_search_field($ngenre,trim($ip),'ip')) return;//已添加
    $tsqlstr = "insert into $ndatabase (
    " . ii_cfname('ip') . ",
    " . ii_cfname('robots') . ",
    " . ii_cfname('lock') . ",
    " . ii_cfname('out') . ",
    " . ii_cfname('time') . ",
    " . ii_cfname('update') . ",
    " . ii_cfname('lng') . "
    ) values (
    '" . trim($ip) . "',
    '$robots',
    ".ii_get_num($lock).",
    ".ii_get_num($out).",
    '" . ii_now() . "',
    '" . ii_now() . "',
    '$slng'
    )";
    $trs = ii_conn_query($tsqlstr, $conn);
    if ($trs) $res = '1';//添加成功
    else $res = '2';//添加失败
  }
  else
  {
    $res='3';//IP为空
  }
  $res ='<tr><td>'.$ip .'</td><td>'.$res.'</td></tr>';
  return $res;
}


function wdja_cms_admin_manage_sqldisp() {
    $time = explode(' ',microtime());
    $start = $time[0] + $time[1];
    $res = '';
    $robots=$_POST['robots'];
    $lock=$_POST['lock'];
    $out=$_POST['out'];
    $ips=explode("\r\n", trim($_POST['ips']));
    foreach($ips as $k=>$v) {
    if (!empty($v) && $v != ' ' && $v != NULL) $res .= pp_add($v,$robots,$lock,$out);
    }
    $endtime = explode(' ',microtime());
    $end = $endtime[0] + $endtime[1];
    $tres = '<table border="1" cellspacing="0"><tr><td>IP</td><td>状态</td></tr>';
    $tres .= $res;
    $tres = '</table><hr>程序运行时间: '.($end - $start);
    return $tres;
}

function wdja_cms_admin_manage_action()
{
  global $ndatabase, $nidfield, $nfpre, $ncontrol;
  switch($_GET['action'])
  {
    case 'add':
      wdja_cms_admin_manage_adddisp();
      break;
    case 'sql':
      wdja_cms_admin_manage_sqldisp();
      break;
    case 'edit':
      wdja_cms_admin_manage_editdisp();
      break;
    case 'delete':
      wdja_cms_admin_deletedisp($ndatabase, $nidfield);
      break;
    case 'control':
      wdja_cms_admin_controldisp($ndatabase, $nidfield, $nfpre, $ncontrol);
      break;
  }
}

function wdja_cms_admin_manage_sql()
{
  $tmpstr = ii_itake('manage.sql', 'tpl');
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function wdja_cms_admin_manage_add()
{
  global $nupsimg, $nupsimgs;
  $tmpstr = ii_itake('manage.add', 'tpl');
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
      if ($tkey == 'robots' &&ii_isnull($val)) $val = 'unknown';
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
  $tsqlstr = "select * from $ndatabase where 1=1 ";
  if ($search_field == 'ip') $tsqlstr .= " and " . ii_cfname('ip') . " like '%" . $search_keyword . "%'";
  if ($search_field == 'default') $tsqlstr .= " and " . ii_cfname('lock') . " = '" . $search_keyword . "' and " . ii_cfname('out') . " = '" . $search_keyword . "'";
  if ($search_field == 'lock') $tsqlstr .= " and " . ii_cfname('lock') . " = '" . $search_keyword . "' and " . ii_cfname('out') . " <> '1'";
  if ($search_field == 'out') $tsqlstr .= " and " . ii_cfname('out') . " = '" . $search_keyword . "'";
  if ($search_field == 'content') $tsqlstr .= " and " . ii_cfname('content') . " like '%" . $search_keyword . "%'";
  if ($search_field == 'robots') {
    switch($search_keyword)
    {
    case '百度':
      $tsqlstr .= " and " . ii_cfname('robots') . " = 'Baiduspider'";
      break;
    case '360':
      $tsqlstr .= " and " . ii_cfname('robots') . " = '360Spider'";
      break;
    case '谷歌':
      $tsqlstr .= " and " . ii_cfname('robots') . " = 'Googlebot'";
      break;
    case '神马':
      $tsqlstr .= " and " . ii_cfname('robots') . " = 'YisouSpider'";
      break;
    case '必应':
      $tsqlstr .= " and " . ii_cfname('robots') . " = 'bingbot'";
      break;
    case '搜狗':
      $tsqlstr .= " and " . ii_cfname('robots') . " = 'Sogou'";
      break;
    case '管理员':
      $tsqlstr .= " and " . ii_cfname('robots') . " = 'admin'";
      break;
  }
  }
  $tsqlstr .= " order by " . ii_cfname('update') . " desc";
  $tcp = new cc_cutepage;
  $tcp -> id = $nidfield;
  $tcp -> sqlstr = $tsqlstr;
  $tcp -> offset = $toffset;
  $tcp -> pagesize = $npagesize;
  $tcp -> rslimit = $nlisttopx;
  $tcp -> init();
  $trsary = $tcp -> get_rs_array();
  if (!(ii_isnull($search_keyword))) $font_red = ii_itake('global.tpl_config.font_red', 'tpl');
  if (is_array($trsary))
  {
    foreach($trsary as $trs)
    {
      $tip = ii_htmlencode($trs[ii_cfname('ip')]);
      $tcontent = ii_htmlencode($trs[ii_cfname('content')]);
      if (isset($font_red))
      {
        $font_red = str_replace('{$explain}', $search_keyword, $font_red);
        $tip = str_replace($search_keyword, $font_red, $tip);
        $tcontent = str_replace($search_keyword, $font_red, $tcontent);
      }
      $tarea = ii_htmlencode($trs[ii_cfname('area')]);
      if (ii_isnull($trs[ii_cfname('robots')])) $trobots = 'unknown';
      else $trobots = ii_htmlencode($trs[ii_cfname('robots')]);
      $tmptstr = str_replace('{$area}', $tarea, $tmpastr);
      $tmptstr = str_replace('{$robots}', ii_itake('sel_robots.'.$trobots, 'sel'), $tmptstr);
      $tmptstr = str_replace('{$ip}', $tip, $tmptstr);
      $tmptstr = str_replace('{$come}', $trs[ii_cfname('come')], $tmptstr);
      $tmptstr = str_replace('{$content}', $tcontent, $tmptstr);
      $tmptstr = str_replace('{$count}', $trs[ii_cfname('count')], $tmptstr);
      $tmptstr = str_replace('{$lock}', $trs[ii_cfname('lock')], $tmptstr);
      $tmptstr = str_replace('{$out}', $trs[ii_cfname('out')], $tmptstr);
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

function wdja_cms_admin_manage()
{
  switch($_GET['type'])
  {
    case 'sql':
      return wdja_cms_admin_manage_sql();
      break;
    case 'add':
      return wdja_cms_admin_manage_add();
      break;
    case 'edit':
      return wdja_cms_admin_manage_edit();
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

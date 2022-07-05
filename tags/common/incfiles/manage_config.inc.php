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

function wdja_cms_admin_manage_adddisp()
{
  global $ngenre, $slng;
  global $conn;
  global $ndatabase, $nidfield, $nfpre, $nsaveimages;
  $tbackurl = $_GET['backurl'];
  $tckstr = 'topic:' . ii_itake('global.lng_config.topic', 'lng');
  $tary = explode(',', $tckstr);
  $Err = array();
  foreach ($tary as $val)
  {
    $tvalary = explode(':', $val);
    if (ii_isnull($_POST[$tvalary[0]])) $Err[count($Err)] = str_replace('[]', '[' . $tvalary[1] . ']', ii_itake('global.lng_error.insert_empty', 'lng'));
  }
  if (is_array($Err) && count($Err) > 0) wdja_cms_admin_msg($Err[0], $tbackurl, 1);
  if ($nsaveimages == '1') $tcontent = ii_left(ii_cstr(saveimages($_POST['content'])), 100000);
  else $tcontent =ii_left(ii_cstr($_POST['content']), 100000);
  $tcontent_atts_list = ii_left(ii_cstr($_POST['content_atts_list']), 10000);
  if (mm_search_field($ngenre,$_POST['topic'],'topic')) wdja_cms_admin_msg(ii_itake('manage.check', 'lng'), $tbackurl, 1);
    $tsqlstr = "insert into $ndatabase (
    " . ii_cfname('topic') . ",
    " . ii_cfname('gourl') . ",
    " . ii_cfname('titles') . ",
    " . ii_cfname('keywords') . ",
    " . ii_cfname('description') . ",
    " . ii_cfname('content') . ",
    " . ii_cfname('content_atts_list') . ",
    " . ii_cfname('time') . ",
    " . ii_cfname('update') . ",
    " . ii_cfname('lng') . "
    ) values (
    '" . ii_left(ii_cstr($_POST['topic']), 50) . "',
    '" . ii_left(ii_cstr($_POST['gourl']), 250) . "',
    '" . ii_left(ii_cstr($_POST['titles']), 250) . "',
    '" . ii_left(ii_cstr($_POST['keywords']), 150) . "',
    '" . ii_left(ii_cstr($_POST['description']), 250) . "',
    '$tcontent',
    '$tcontent_atts_list',
    '" . ii_now() . "',
    '" . ii_now() . "',
    '$slng'
    )";
    $trs = ii_conn_query($tsqlstr, $conn);
    if ($trs)
    {
      $upfid = ii_conn_insert_id($conn);
      uu_upload_update_database_note($ngenre, $tcontent_atts_list, 'content_atts', $upfid);
      wdja_cms_admin_msg(ii_itake('global.lng_public.add_succeed', 'lng'), $tbackurl, 1);
    }
    else wdja_cms_admin_msg(ii_itake('global.lng_public.add_failed', 'lng'), $tbackurl, 1);
}

function wdja_cms_admin_manage_editdisp()
{
  global $ngenre;
  global $conn;
  global $ndatabase, $nidfield, $nfpre, $nsaveimages;
  $tbackurl = $_GET['backurl'];
  $tckstr = 'topic:' . ii_itake('global.lng_config.topic', 'lng');
  $tary = explode(',', $tckstr);
  $Err = array();
  foreach ($tary as $val)
  {
    $tvalary = explode(':', $val);
    if (ii_isnull($_POST[$tvalary[0]])) $Err[count($Err)] = str_replace('[]', '[' . $tvalary[1] . ']', ii_itake('global.lng_error.insert_empty', 'lng'));
  }
  if (is_array($Err) && count($Err) > 0) wdja_cms_admin_msg($Err[0], $tbackurl, 1);
  if ($nsaveimages == '1') $tcontent = ii_left(ii_cstr(saveimages($_POST['content'])), 100000);
  else $tcontent = ii_left(ii_cstr($_POST['content']), 100000);
  $tcontent_atts_list = ii_left(ii_cstr($_POST['content_atts_list']), 10000);
  $tid = ii_get_num($_GET['id']);
  if (mm_search_field($ngenre,$_POST['topic'],'topic',$tid)) wdja_cms_admin_msg(ii_itake('manage.check', 'lng'), $tbackurl, 1);
    $tsqlstr = "update $ndatabase set
    " . ii_cfname('topic') . "='" . ii_left(ii_cstr($_POST['topic']), 50) . "',
    " . ii_cfname('gourl') . "='" . ii_left(ii_cstr($_POST['gourl']), 250) . "',
    " . ii_cfname('titles') . "='" . ii_left(ii_cstr($_POST['titles']), 250) . "',
    " . ii_cfname('keywords') . "='" . ii_left(ii_cstr($_POST['keywords']), 150) . "',
    " . ii_cfname('description') . "='" . ii_left(ii_cstr($_POST['description']), 250) . "',
    " . ii_cfname('content') . "='$tcontent',
    " . ii_cfname('content_atts_list') . "='$tcontent_atts_list',
    " . ii_cfname('count') . "=" . ii_get_num($_POST['count']) . ",
    " . ii_cfname('time') . "='" . ii_get_date(ii_cstr($_POST['time'])) . "',
    " . ii_cfname('update') . "='" . ii_now() . "'
    where $nidfield=$tid";
    $trs = ii_conn_query($tsqlstr, $conn);
    if ($trs)
    {
      $upfid = $tid;
      uu_upload_update_database_note($ngenre, $tcontent_atts_list, 'content_atts', $upfid);
      wdja_cms_admin_msg(ii_itake('global.lng_public.edit_succeed', 'lng'), $tbackurl, 1);
    }
    else wdja_cms_admin_msg(ii_itake('global.lng_public.edit_failed', 'lng'), $tbackurl, 1);
}

function pp_add($k)
{
  global $ngenre, $slng;
  global $conn;
  global $ndatabase, $nidfield, $nfpre, $nsaveimages;
  if ($nsaveimages == '1') $tcontent = ii_left(ii_cstr(saveimages($_POST['content'])), 100000);
  else $tcontent =ii_left(ii_cstr($_POST['content']), 100000);
  $tcontent_atts_list = ii_left(ii_cstr($_POST['content_atts_list']), 10000);
  if (mm_search_field($ngenre,trim($k),'topic')) return;//已添加
    $tsqlstr = "insert into $ndatabase (
    " . ii_cfname('topic') . ",
    " . ii_cfname('time') . ",
    " . ii_cfname('update') . ",
    " . ii_cfname('lng') . "
    ) values (
    '" . ii_left(ii_cstr(trim($k)), 50) . "',
    '" . ii_now() . "',
    '" . ii_now() . "',
    '$slng'
    )";
    $trs = ii_conn_query($tsqlstr, $conn);
    if ($trs) $res = '1';//添加成功
    else $res = '2';//添加失败
  $res ='<tr><td>'.$k .'</td><td>'.$res.'</td></tr>';
  return $res;
}


function wdja_cms_admin_manage_sqldisp() {
    $time = explode(' ',microtime());
    $start = $time[0] + $time[1];
    $res = '';
    $keywords=explode("\r\n", trim($_POST['keywords']));
    foreach($keywords as $k=>$v) {
    if (!empty($v) && $v != ' ' && $v != NULL) $res .= pp_add($v);
    }
    $endtime = explode(' ',microtime());
    $end = $endtime[0] + $endtime[1];
    $tres = '<table border="1" cellspacing="0"><tr><td>标签</td><td>状态</td></tr>';
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
    case 'batch_shift':
      wdja_cms_admin_batch_shiftdisp($ndatabase, $nidfield, $nfpre);
      break;
    case 'batch_delete':
      wdja_cms_admin_batch_deletedisp($ndatabase, $nidfield, $nfpre);
      break;
    case 'upload':
      uu_upload_files();
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
    $tmpstr = str_replace('{$upsimg}', $nupsimg, $tmpstr);
    $tmpstr = str_replace('{$upsimgs}', $nupsimgs, $tmpstr);
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
  $tsqlstr = "select * from $ndatabase where " . ii_cfname('lng') . "= '" . $slng . "'";
  if ($search_field == 'topic') $tsqlstr .= " and $ndatabase." . ii_cfname('topic') . " like '%" . $search_keyword . "%'";
  if ($search_field == 'id') $tsqlstr .= " and $ndatabase.$nidfield=" . ii_get_num($search_keyword);
  $tsqlstr .= " order by $nidfield desc";
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
      global $variable,$nurltype,$ncreatefiletype;
      $tgourl = mm_get_field($ngenre,$trs[$nidfield],'gourl');
      if (!ii_isnull($tgourl)) $turl = $tgourl;
      else $turl = '/'.$ngenre.'/'.ii_iurl('detail',$trs[$nidfield], $nurltype);
      $tmptstr = str_replace('{$topic}', $ttopic, $tmpastr);
      $tmptstr = str_replace('{$topicstr}', ii_encode_scripts(ii_htmlencode($trs[ii_cfname('topic')])), $tmptstr);
      $tmptstr = str_replace('{$url}', $turl, $tmptstr);
      $tmptstr = str_replace('{$gourl}', ii_htmlencode($trs[ii_cfname('gourl')]), $tmptstr);
      $tmptstr = str_replace('{$time}', ii_get_date($trs[ii_cfname('time')]), $tmptstr);
      $tmptstr = str_replace('{$count}', ii_get_num($trs[ii_cfname('count')]), $tmptstr);
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
    case 'add':
      return wdja_cms_admin_manage_add();
      break;
    case 'sql':
      return wdja_cms_admin_manage_sql();
      break;
    case 'edit':
      return wdja_cms_admin_manage_edit();
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
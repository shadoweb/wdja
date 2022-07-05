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

function wdja_cms_admin_manage_editdisp()
{
  global $ngenre;
  global $conn;
  global $ndatabase, $nidfield, $nfpre, $nsaveimages;
  $tbackurl = $_GET['backurl'];
  $tid = ii_get_num($_GET['id']);
  $switch = ii_get_num($_POST['timer_switch']);
  $event = ii_get_num($_POST['event']);//0发布,1隐藏
  $timer = ii_get_date(ii_cstr($_POST['timer']));
  $state = 0;
  if ($switch == 0) $state = 2;//state: 0进行中,1已完成,2已关闭
  if (ii_check_expireDate($timer) && $switch != 0) {//当前定时已到期
      $switch = 0;//开关更改为已关闭
      $state = 1;//状态更改为已完成
      $genre = mm_get_field($ngenre,$tid,'genre');
      $gid = mm_get_field($ngenre,$tid,'gid');
      if ($event == 0 ) mm_update_field($genre,$gid,'hidden',0);
      else mm_update_field($genre,$gid,'hidden',1);
    }
    $tsqlstr = 'update '.$ndatabase.' set
	    ' . ii_cfnames($nfpre,'event') . '="' . $event . '",
	    ' . ii_cfnames($nfpre,'timer_switch') . '="' . $switch . '",
	    ' . ii_cfnames($nfpre,'timer') . '="' . $timer . '",
	    ' . ii_cfnames($nfpre,'state') . '="' . $state . '",
	    ' . ii_cfnames($nfpre,'update') . '="' . ii_now() . '"
    where '.$nidfield.'='.$tid;
    $trs = ii_conn_query($tsqlstr, $conn);
    if ($trs)
    {
      $upfid = $tid;
      wdja_cms_admin_msg(ii_itake('global.lng_public.edit_succeed', 'lng'), $tbackurl, 1);
    }
    else wdja_cms_admin_msg(ii_itake('global.lng_public.edit_failed', 'lng'), $tbackurl, 1);
}

function wdja_cms_admin_manage_action()
{
  global $ndatabase, $nidfield, $nfpre, $ncontrol;
  switch($_GET['action'])
  {
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
  }
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
    $ttimer = ii_get_date(ii_cstr($trs[ii_cfnames($nfpre,"timer")]));
    $timer = ii_format_date($ttimer,4);
    foreach ($trs as $key => $val)
    {
      $tkey = ii_get_lrstr($key, '_', 'rightr');
      $GLOBALS['RS_' . $tkey] = $val;
      if ($tkey == 'timer') $tmpstr = str_replace('{$timer}', $timer, $tmpstr);
      else $tmpstr = str_replace('{$' . $tkey . '}', ii_htmlencode($val), $tmpstr);
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
  $tsqlstr = "select * from $ndatabase where " . ii_cfname('lng') . "= '" . $slng . "'";
  if ($search_field == 'topic') $tsqlstr .= " and $ndatabase." . ii_cfname('topic') . " like '%" . $search_keyword . "%'";
  if ($search_field == 'switch0') $tsqlstr .= " and " . ii_cfname('timer_switch') . "=0";
  if ($search_field == 'switch1') $tsqlstr .= " and " . ii_cfname('timer_switch') . "=1";
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
      $ggenre = ii_htmlencode($trs[ii_cfname('genre')]);
      $gid = ii_get_num($trs[ii_cfname('gid')]);
      $gtopic = mm_get_field($ggenre,$gid,'topic');
      $tevent = ii_get_num($trs[ii_cfname('event')]);
      $tstate = ii_get_num($trs[ii_cfname('state')]);
      $tmptstr = str_replace('{$topic}', $ttopic, $tmpastr);
      $tmptstr = str_replace('{$topicstr}', ii_encode_scripts(ii_htmlencode($trs[ii_cfname('topic')])), $tmptstr);
      $tmptstr = str_replace('{$genre}', ii_itake('global.'.$ggenre.':module.channel_title', 'lng'), $tmptstr);
      $tmptstr = str_replace('{$gid}', $gtopic, $tmptstr);
      $tmptstr = str_replace('{$timer_switch}', ii_get_num($trs[ii_cfname('timer_switch')]), $tmptstr);
      $tmptstr = str_replace('{$event}', ii_itake('global.expansion/timer:sel_event.'.$tevent, 'sel'), $tmptstr);
      $tmptstr = str_replace('{$state}', ii_itake('global.expansion/timer:sel_state.'.$tstate, 'sel'), $tmptstr);
      $tmptstr = str_replace('{$timer}', ii_get_date($trs[ii_cfname('timer')]), $tmptstr);
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
    case 'edit':
      return wdja_cms_admin_manage_edit();
      break;
    case 'list':
      return wdja_cms_admin_manage_list();
      break;
    case 'displace':
      return wdja_cms_admin_manage_displace();
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
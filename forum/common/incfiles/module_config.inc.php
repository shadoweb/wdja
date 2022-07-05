<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
function pp_module_data_top()
{
  return ii_ireplace('module.data_top', 'tpl');
}

function pp_module_data_foot()
{
  return ii_ireplace('module.data_foot', 'tpl');
}

function pp_module_data_manage_menu()
{
  $tsid = ii_get_num($_GET['sid']);
  $tmpstr = ii_ireplace('module.data_manage_menu', 'tpl');
  $tmpstr = str_replace('{$sid}', $tsid, $tmpstr);
  return $tmpstr;
}

function wdja_cms_module_manage_topicdisp()
{
  global $ndatabase, $nidfield, $nfpre;
  global $ncontrol, $forum_isadmin;
  $ncontrol = 'select';
  if ($forum_isadmin == 1) $ncontrol .= ',htop';
  $ncontrol .= ',top,elite,lock,hidden';
  $tsid = ii_get_num($_GET['sid']);
  $tbackurl = $_GET['backurl'];
  $totsql = " and " . ii_cfname('sid') . "=$tsid";
  $tsid = $_POST['sel_ids'];
  $tcontrol = $_POST['control'];
  switch($tcontrol)
  {
    case 'htop':
      if (ii_cinstr($ncontrol, $tcontrol, ',')) $texec = mm_dbase_switch($ndatabase, $nfpre . 'htop', $nidfield, $tsid, $totsql);
      break;
    case 'top':
      if (ii_cinstr($ncontrol, $tcontrol, ',')) $texec = mm_dbase_switch($ndatabase, $nfpre . 'top', $nidfield, $tsid, $totsql);
      break;
    case 'elite':
      if (ii_cinstr($ncontrol, $tcontrol, ',')) $texec = mm_dbase_switch($ndatabase, $nfpre . 'elite', $nidfield, $tsid, $totsql);
      break;
    case 'lock':
      if (ii_cinstr($ncontrol, $tcontrol, ',')) $texec = mm_dbase_switch($ndatabase, $nfpre . 'lock', $nidfield, $tsid, $totsql);
      break;
    case 'hidden':
      if (ii_cinstr($ncontrol, $tcontrol, ',')) $texec = mm_dbase_switch($ndatabase, $nfpre . 'hidden', $nidfield, $tsid, $totsql);
      break;
  }
  @mm_dbase_update($ndatabase, ii_cfname('color'), ii_left(ii_cstr($_POST['color']), 50), $nidfield, $tsid, 1, $totsql);
  @mm_dbase_update($ndatabase, ii_cfname('b'), ii_get_num($_POST['b']), $nidfield, $tsid, 0, $totsql);
  mm_client_redirect($tbackurl);
}

function wdja_cms_module_manage_detaildisp()
{
  global $ndatabase, $nidfield, $nfpre;
  global $ncontrol;
  $ncontrol = 'select,hidden';
  $tsid = ii_get_num($_GET['sid']);
  $tbackurl = $_GET['backurl'];
  $totsql = " and " . ii_cfname('sid') . "=$tsid";
  $tsid = $_POST['sel_ids'];
  $tcontrol = $_POST['control'];
  switch($tcontrol)
  {
    case 'hidden':
      if (ii_cinstr($ncontrol, $tcontrol, ',')) $texec = mm_dbase_switch($ndatabase, $nfpre . 'hidden', $nidfield, $tsid, $totsql);
      break;
  }
  mm_client_redirect($tbackurl);
}

function wdja_cms_module_manage_blacklistdisp()
{
  global $ngenre, $ncontrol;
  $ncontrol = 'select,delete';
  $tdatabase = mm_cndatabase($ngenre, 'blacklist');
  $tidfield = mm_cnidfield($ngenre, 'blacklist');
  $tfpre = mm_cnfpre($ngenre, 'blacklist');
  $tsid = ii_get_num($_GET['sid']);
  $tbackurl = $_GET['backurl'];
  $totsql = " and " . ii_cfnames($tfpre, 'sid') . "=$tsid";
  $tsid = $_POST['sel_ids'];
  $tcontrol = $_POST['control'];
  switch($tcontrol)
  {
    case 'delete':
      if (ii_cinstr($ncontrol, $tcontrol, ',')) $texec = mm_dbase_delete($tdatabase, $tidfield, $tsid, $totsql);
      break;
  }
  mm_client_redirect($tbackurl);
}

function wdja_cms_module_manage_add_blacklistdisp()
{
  global $conn;
  global $ngenre, $nusername;
  $tsid = ii_get_num($_GET['sid']);
  $tbackurl = $_GET['backurl'];
  $tckstr = 'username:' . ii_itake('blacklist.username', 'lng') . ',remark:' . ii_itake('blacklist.remark', 'lng');
  $tary = explode(',', $tckstr);
  $Err = array();
  foreach ($tary as $val)
  {
    $tvalary = explode(':', $val);
    if (ii_isnull($_POST[$tvalary[0]])) mm_imessage(str_replace('[]', '[' . $tvalary[1] . ']', ii_itake('global.lng_error.insert_empty', 'lng')), $tbackurl);
  }
  $tdatabase = mm_cndatabase($ngenre, 'blacklist');
  $tidfield = mm_cnidfield($ngenre, 'blacklist');
  $tfpre = mm_cnfpre($ngenre, 'blacklist');
  $tsqlstr = "insert into $tdatabase (
  " . ii_cfnames($tfpre, 'username') . ",
  " . ii_cfnames($tfpre, 'sid') . ",
  " . ii_cfnames($tfpre, 'admin') . ",
  " . ii_cfnames($tfpre, 'time') . ",
  " . ii_cfnames($tfpre, 'remark') . "
  ) values (
  '" . ii_left(ii_cstr($_POST['username']), 50) . "',
  $tsid,
  '$nusername',
  '" . ii_now() . "',
  '" . ii_left(ii_cstr($_POST['remark']), 255) . "'
  )";
  $trs = ii_conn_query($tsqlstr, $conn);
  if ($trs) mm_client_redirect($tbackurl);
  else mm_imessage(ii_itake('global.lng_public.sudd', 'lng'), $tbackurl);
}

function wdja_cms_module_topic_managedisp()
{
  ap_user_islogin();
  global $nusername, $forum_isadmin;
  $tsid = ii_get_num($_GET['sid']);
  $forum_isadmin = pp_check_forum_isadmin($tsid, $nusername);
  if ($forum_isadmin == 0) mm_imessage(ii_itake('config.admininfo', 'lng'), -1);
  switch($_GET['mtype'])
  {
    case 'topic':
      wdja_cms_module_manage_topicdisp();
      break;
    case 'detail':
      wdja_cms_module_manage_detaildisp();
      break;
    case 'blacklist':
      wdja_cms_module_manage_blacklistdisp();
      break;
    case 'add_blacklist':
      wdja_cms_module_manage_add_blacklistdisp();
      break;
  }
}

function wdja_cms_module_topic_releasedisp()
{
  global $ctype;
  $ctype = 'release';
  $Err = array();
  global $conn;
  global $ngenre, $nusername, $nmax_vote_option;
  ap_user_islogin(ii_get_actual_route($ngenre));
  global $nint_topic, $nuser_upload, $nnew_user_release_timeout;
  global $ndatabase, $nidfield, $nfpre;
  $tUserRegTime = ii_get_date(ap_get_userinfo('time', $nusername));
  if (ii_datediff('s', $tUserRegTime, ii_now()) <= $nnew_user_release_timeout) mm_imessage(ii_ireplace('module.new_user_release_timeout', 'lng'), -1);
  $tsid = ii_get_num($_GET['sid']);
  if (!mm_ck_valcode()) $Err[count($Err)] = ii_itake('global.lng_error.valcode', 'lng');
  $tforum_popedom = pp_check_forum_popedom($tsid, 1);
  if ($tforum_popedom == -1) $Err[count($Err)] = ii_itake('module.pdm_none', 'lng');
  if ($tforum_popedom == 1) $Err[count($Err)] = ii_itake('module.pdm_type', 'lng');
  if ($tforum_popedom == 2) $Err[count($Err)] = ii_itake('module.pdm_mode', 'lng');
  if ($tforum_popedom == 3) $Err[count($Err)] = ii_itake('module.popedom', 'lng');
  if (pp_check_forum_blacklist($tsid, $nusername)) $Err[count($Err)] = ii_itake('module.inblacklist', 'lng');
  $tckstr = 'topic:' . ii_itake('config.topic', 'lng') . ',content:' . ii_itake('config.content', 'lng');
  $tary = explode(',', $tckstr);
  foreach ($tary as $val)
  {
    $tvalary = explode(':', $val);
    if (ii_isnull($_POST[$tvalary[0]])) $Err[count($Err)] = str_replace('[]', '[' . $tvalary[1] . ']', ii_itake('global.lng_error.insert_empty', 'lng'));
  }
  $tvote_type = ii_get_num($_POST['vote_type'], 0);
  $tvote_day = ii_get_num($_POST['vote_day'], -1);
  $tvote_content = ii_left(ii_cstr($_POST['vote_content']), 1000);
  if (!ii_isnull($tvote_content))
  {
    $tvote_content2 = '';
    $tvote_contentary = explode(CRLF, $tvote_content);
    foreach ($tvote_contentary as $key => $val)
    {
      if (!ii_isnull($val)) $tvote_content2 .= $val . CRLF;
    }
    $tvote_contentary2 = explode(CRLF, $tvote_content2);
    if ((count($tvote_contentary2) >= ($nmax_vote_option + 1)) || (count($tvote_contentary2) <= 2)) $Err[count($Err)] = ii_itake('config.voteerror', 'lng');
    if (count($Err) > 0) mm_imessage($Err[0], '-1');
    if (!is_array($Err) || count($Err) == 0)
    {
      $tdatabase = mm_cndatabase($ngenre, 'vote');
      $tidfield = mm_cnidfield($ngenre, 'vote');
      $tfpre = mm_cnfpre($ngenre, 'vote');
      $tsqlstr = "insert into $tdatabase (
      " . ii_cfnames($tfpre, 'topic') . ",
      " . ii_cfnames($tfpre, 'type') . ",
      " . ii_cfnames($tfpre, 'time') . ",
      " . ii_cfnames($tfpre, 'day') . "
      ) values (
      '" . ii_left(ii_cstr($_POST['topic']), 50) . "',
      $tvote_type,
      '" . ii_now() . "',
      $tvote_day
      )";
      $trs = ii_conn_query($tsqlstr, $conn);
      if ($trs)
      {
        $tvoteid = ii_conn_insert_id($conn);
        $tdatabase = mm_cndatabase($ngenre, 'vote_data');
        $tidfield = mm_cnidfield($ngenre, 'vote_data');
        $tfpre = mm_cnfpre($ngenre, 'vote_data');
        $ti = 0;
        foreach ($tvote_contentary2 as $key => $val)
        {
          if (!ii_isnull($val))
          {
            $tsqlstr = "insert into $tdatabase (
            " . ii_cfnames($tfpre, 'topic') . ",
            " . ii_cfnames($tfpre, 'fid') . ",
            " . ii_cfnames($tfpre, 'vid') . "
            ) values (
            '" . ii_left(ii_cstr($val), 50) . "',
            $tvoteid,
            $ti
            )";
            ii_conn_query($tsqlstr, $conn);
            $ti = $ti + 1;
          }
        }
      }
    }
  }
  if (!is_array($Err) || count($Err) == 0)
  {
    if ($nuser_upload == 1) $tcontent_files_list = ii_left(ii_cstr($_POST['content_files_list']), 1000);
    else $tcontent_files_list = '';
    if ($tforum_popedom == 2.5) $thidden = 1;
    else $thidden = 0;
    $tsqlstr = "insert into $ndatabase (
    " . ii_cfname('sid') . ",
    " . ii_cfname('fid') . ",
    " . ii_cfname('icon') . ",
    " . ii_cfname('topic') . ",
    " . ii_cfname('author') . ",
    " . ii_cfname('authorip') . ",
    " . ii_cfname('voteid') . ",
    " . ii_cfname('content') . ",
    " . ii_cfname('content_files_list') . ",
    " . ii_cfname('ubb') . ",
    " . ii_cfname('hidden') . ",
    " . ii_cfname('time') . ",
    " . ii_cfname('lasttime') . "
    ) values (
    $tsid,
    0,
    " . ii_get_num($_POST['icon']) . ",
    '" . ii_left(ii_cstr($_POST['topic']), 50) . "',
    '$nusername',
    '" . ii_get_client_ip() . "',
    " . ii_get_num($tvoteid) . ",
    '" . ii_left(ii_cstr($_POST['content']), 20000) . "',
    '$tcontent_files_list',
    " . ii_get_num($_POST['ubb']) . ",
    $thidden,
    '" . ii_now() . "',
    '" . ii_now() . "'
    )";
    $trs = ii_conn_query($tsqlstr, $conn);
    if ($trs)
    {
      $tid = ii_conn_insert_id($conn);
      if ($nuser_upload == 1) uu_upload_update_database_note($ngenre, $tcontent_files_list, 'content_files', $tid);
      $tdatabase = mm_cndatabase($ngenre, 'sort');
      $tidfield = mm_cnidfield($ngenre, 'sort');
      $tfpre = mm_cnfpre($ngenre, 'sort');
      $tsqlstr = "select * from $tdatabase where $tidfield=$tsid";
      $trs = ii_conn_query($tsqlstr, $conn);
      $trs = ii_conn_fetch_array($trs);
      if ($trs)
      {
        if (ii_format_date($trs[ii_cfnames($tfpre, 'today_date')], 1) == ii_format_date(ii_now(), 1))
        {
          $ttoday_date = ii_get_date($trs[ii_cfnames($tfpre, 'today_date')]);
          $ttoday_ntopic = ii_get_num($trs[ii_cfnames($tfpre, 'today_ntopic')]) + 1;
        }
        else
        {
          $ttoday_date = ii_format_date(ii_now(), 1);
          $ttoday_ntopic = 1;
        }
        $tsqlstr = "update $tdatabase set
        " . ii_cfnames($tfpre, 'ntopic') . "=" . ii_cfnames($tfpre, 'ntopic') . "+1,
        " . ii_cfnames($tfpre, 'nnote') . "=" . ii_cfnames($tfpre, 'nnote') . "+1,
        " . ii_cfnames($tfpre, 'last_topic') . "='" . ii_left(ii_cstr($_POST['topic']), 50) . "',
        " . ii_cfnames($tfpre, 'last_tid') . "=$tid,
        " . ii_cfnames($tfpre, 'last_time') . "='" . ii_now() . "',
        " . ii_cfnames($tfpre, 'today_date') . "='$ttoday_date',
        " . ii_cfnames($tfpre, 'today_ntopic') . "=$ttoday_ntopic
        where $tidfield=$tsid";
        ii_conn_query($tsqlstr, $conn);
      }
    }
    ap_update_userproperty('topic', 1, 0, $nusername);
    ap_update_userproperty('integral', $nint_topic, 0, $nusername);
    if ($tforum_popedom != 2.5) header('location: ?type=list&sid=' . $tsid);
    else mm_client_alert(ii_itake('module.newtopic_info1', 'lng'), '?type=list&sid=' . $tsid);
  }
}

function wdja_cms_module_topic_replydisp()
{
  global $ctype;
  $ctype = 'reply';
  $Err = array();
  global $conn;
  global $ngenre, $nusername, $nint_reply;
  global $ndatabase, $nidfield, $nfpre;
  $tbackurl = $_GET['backurl'];
  ap_user_islogin($tbackurl);
  $tUserRegTime = ii_get_date(ap_get_userinfo('time', $nusername));
  if (ii_datediff('s', $tUserRegTime, ii_now()) <= $nnew_user_release_timeout) mm_imessage(ii_ireplace('module.new_user_release_timeout', 'lng'), -1);
  $ttid = ii_get_num($_GET['tid']);
  if (!mm_ck_valcode()) $Err[count($Err)] = ii_itake('global.lng_error.valcode', 'lng');
  $tckstr = 'content:' . ii_itake('config.replycontent', 'lng');
  $tary = explode(',', $tckstr);
  foreach ($tary as $val)
  {
    $tvalary = explode(':', $val);
    if (ii_isnull($_POST[$tvalary[0]])) $Err[count($Err)] = str_replace('[]', '[' . $tvalary[1] . ']', ii_itake('global.lng_error.insert_empty', 'lng'));
  }
  $tsqlstr = "select * from $ndatabase where " . ii_cfname('hidden') . "=0 and " . ii_cfname('fid') . "=0 and $nidfield=$ttid";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs)
  {
    $tsid = ii_get_num($trs[ii_cfname('sid')]);
    if (ii_get_num($trs[ii_cfname('lock')]) == 1) $Err[count($Err)] = ii_itake('config.lockinfo', 'lng');
    if (pp_check_forum_popedom($tsid, 0) != 0) $Err[count($Err)] = ii_itake('module.popedom', 'lng');
    if (pp_check_forum_blacklist($tsid, $nusername)) $Err[count($Err)] = ii_itake('module.inblacklist', 'lng');
  }
  else $Err[count($Err)] = ii_itake('config.notexist', 'lng');
  if (count($Err) > 0) mm_imessage($Err[0], '-1');
  if (!is_array($Err) || count($Err) == 0)
  {
    $tsqlstr = "update $ndatabase set " . ii_cfname('reply') . "=" . ii_cfname('reply') . "+1," . ii_cfname('lasttime') . "='" . ii_now() . "'," . ii_cfname('lastuser') . "='$nusername' where $nidfield=$ttid";
    @ii_conn_query($tsqlstr, $conn);
    $tsqlstr = "insert into $ndatabase (
    " . ii_cfname('sid') . ",
    " . ii_cfname('fid') . ",
    " . ii_cfname('icon') . ",
    " . ii_cfname('topic') . ",
    " . ii_cfname('author') . ",
    " . ii_cfname('authorip') . ",
    " . ii_cfname('content') . ",
    " . ii_cfname('ubb') . ",
    " . ii_cfname('time') . ",
    " . ii_cfname('lasttime') . "
    ) values (
    $tsid,
    $ttid,
    " . ii_get_num($_POST['icon']) . ",
    '" . ii_left(ii_cstr($_POST['topic']), 50) . "',
    '$nusername',
    '" . ii_get_client_ip() . "',
    '" . ii_left(ii_cstr($_POST['content']), 20000) . "',
    " . ii_get_num($_POST['ubb']) . ",
    '" . ii_now() . "',
    '" . ii_now() . "'
    )";
    $trs = ii_conn_query($tsqlstr, $conn);
    if ($trs)
    {
      $tdatabase = mm_cndatabase($ngenre, 'sort');
      $tidfield = mm_cnidfield($ngenre, 'sort');
      $tfpre = mm_cnfpre($ngenre, 'sort');
      $tsqlstr = "update $tdatabase set " . ii_cfnames($tfpre, 'nnote') . "=" . ii_cfnames($tfpre, 'nnote') . "+1," . ii_cfnames($tfpre, 'last_time') . "='" . ii_now() . "' where $tidfield=$tsid";
      @ii_conn_query($tsqlstr, $conn);
      ap_update_userproperty('topic', 1, 0, $nusername);
      ap_update_userproperty('integral', $nint_reply, 0, $nusername);
    }
    mm_client_redirect($tbackurl);
  }
}

function wdja_cms_module_topic_editdisp()
{
  global $ctype;
  $ctype = 'edit';
  $Err = array();
  global $conn;
  global $ngenre, $nusername;
  global $ndatabase, $nidfield, $nfpre;
  global $nuser_upload;
  ap_user_islogin(ii_get_actual_route($ngenre));
  $tbackurl = $_GET['backurl'];
  $ttid = ii_get_num($_GET['tid']);
  $tsid = ii_get_num($_GET['sid']);
  if (!mm_ck_valcode()) $Err[count($Err)] = ii_itake('global.lng_error.valcode', 'lng');
  $tsqlstr = "select * from $ndatabase where " . ii_cfname('hidden') . "=0 and " . ii_cfname('author') . "='$nusername' and $nidfield=$ttid";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if (!$trs) mm_imessage(ii_itake('module.topicedit_failed', 'lng'), $tbackurl);
  else
  {
    if (ii_get_num($trs[ii_cfname('fid')]) == 0)
    {
      if (ii_isnull($_POST['topic'])) $Err[count($Err)] = str_replace('[]', '[' . ii_itake('config.topic', 'lng') . ']', ii_itake('global.lng_error.insert_empty', 'lng'));
    }
  }
  $tckstr = 'content:' . ii_itake('config.content', 'lng');
  $tary = explode(',', $tckstr);
  foreach ($tary as $val)
  {
    $tvalary = explode(':', $val);
    if (ii_isnull($_POST[$tvalary[0]])) $Err[count($Err)] = str_replace('[]', '[' . $tvalary[1] . ']', ii_itake('global.lng_error.insert_empty', 'lng'));
  }
  if (count($Err) > 0) mm_imessage($Err[0], '-1');
  if (!is_array($Err) || count($Err) == 0)
  {
    if ($nuser_upload == 1) $tcontent_files_list = ii_left(ii_cstr($_POST['content_files_list']), 1000);
    else $tcontent_files_list = '';
    $tsqlstr = "update $ndatabase set
    " . ii_cfname('icon') . "=" . ii_get_num($_POST['icon']) . ",
    " . ii_cfname('topic') . "='" . ii_left(ii_cstr($_POST['topic']), 50) . "',
    " . ii_cfname('content') . "='" . ii_left(ii_cstr($_POST['content']), 20000) . "' ,
    " . ii_cfname('content_files_list') . "='$tcontent_files_list',
    " . ii_cfname('edit_info') . "='" . ii_ireplace('global.forum:module.topicedit_info', 'lng') . "'
    where $nidfield=$ttid";
    $trs = ii_conn_query($tsqlstr, $conn);
    if ($trs)
    {
      if ($nuser_upload == 1) uu_upload_update_database_note($ngenre, $tcontent_files_list, 'content_files', $ttid);
      mm_imessage(ii_itake('module.topicedit_succeed', 'lng'), $tbackurl);
    }
    else mm_imessage(ii_itake('module.topicedit_failed', 'lng'), $tbackurl);
  }
}

function wdja_cms_module_topic_votedisp()
{
  global $conn, $ngenre, $nusername;
  $tid = ii_get_num($_GET['id']);
  $tbackurl = $_GET['backurl'];
  ap_user_islogin($tbackurl);
  if (ii_get_num($_COOKIE[APP_NAME . 'forum_vote'][$tid]) == 1) mm_client_alert(ii_itake('vote.failed', 'lng'), -1);
  $tvotes = $_POST['votes'];
  if (is_array($tvotes)) $tvotes = implode(',', $tvotes);
  if (ii_isnull($tvotes)) mm_client_alert(ii_itake('vote.error1', 'lng'), -1);
  $tdatabase = mm_cndatabase($ngenre, 'vote');
  $tidfield = mm_cnidfield($ngenre, 'vote');
  $tfpre = mm_cnfpre($ngenre, 'vote');
  $tsqlstr = "select * from $tdatabase where $tidfield=$tid";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs)
  {
    if (ii_get_num($trs[ii_cfnames($tfpre, 'day')]) != -1)
    {
      if (ii_datediff('d', ii_get_date($trs[ii_cfnames($tfpre, 'time')]), ii_now()) > ii_get_num($trs[ii_cfnames($tfpre, 'day')])) mm_client_alert(ii_itake('vote.error2', 'lng'), -1);
    }
    if (ii_get_num($trs[ii_cfnames($tfpre, 'type')]) == 0)
    {
      $tvotes = ii_get_num($tvotes, 0);
      if ($tvotes == 0) mm_client_alert(ii_itake('vote.error3', 'lng'), -1);
    }
    else
    {
      if (!ii_cidary($tvotes)) mm_client_alert(ii_itake('vote.error3', 'lng'), -1);
    }
  }
  else mm_client_alert(ii_itake('vote.error4', 'lng'), -1);
  $tdatabase = mm_cndatabase($ngenre, 'vote_voter');
  $tidfield = mm_cnidfield($ngenre, 'vote_voter');
  $tfpre = mm_cnfpre($ngenre, 'vote_voter');
  $tuserip = ii_get_client_ip();
  $tsqlstr = "select * from $tdatabase where " . ii_cfnames($tfpre, 'fid') . "=$tid and " . ii_cfnames($tfpre, 'ip') . "='$tuserip'";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs) mm_client_alert(ii_itake('vote.error5', 'lng'), -1);
  else
  {
    $tsqlstr = "insert into $tdatabase (
    " . ii_cfnames($tfpre, 'fid') . ",
    " . ii_cfnames($tfpre, 'ip') . ",
    " . ii_cfnames($tfpre, 'username') . ",
    " . ii_cfnames($tfpre, 'data') . ",
    " . ii_cfnames($tfpre, 'time') . "
    ) values (
    $tid,
    '$tuserip',
    '$nusername',
    '" . ii_left(ii_cstr($tvotes), 255) . "',
    '" . ii_now() . "'
    )";
    $trs = ii_conn_query($tsqlstr, $conn);
    if ($trs)
    {
      $tdatabase = mm_cndatabase($ngenre, 'vote_data');
      $tidfield = mm_cnidfield($ngenre, 'vote_data');
      $tfpre = mm_cnfpre($ngenre, 'vote_data');
      $tsqlstr = "update $tdatabase set " . ii_cfnames($tfpre, 'count') . "=" . ii_cfnames($tfpre, 'count') . "+1 where " . ii_cfnames($tfpre, 'fid') . "=$tid and $tidfield in ($tvotes)";
      $trs = ii_conn_query($tsqlstr, $conn);
      if ($trs)
      {
        header("Set-Cookie:".APP_NAME."forum_vote[".$tid."]=1;path =".COOKIES_PATH.";httpOnly;SameSite=Strict;expires=".COOKIES_EXPIRES.";",false);
        mm_imessage(ii_itake('vote.succeed', 'lng'), $tbackurl);
      }
      else mm_imessage(ii_itake('vote.error0', 'lng'), $tbackurl);
    }
    else mm_client_alert(ii_itake('vote.failed', 'lng'), -1);
  }
}

function wdja_cms_module_action()
{
  global $nuser_upload;
  switch($_GET['action'])
  {
    case 'manage':
      wdja_cms_module_topic_managedisp();
      break;
    case 'release':
      wdja_cms_module_topic_releasedisp();
      break;
    case 'reply':
      wdja_cms_module_topic_replydisp();
      break;
    case 'edit':
      wdja_cms_module_topic_editdisp();
      break;
    case 'vote':
      wdja_cms_module_topic_votedisp();
      break;
    case 'upload':
      if ($nuser_upload == 1) uu_upload_files();
      break;
  }
}

function wdja_cms_module_manage_topic()
{
  global $conn, $ngenre;
  global $ndatabase, $nidfield, $nfpre;
  global $npagesize, $nlisttopx;
  global $forum_isadmin, $ncontrol;
  $ncontrol = 'select';
  if ($forum_isadmin == 1) $ncontrol .= ',htop';
  $ncontrol .= ',top,elite,lock,hidden';
  $tsid = ii_get_num($_GET['sid']);
  $toffset = ii_get_num($_GET['offset']);
  $tatt = ii_get_safecode($_GET['att']);
  $tdatabase = mm_cndatabase($ngenre, 'sort');
  $tidfield = mm_cnidfield($ngenre, 'sort');
  $tfpre = mm_cnfpre($ngenre, 'sort');
  $tsqlstr = "select * from $tdatabase where " . ii_cfnames($tfpre, 'hidden') . "=0 and not " . ii_cfnames($tfpre, 'fsid') . "=0 and $tidfield=$tsid";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if (!$trs) mm_imessage(ii_itake('config.notexist', 'lng'), -1);
  $tmpstr = ii_itake('module.manage_topic', 'tpl');
  $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_ida}');
  $tsqlstr = "select * from $ndatabase where " . ii_cfname('sid') . "=$tsid and " . ii_cfname('fid') . "=0";
  if ($tatt == 'elite') $tsqlstr .= " and " . ii_cfname('elite') . "=1";
  if ($tatt == 'lock') $tsqlstr .= " and " . ii_cfname('lock') . "=1";
  if ($tatt == 'top') $tsqlstr .= " and " . ii_cfname('top') . "=1";
  if ($tatt == 'htop') $tsqlstr .= " and " . ii_cfname('htop') . "=1";
  if ($tatt == 'hidden') $tsqlstr .= " and " . ii_cfname('hidden') . "=1";
  $tsqlstr .= " order by " . ii_cfname('htop') . " desc," . ii_cfname('top') . " desc," . ii_cfname('lasttime') . " desc";
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
    $tpicnew = ii_ireplace('config.new', 'tpl');
    $tpichtop = ii_ireplace('config.htop', 'tpl');
    $tpictop = ii_ireplace('config.top', 'tpl');
    $tpicelite = ii_ireplace('config.elite', 'tpl');
    $tpiclock = ii_ireplace('config.lock', 'tpl');
    $tpichidden = ii_ireplace('config.hidden', 'tpl');
    foreach($trsary as $trs)
    {
      $ttopic = pp_change_forum_topic(ii_htmlencode($trs[ii_cfname('topic')]), $trs[ii_cfname('color')], $trs[ii_cfname('b')]);
      if (isset($font_red))
      {
        $font_red = str_replace('{$explain}', $tkeyword, $font_red);
        $ttopic = str_replace($tkeyword, $font_red, $ttopic);
      }
      $ttopicpic = '';
      if (ii_datediff('h', ii_get_date($trs[ii_cfname('time')]), ii_now()) < 24) $ttopicpic .= $tpicnew;
      if (ii_get_num($trs[ii_cfname('htop')]) == 1) $ttopicpic .= $tpichtop;
      if (ii_get_num($trs[ii_cfname('top')]) == 1) $ttopicpic .= $tpictop;
      if (ii_get_num($trs[ii_cfname('elite')]) == 1) $ttopicpic .= $tpicelite;
      if (ii_get_num($trs[ii_cfname('lock')]) == 1) $ttopicpic .= $tpiclock;
      if (ii_get_num($trs[ii_cfname('hidden')]) == 1) $ttopicpic .= $tpichidden;
      $tmptstr = str_replace('{$icon}', ii_get_num($trs[ii_cfname('icon')]), $tmpastr);
      $tmptstr = str_replace('{$topic}', $ttopic, $tmptstr);
      $tmptstr = str_replace('{$topicpic}', $ttopicpic, $tmptstr);
      $tmptstr = str_replace('{$author}', ii_htmlencode($trs[ii_cfname('author')]), $tmptstr);
      $tmptstr = str_replace('{$reply}', ii_get_num($trs[ii_cfname('reply')]), $tmptstr);
      $tmptstr = str_replace('{$count}', ii_get_num($trs[ii_cfname('count')]), $tmptstr);
      $tmptstr = str_replace('{$lasttime}', ii_format_date($trs[ii_cfname('lasttime')], 11), $tmptstr);
      $tmptstr = str_replace('{$lastuser}', ii_htmlencode($trs[ii_cfname('lastuser')]), $tmptstr);
      $tmptstr = str_replace('{$tid}', $trs[$nidfield], $tmptstr);
      $tmptstr = ii_creplace($tmptstr);
      $tmprstr .= $tmptstr;
    }
  }
  $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
  $tmpstr = str_replace('{$cpagestr}', $tcp -> get_pagenum(), $tmpstr);
  $tmpstr = str_replace('{$sid}', $tsid, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function wdja_cms_module_manage_detail()
{
  global $ncontrol;
  $ncontrol = 'select,hidden';
  global $conn, $ngenre;
  global $ndatabase, $nidfield, $nfpre;
  global $npagesize_reply, $nlisttopx;
  $tsid = ii_get_num($_GET['sid']);
  $ttid = ii_get_num($_GET['tid']);
  $toffset = ii_get_num($_GET['offset']);
  $tdatabase = mm_cndatabase(USER_FOLDER);
  $tidfield = mm_cnidfield(USER_FOLDER);
  $tfpre = mm_cnfpre(USER_FOLDER);
  $tmpstr = ii_itake('module.manage_detail', 'tpl');
  $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_ida}');
  $tmpastr2 = ii_ctemplate_infos($tmpstr, '{@recurrence_idb}');
  $tmprstr = '';
  $tmprstr2 = '';
  $tsqlstr = "select * from $ndatabase,$tdatabase where $ndatabase." . ii_cfname('author') . "=$tdatabase." . ii_cfnames($tfpre, 'username') . " and ($ndatabase.$nidfield=$ttid or $ndatabase." . ii_cfname('fid') . "=$ttid) order by $ndatabase." . ii_cfname('fid') . " asc,$ndatabase." . ii_cfname('time') . " asc";
  $tcp = new cc_cutepage;
  $tcp -> id = $nidfield;
  $tcp -> pagesize = $npagesize_reply;
  $tcp -> rslimit = $nlisttopx;
  $tcp -> sqlstr = $tsqlstr;
  $tcp -> offset = $toffset;
  $tcp -> init();
  $trsary = $tcp -> get_rs_array();
  if (is_array($trsary))
  {
    $ti = 1;
    $tpichidden = ii_ireplace('config.hidden', 'tpl');
    foreach($trsary as $trs)
    {
      $floor = $toffset + $ti;
      if ($floor == 1 ) $tmptstr = str_replace('{$username}', ii_htmlencode($trs[ii_cfnames($tfpre, 'username')]), $tmpastr2);
      else $tmptstr = str_replace('{$username}', ii_htmlencode($trs[ii_cfnames($tfpre, 'username')]), $tmpastr);
      $tmptstr = str_replace('{$utype}', ii_get_num($trs[ii_cfnames($tfpre, 'utype')]), $tmptstr);
      $tmptstr = str_replace('{$face}', ii_htmlencode($trs[ii_cfnames($tfpre, 'face')]), $tmptstr);
      $tmptstr = str_replace('{$email}', ii_htmlencode($trs[ii_cfnames($tfpre, 'email')]), $tmptstr);
      $tmptstr = str_replace('{$num_topic}', ii_get_num($trs[ii_cfnames($tfpre, 'topic')]), $tmptstr);
      $tmptstr = str_replace('{$integral}', ii_get_num($trs[ii_cfnames($tfpre, 'integral')]), $tmptstr);
      $tmptstr = str_replace('{$regtime}', ii_format_date(ii_get_date($trs[ii_cfnames($tfpre, 'time')]), 2), $tmptstr);
      $tmptstr = str_replace('{$sign}', ii_htmlencode($trs[ii_cfnames($tfpre, 'sign')]), $tmptstr);
      $tmptstr = str_replace('{$icon}', ii_get_num($trs[ii_cfname('icon')]), $tmptstr);
      $tmptstr = str_replace('{$topic}', ii_htmlencode($trs[ii_cfname('topic')]), $tmptstr);
      $tmptstr = str_replace('{$time}', ii_get_date($trs[ii_cfname('time')]), $tmptstr);
      $tmptstr = str_replace('{$content}', $trs[ii_cfname('content')], $tmptstr);
      $tmptstr = str_replace('{$edit_info}', ii_htmlencode($trs[ii_cfname('edit_info')]), $tmptstr);
      if ($floor == 1 ) $tmptstr = str_replace('{$floor}', $floor, $tmptstr);
      else $tmptstr = str_replace('{$floor}', $floor - 1, $tmptstr);
      $tmptstr = str_replace('{$tid}', ii_get_num($trs[$nidfield]), $tmptstr);
      if (ii_get_num($trs[ii_cfname('hidden')]) == 1) $tmptstr = str_replace('{$topicpic}', $tpichidden, $tmptstr);
      else $tmptstr = str_replace('{$topicpic}', '', $tmptstr);
      if ($floor == 1 ) $tmprstr2 = $tmptstr;
      else $tmprstr .= $tmptstr;
      $ti = $ti + 1;
    }
  }
  $tmpstr = str_replace(WDJA_CINFO_INFOS, $tmprstr2, $tmpstr);
  $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
  $tmpstr = str_replace('{$cpagestr}', $tcp -> get_pagenum(), $tmpstr);
  $tmpstr = str_replace('{$sid}', $tsid, $tmpstr);
  $tmpstr = str_replace('{$tid}', $ttid, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function wdja_cms_module_manage_blacklist()
{
  global $ncontrol;
  $ncontrol = 'select,delete';
  global $conn, $ngenre;
  global $ndatabase, $nidfield, $nfpre;
  global $npagesize, $nlisttopx;
  $tsid = ii_get_num($_GET['sid']);
  $toffset = ii_get_num($_GET['offset']);
  $tdatabase = mm_cndatabase($ngenre, 'sort');
  $tidfield = mm_cnidfield($ngenre, 'sort');
  $tfpre = mm_cnfpre($ngenre, 'sort');
  $tsqlstr = "select * from $tdatabase where " . ii_cfnames($tfpre, 'hidden') . "=0 and not " . ii_cfnames($tfpre, 'fsid') . "=0 and $tidfield=$tsid";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if (!$trs) mm_imessage(ii_itake('config.notexist', 'lng'), -1);
  $tdatabase = mm_cndatabase($ngenre, 'blacklist');
  $tidfield = mm_cnidfield($ngenre, 'blacklist');
  $tfpre = mm_cnfpre($ngenre, 'blacklist');
  $tmpstr = ii_itake('module.manage_blacklist', 'tpl');
  $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_ida}');
  $tmprstr = '';
  $tsqlstr = "select * from $tdatabase where " . ii_cfnames($tfpre, 'sid') . "=$tsid order by $tidfield desc";
  $tcp = new cc_cutepage;
  $tcp -> id = $tidfield;
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
      $tmptstr = str_replace('{$username}', ii_htmlencode($trs[ii_cfnames($tfpre, 'username')]), $tmpastr);
      $tmptstr = str_replace('{$sid}', ii_get_num($trs[ii_cfnames($tfpre, 'sid')]), $tmptstr);
      $tmptstr = str_replace('{$admin}', ii_htmlencode($trs[ii_cfnames($tfpre, 'admin')]), $tmptstr);
      $tmptstr = str_replace('{$time}', ii_htmlencode($trs[ii_cfnames($tfpre, 'time')]), $tmptstr);
      $tmptstr = str_replace('{$remark}', ii_htmlencode($trs[ii_cfnames($tfpre, 'remark')]), $tmptstr);
      $tmptstr = str_replace('{$id}', ii_htmlencode($trs[$tidfield]), $tmptstr);
      $tmprstr .= $tmptstr;
    }
  }
  $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
  $tmpstr = str_replace('{$cpagestr}', $tcp -> get_pagenum(), $tmpstr);
  $tmpstr = str_replace('{$sid}', $tsid, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function wdja_cms_module_manage()
{
  ap_user_islogin();
  global $nusername, $forum_isadmin;
  $tsid = ii_get_num($_GET['sid']);
  $forum_isadmin = pp_check_forum_isadmin($tsid, $nusername);
  if ($forum_isadmin == 0) mm_imessage(ii_itake('config.admininfo', 'lng'), -1);
  switch(mm_ctype($_GET['mtype']))
  {
    case 'topic':
      return wdja_cms_module_manage_topic();
      break;
    case 'detail':
      return wdja_cms_module_manage_detail();
      break;
    case 'blacklist':
      return wdja_cms_module_manage_blacklist();
      break;
    default:
      return wdja_cms_module_manage_topic();
      break;
  }
}

function wdja_cms_module_index()
{
  global $conn, $nlng;
  global $ngenre, $nusername;
  $tdatabase = mm_cndatabase($ngenre, 'sort');
  $tidfield = mm_cnidfield($ngenre, 'sort');
  $tfpre = mm_cnfpre($ngenre, 'sort');
  $tutype = ap_get_userinfo('utype', $nusername);
  $tmpstr = ii_itake('module.index', 'tpl');
  $tmprstr = '';
  $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_forum}');
  $tpl_image = ii_itake('global.tpl_config.image', 'tpl');
  $tsqlstr = "select * from $tdatabase where " . ii_cfnames($tfpre, 'fsid') . "=0 and " . ii_cfnames($tfpre, 'hidden') . "=0 and " . ii_cfnames($tfpre, 'lng') . "='$nlng' order by " . ii_cfnames($tfpre, 'order') . " asc";
  $trs = ii_conn_query($tsqlstr, $conn);
  while ($trow = ii_conn_fetch_array($trs))
  {
    $tmpstr2 = $tmpastr;
    $tmprstr2 = '';
    $tmpastr2 = ii_ctemplate($tmpstr2, '{@recurrence_ida}');
    $tsqlstr2 = "select * from $tdatabase where " . ii_cfnames($tfpre, 'fsid') . "=" . $trow[$tidfield] . " and " . ii_cfnames($tfpre, 'hidden') . "=0 order by " . ii_cfnames($tfpre, 'order') . " asc";
    $trs2 = ii_conn_query($tsqlstr2, $conn);
    while ($trow2 = ii_conn_fetch_array($trs2))
    {
      $tislock = pp_check_forum_islock($trow2[ii_cfnames($tfpre, 'popedom')], $tutype, $trow2[ii_cfnames($tfpre, 'type')], $trow2[ii_cfnames($tfpre, 'attestation')], $nusername);
      $tforumImages = ii_htmlencode($trow2[ii_cfnames($tfpre, 'images')]);
      if (!ii_isnull($tforumImages)) $tforumImages = str_replace('{$value}', $tforumImages, $tpl_image);
      $tmptstr2 = str_replace('{$sort}', ii_encode_text($trow2[ii_cfnames($tfpre, 'sort')]), $tmpastr2);
      $tmptstr2 = str_replace('{$explain}', ii_encode_article(ii_encode_text($trow2[ii_cfnames($tfpre, 'explain')])), $tmptstr2);
      $tmptstr2 = str_replace('{$admin}', pp_get_forum_admin($trow2[ii_cfnames($tfpre, 'admin')]), $tmptstr2);
      $tmptstr2 = str_replace('{$pic}', pp_get_forum_pic($tislock, $trow2[ii_cfnames($tfpre, 'today_date')]), $tmptstr2);
      $tmptstr2 = str_replace('{$forum_images}', $tforumImages, $tmptstr2);
      $tmptstr2 = str_replace('{$forum_info}', pp_get_forum_info($trow2[$tidfield], $tislock, $trow2[ii_cfnames($tfpre, 'last_tid')], $trow2[ii_cfnames($tfpre, 'last_topic')], $trow2[ii_cfnames($tfpre, 'last_time')], $trow2[ii_cfnames($tfpre, 'today_ntopic')], $trow2[ii_cfnames($tfpre, 'today_date')], $trow2[ii_cfnames($tfpre, 'ntopic')], $trow2[ii_cfnames($tfpre, 'nnote')]), $tmptstr2);
      $tmptstr2 = str_replace('{$id}', $trow2[$tidfield], $tmptstr2);
      $tmprstr2 .= $tmptstr2;
    }
    $tmpstr2 = str_replace(WDJA_CINFO, $tmprstr2, $tmpstr2);
    $tmpstr2 = str_replace('{$sort}', $trow[ii_cfnames($tfpre, 'sort')], $tmpstr2);
    $tmprstr .= $tmpstr2;
  }
  $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function wdja_cms_module_topic_list()
{
  global $conn, $nlng;
  global $ngenre, $nusername;
  global $npagesize, $nlisttopx;
  global $ndatabase, $nidfield, $nfpre;
  $tdatabase = mm_cndatabase($ngenre, 'sort');
  $tidfield = mm_cnidfield($ngenre, 'sort');
  $tfpre = mm_cnfpre($ngenre, 'sort');
  $tsid = ii_get_num($_GET['sid']);
  $toffset = ii_get_num($_GET['offset']);
  $tutype = ap_get_userinfo('utype', $nusername);
  $tsqlstr = "select * from $tdatabase where " . ii_cfnames($tfpre, 'hidden') . "=0 and not " . ii_cfnames($tfpre, 'fsid') . "=0 and $tidfield=$tsid";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if (!$trs) mm_imessage(ii_itake('config.notexist', 'lng'), -1);
  else
  {
    mm_cntitle($trs[ii_cfnames($tfpre, 'sort')]);
    $tmpstr = ii_itake('module.topic_list', 'tpl');
    $tmprstr = '';
    $tmpastr = ii_ctemplate($tmpstr, '{@child_forum}');
    $tpl_image = ii_itake('global.tpl_config.image', 'tpl');
    $tislock = pp_check_forum_islock($trs[ii_cfnames($tfpre, 'popedom')], $tutype, $trs[ii_cfnames($tfpre, 'type')], $trs[ii_cfnames($tfpre, 'attestation')], $nusername);
    if ($tislock) mm_imessage(ii_itake('module.popedom', 'lng'), -1);
    $tsqlstr2 = "select * from $tdatabase where " . ii_cfnames($tfpre, 'fsid') . "=$tsid and " . ii_cfnames($tfpre, 'hidden') . "=0 order by " . ii_cfnames($tfpre, 'order') . " asc";
    $trs2 = ii_conn_query($tsqlstr2, $conn);
    $tmprstr2 = '';
    $tmpastr2 = ii_ctemplate($tmpastr, '{@recurrence_ida}');
    while ($trow2 = ii_conn_fetch_array($trs2))
    {
      $tislock = pp_check_forum_islock($trow2[ii_cfnames($tfpre, 'popedom')], $tutype, $trow2[ii_cfnames($tfpre, 'type')], $trow2[ii_cfnames($tfpre, 'attestation')], $nusername);
      $tforumImages = ii_htmlencode($trow2[ii_cfnames($tfpre, 'images')]);
      if (!ii_isnull($tforumImages)) $tforumImages = str_replace('{$value}', $tforumImages, $tpl_image);
      $tmptstr2 = str_replace('{$sort}', ii_encode_text($trow2[ii_cfnames($tfpre, 'sort')]), $tmpastr2);
      $tmptstr2 = str_replace('{$explain}', ii_encode_article(ii_encode_text($trow2[ii_cfnames($tfpre, 'explain')])), $tmptstr2);
      $tmptstr2 = str_replace('{$admin}', pp_get_forum_admin($trow2[ii_cfnames($tfpre, 'admin')]), $tmptstr2);
      $tmptstr2 = str_replace('{$pic}', pp_get_forum_pic($tislock, $trow2[ii_cfnames($tfpre, 'today_date')]), $tmptstr2);
      $tmptstr2 = str_replace('{$forum_images}', $tforumImages, $tmptstr2);
      $tmptstr2 = str_replace('{$forum_info}', pp_get_forum_info($trow2[$tidfield], $tislock, $trow2[ii_cfnames($tfpre, 'last_tid')], $trow2[ii_cfnames($tfpre, 'last_topic')], $trow2[ii_cfnames($tfpre, 'last_time')], $trow2[ii_cfnames($tfpre, 'today_ntopic')], $trow2[ii_cfnames($tfpre, 'today_date')], $trow2[ii_cfnames($tfpre, 'ntopic')], $trow2[ii_cfnames($tfpre, 'nnote')]), $tmptstr2);
      $tmptstr2 = str_replace('{$id}', $trow2[$tidfield], $tmptstr2);
      $tmprstr2 .= $tmptstr2;
    }
    if (ii_isnull($tmprstr2)) $tmpastr = '';
    else $tmpastr = str_replace(WDJA_CINFO, $tmprstr2, $tmpastr);
    $tmpstr = str_replace(WDJA_CINFO, $tmpastr, $tmpstr);
    $tmpstr = str_replace('{$forum_admin}', pp_get_forum_admin($trs[ii_cfnames($tfpre, 'admin')]), $tmpstr);
    $tmpstr = str_replace('{$sort}', ii_encode_text($trs[ii_cfnames($tfpre, 'sort')]), $tmpstr);
    $tmpstr = str_replace('{$rule}', ii_encode_article(ii_encode_text($trs[ii_cfnames($tfpre, 'rule')])), $tmpstr);
    $tmode = $trs[ii_cfnames($tfpre, 'mode')];
    if ($tmode == 1) $tmodeVstr = 0;
    else $tmodeVstr = 1;
    $tmpstr = mm_cvalhtml($tmpstr, $tmodeVstr, '{@topic_list}');
    if ($tmode != 1)
    {
      $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_idb}');
      $tsqlstr = "select * from $ndatabase where (" . ii_cfname('htop') . "=1 or " . ii_cfname('sid') . "=$tsid) and " . ii_cfname('fid') . "=0 and " . ii_cfname('hidden') . "=0 order by " . ii_cfname('htop') . " desc," . ii_cfname('top') . " desc," . ii_cfname('lasttime') . " desc";
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
        $tpicnew = ii_ireplace('config.new', 'tpl');
        foreach($trsary as $trs)
        {
          $ttopic = pp_change_forum_topic(ii_htmlencode($trs[ii_cfname('topic')]), $trs[ii_cfname('color')], $trs[ii_cfname('b')]);
          $ttopicpic = '';
          if (ii_datediff('h', ii_get_date($trs[ii_cfname('time')]), ii_now()) < 24) $ttopicpic .= $tpicnew;
          $tmptstr = str_replace('{$ico}', pp_get_forum_topic_pic(ii_get_num($trs[ii_cfname('htop')]), ii_get_num($trs[ii_cfname('top')]), ii_get_num($trs[ii_cfname('lock')]), ii_get_num($trs[ii_cfname('elite')]), ii_get_num($trs[ii_cfname('count')])), $tmpastr);
          $tmptstr = str_replace('{$icon}', ii_get_num($trs[ii_cfname('icon')]), $tmptstr);
          $tmptstr = str_replace('{$topic}', $ttopic, $tmptstr);
          $tmptstr = str_replace('{$topicpic}', $ttopicpic, $tmptstr);
          $tmptstr = str_replace('{$author}', ii_htmlencode($trs[ii_cfname('author')]), $tmptstr);
          $tmptstr = str_replace('{$reply}', number_format(ii_get_num($trs[ii_cfname('reply')])), $tmptstr);
          $tmptstr = str_replace('{$count}', number_format(ii_get_num($trs[ii_cfname('count')])), $tmptstr);
          $tmptstr = str_replace('{$lasttime}', ii_format_date($trs[ii_cfname('lasttime')], 11), $tmptstr);
          $tmptstr = str_replace('{$lastuser}', ii_htmlencode($trs[ii_cfname('lastuser')]), $tmptstr);
          $tmptstr = str_replace('{$tid}', $trs[$nidfield], $tmptstr);
          $tmptstr = ii_creplace($tmptstr);
          $tmprstr .= $tmptstr;
        }
      }
      $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
      $tmpstr = str_replace('{$cpagestr}', $tcp -> get_pagenum(), $tmpstr);
    }
    $tmpstr = str_replace('{$sid}', $tsid, $tmpstr);
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
  }
}

function wdja_cms_module_topic_detail()
{
  global $conn, $ngenre;
  global $ndatabase, $nidfield, $nfpre;
  global $nvalidate, $nlisttopx, $npagesize_reply;
  global $nuser_upload;
  $tsid = ii_get_num($_GET['sid']);
  $ttid = ii_get_num($_GET['tid']);
  $toffset = ii_get_num($_GET['offset'], 0);
  if (pp_check_forum_popedom($tsid, 0) != 0) mm_imessage(ii_itake('module.popedom', 'lng'), -1);
  $tmpstr = ii_itake('module.topic_detail', 'tpl');
  $tsqlstr = "select * from $ndatabase where " . ii_cfname('fid') . "=0 and $nidfield=$ttid";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if (!$trs) mm_imessage(ii_itake('global.lng_public.sudd', 'lng'), -1);
  else
  {
    if ($trs[ii_cfname('hidden')] == 1) mm_imessage(ii_itake('module.topic_hidden', 'lng'), -1);
    if ($trs[ii_cfname('htop')] == 0 && $trs[ii_cfname('sid')] != $tsid) mm_imessage(ii_itake('global.lng_public.sudd', 'lng'), -1);
    $tvoteid = ii_get_num($trs[ii_cfname('voteid')]);
    $tlock = ii_get_num($trs[ii_cfname('lock')]);
    $ttopic = ii_htmlencode($trs[ii_cfname('topic')]);
    mm_cntitle($ttopic);
    $tsqlstr = "update $ndatabase set " . ii_cfname('count') . "=" . ii_cfname('count') . "+1 where $nidfield=$ttid";
    @ii_conn_query($tsqlstr, $conn);
  }
  if ($tvoteid != 0)
  {
    $ti = 0;
    $tvotecount = 0;
    $tVoteDatabase = mm_cndatabase($ngenre, 'vote');
    $tVoteIdfield = mm_cnidfield($ngenre, 'vote');
    $tVoteFpre = mm_cnfpre($ngenre, 'vote');
    $tVoteDataDatabase = mm_cndatabase($ngenre, 'vote_data');
    $tVoteDataIdfield = mm_cnidfield($ngenre, 'vote_data');
    $tVoteDataFpre = mm_cnfpre($ngenre, 'vote_data');
    $tsqlstr = "select * from $tVoteDatabase,$tVoteDataDatabase where $tVoteDatabase.$tVoteIdfield=$tVoteDataDatabase." . ii_cfnames($tVoteDataFpre, 'fid') . " and $tVoteDatabase.$tVoteIdfield=$tvoteid";
    $trs = ii_conn_query($tsqlstr, $conn);
    while ($trow = ii_conn_fetch_array($trs))
    {
      if ($ti == 0)
      {
        $tvtopic = ii_htmlencode($trow[ii_cfnames($tVoteFpre, 'topic')]);
        $tvtype = ii_get_num($trow[ii_cfnames($tVoteFpre, 'type')], 0);
        $tvday = ii_get_num($trow[ii_cfnames($tVoteFpre, 'day')], -1);
        if ($tvday == -1) $tendtime = ii_itake('config.noexp', 'lng');
        else $tendtime = ii_dateadd('d', $tvday, ii_get_date($trow[ii_cfnames($tVoteFpre, 'time')]));
      }
      $tvoteary[$ti][0] = $trow[$tVoteDataIdfield];
      $tvoteary[$ti][1] = ii_htmlencode($trow[ii_cfnames($tVoteDataFpre, 'topic')]);
      $tvoteary[$ti][2] = ii_get_num($trow[ii_cfnames($tVoteDataFpre, 'count')]);
      $tvotecount = $tvotecount + ii_get_num($trow[ii_cfnames($tVoteDataFpre, 'count')]);
      $ti = $ti + 1;
    }
    if (is_array($tvoteary))
    {
      $tvotestr = ii_ctemplate($tmpstr, '{@recurrence_vote}');
      $tvoteastr = ii_ctemplate($tvotestr, '{@recurrence_voa}');
      $tvoterstr = '';
      foreach ($tvoteary as $key => $val)
      {
        $tvotetstr = str_replace('{$id}', $val[0], $tvoteastr);
        $tvotetstr = str_replace('{$topic}', $val[1], $tvotetstr);
        $tvotetstr = str_replace('{$type}', $tvtype, $tvotetstr);
        $tvotetstr = str_replace('{$per}', ii_cper($val[2], $tvotecount), $tvotetstr);
        $tvoterstr .= $tvotetstr;
      }
      $tvotestr = str_replace(WDJA_CINFO, $tvoterstr, $tvotestr);
      $tvotestr = str_replace('{$topic}', $tvtopic, $tvotestr);
      $tvotestr = str_replace('{$count}', $tvotecount, $tvotestr);
      $tvotestr = str_replace('{$endtime}', $tendtime, $tvotestr);
      $tvotestr = str_replace('{$id}', $tvoteid, $tvotestr);
      $tmpstr = str_replace(WDJA_CINFO, $tvotestr, $tmpstr);
    }
    else $tmpstr = mm_cvalhtml($tmpstr, 0, '{@recurrence_vote}');
  }
  else $tmpstr = mm_cvalhtml($tmpstr, 0, '{@recurrence_vote}');
  $tdatabase = mm_cndatabase(USER_FOLDER);
  $tidfield = mm_cnidfield(USER_FOLDER);
  $tfpre = mm_cnfpre(USER_FOLDER);
  $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_ida}');
  $tmpastr2 = ii_ctemplate_infos($tmpstr, '{@recurrence_idb}');
  $tmprstr = '';
  $tmprstr2 = '';
  $tsqlstr = "select * from $ndatabase,$tdatabase where $ndatabase." . ii_cfname('author') . "=$tdatabase." . ii_cfnames($tfpre, 'username') . " and ($ndatabase.$nidfield=$ttid or $ndatabase." . ii_cfname('fid') . "=$ttid) and $ndatabase." . ii_cfname('hidden') . "=0 order by $ndatabase." . ii_cfname('fid') . " asc,$ndatabase." . ii_cfname('time') . " asc";
  $tcp = new cc_cutepage;
  $tcp -> id = $nidfield;
  $tcp -> pagesize = $npagesize_reply;
  $tcp -> rslimit = $nlisttopx;
  $tcp -> sqlstr = $tsqlstr;
  $tcp -> offset = $toffset;
  $tcp -> init();
  $trsary = $tcp -> get_rs_array();
  if (is_array($trsary))
  {
    $ti = 1;
    foreach($trsary as $trs)
    {
      $floor = $toffset + $ti;
      if ($floor == 1 ) $tmptstr = str_replace('{$username}', ii_htmlencode($trs[ii_cfnames($tfpre, 'username')]), $tmpastr2);
      else $tmptstr = str_replace('{$username}', ii_htmlencode($trs[ii_cfnames($tfpre, 'username')]), $tmpastr);
      $tmptstr = str_replace('{$utype}', ii_get_num($trs[ii_cfnames($tfpre, 'utype')]), $tmptstr);
      $tmptstr = str_replace('{$face}', ii_htmlencode($trs[ii_cfnames($tfpre, 'face')]), $tmptstr);
      $tmptstr = str_replace('{$email}', ii_htmlencode($trs[ii_cfnames($tfpre, 'email')]), $tmptstr);
      $tmptstr = str_replace('{$num_topic}', ii_get_num($trs[ii_cfnames($tfpre, 'topic')]), $tmptstr);
      $tmptstr = str_replace('{$integral}', ii_get_num($trs[ii_cfnames($tfpre, 'integral')]), $tmptstr);
      $tmptstr = str_replace('{$regtime}', ii_format_date(ii_get_date($trs[ii_cfnames($tfpre, 'time')]), 2), $tmptstr);
      $tmptstr = str_replace('{$sign}', ii_htmlencode($trs[ii_cfnames($tfpre, 'sign')]), $tmptstr);
      $tmptstr = str_replace('{$icon}', ii_get_num($trs[ii_cfname('icon')]), $tmptstr);
      $tmptstr = str_replace('{$topic}', ii_htmlencode($trs[ii_cfname('topic')]), $tmptstr);
      $tmptstr = str_replace('{$time}', ii_get_date($trs[ii_cfname('time')]), $tmptstr);
      $tmptstr = str_replace('{$content}',mm_encode_content($trs[ii_cfname('content')]), $tmptstr);
      $tmptstr = str_replace('{$edit_info}', ii_htmlencode($trs[ii_cfname('edit_info')]), $tmptstr);
      if ($floor == 1 ) $tmptstr = str_replace('{$floor}', $floor, $tmptstr);
      else $tmptstr = str_replace('{$floor}', $floor - 1, $tmptstr);
      $tmptstr = str_replace('{$tid}', ii_get_num($trs[$nidfield]), $tmptstr);
      if ($floor == 1 ) $tmprstr2 = $tmptstr;
      else $tmprstr .= $tmptstr;
      $ti = $ti + 1;
    }
  }
  $tmpstr = str_replace(WDJA_CINFO_INFOS, $tmprstr2, $tmpstr);
  $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
  $tmpstr = str_replace('{$cpagestr}', $tcp -> get_pagenum(), $tmpstr);
  $tmprstr = '';
  $tmpastr = ii_ctemplate($tmpstr, '{@topic_reply}');
  $tmpary = explode('{@@}', $tmpastr);
  if ($tlock == 0) $tmprstr = $tmpary[0];
  else $tmprstr = $tmpary[1];
  $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
  $tmpstr = str_replace('{$sid}', $tsid, $tmpstr);
  $tmpstr = str_replace('{$tid}', $ttid, $tmpstr);
  $tmpstr = mm_cvalhtml($tmpstr, $nvalidate, '{@recurrence_valcode}');
    $tmpstr = mm_cvalhtml($tmpstr, $nuser_upload, '{@recurrence_user_upload}');
    $tmpstr = mm_cvalhtml($tmpstr, $nuser_upload, '{@recurrence_user_upload_script}');
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function wdja_cms_module_topic_release()
{
  ap_user_islogin();
  global $nusername, $nvalidate;
  global $nuser_upload, $nnew_user_release_timeout;
  $tUserRegTime = ii_get_date(ap_get_userinfo('time', $nusername));
  if (ii_datediff('s', $tUserRegTime, ii_now()) <= $nnew_user_release_timeout) mm_imessage(ii_ireplace('module.new_user_release_timeout', 'lng'), -1);
  $tsid = ii_get_num($_GET['sid']);
  $tvote = ii_get_num($_GET['vote']);
  $tforum_popedom = pp_check_forum_popedom($tsid, 1);
  if ($tforum_popedom == -1) mm_imessage(ii_itake('module.pdm_none', 'lng'), -1);
  if ($tforum_popedom == 1) mm_imessage(ii_itake('module.pdm_type', 'lng'), -1);
  if ($tforum_popedom == 2) mm_imessage(ii_itake('module.pdm_mode', 'lng'), -1);
  if ($tforum_popedom == 3) mm_imessage(ii_itake('module.popedom', 'lng'), -1);
  $tmpstr = ii_itake('module.topic_release', 'tpl');
  $tmpstr = str_replace('{$sid}', $tsid, $tmpstr);
  $tmpstr = mm_cvalhtml($tmpstr, $tvote, '{@recurrence_vote}');
  $tmpstr = mm_cvalhtml($tmpstr, $nvalidate, '{@recurrence_valcode}');
  $tmpstr = mm_cvalhtml($tmpstr, $nuser_upload, '{@recurrence_user_upload}');
  $tmpstr = mm_cvalhtml($tmpstr, $nuser_upload, '{@recurrence_user_upload_script}');
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function wdja_cms_module_topic_reply()
{
  ap_user_islogin();
  global $nvalidate;
  $ttid = ii_get_num($_GET['tid']);
  $tsid = ii_get_num($_GET['sid']);
  $tmpstr = ii_itake('module.topic_reply', 'tpl');
  $tmpstr = str_replace('{$tid}', $ttid, $tmpstr);
  $tmpstr = str_replace('{$sid}', $tsid, $tmpstr);
  $tmpstr = mm_cvalhtml($tmpstr, $nvalidate, '{@recurrence_valcode}');
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function wdja_cms_module_topic_edit()
{
  ap_user_islogin();
  global $conn, $nusername;
  global $ndatabase, $nidfield, $nfpre;
  global $nvalidate, $nuser_upload;
  $tid = ii_get_num($_GET['tid']);
  $tbackurl = $_GET['backurl'];
  $tsqlstr = "select * from $ndatabase where " . ii_cfname('hidden') . "=0 and $nidfield=$tid";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs)
  {
    if ($trs[ii_cfname('author')] != $nusername) mm_imessage(ii_itake('module.topicedit_error', 'lng'), -1);
    $tmpstr = ii_itake('module.topic_edit', 'tpl');
    foreach ($trs as $key => $val)
    {
      $tkey = ii_get_lrstr($key, '_', 'rightr');
      $GLOBALS['RS_' . $tkey] = $val;
      $tmpstr = str_replace('{$' . $tkey . '}', ii_htmlencode($val), $tmpstr);
    }
    $tmpstr = str_replace('{$id}', $trs[$nidfield], $tmpstr);
    $tmpstr = str_replace('{$backurl}', urlencode($tbackurl), $tmpstr);
    $tmpstr = mm_cvalhtml($tmpstr, $nvalidate, '{@recurrence_valcode}');
    $tmpstr = mm_cvalhtml($tmpstr, $nuser_upload, '{@recurrence_user_upload}');
    $tmpstr = mm_cvalhtml($tmpstr, $nuser_upload, '{@recurrence_user_upload_script}');
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
  }
  else mm_client_alert(ii_itake('global.lng_public.sudd', 'lng'), -1);
}

function wdja_cms_module_search_list()
{
  global $conn;
  global $ndatabase, $nidfield, $nfpre;
  global $npagesize, $nlisttopx;
  $tsid = ii_get_num($_GET['sid']);
  $toffset = ii_get_num($_GET['offset']);
  $tkeyword = ii_get_safecode($_GET['keyword']);
  $tauthor = ii_get_safecode($_GET['author']);
  $tmpstr = ii_itake('module.search_list', 'tpl');
  $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_idb}');
  $tsqlstr = "select * from $ndatabase where " . ii_cfname('fid') . "=0 and " . ii_cfname('hidden') . "=0";
  if (!ii_isnull($tkeyword))
  {
    $font_red = ii_itake('global.tpl_config.font_red', 'tpl');
    $tsqlstr .= " and " . ii_cfname('topic') . " like '%" . $tkeyword . "%'";
  }
  if (!ii_isnull($tauthor)) $tsqlstr .= " and " . ii_cfname('author') . " like '%" . $tauthor . "%'";
  if ($tsid != 0) $tsqlstr .= " and " . ii_cfname('sid') . "=$tsid";
  $tsqlstr .= " order by " . ii_cfname('htop') . " desc," . ii_cfname('top') . " desc," . ii_cfname('lasttime') . " desc";
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
    $tpicnew = ii_ireplace('config.new', 'tpl');
    foreach($trsary as $trs)
    {
      $ttopic = pp_change_forum_topic(ii_htmlencode($trs[ii_cfname('topic')]), $trs[ii_cfname('color')], $trs[ii_cfname('b')]);
      if (isset($font_red))
      {
        $font_red = str_replace('{$explain}', $tkeyword, $font_red);
        $ttopic = str_replace($tkeyword, $font_red, $ttopic);
      }
      $ttopicpic = '';
      if (ii_datediff('h', ii_get_date($trs[ii_cfname('time')]), ii_now()) < 24) $ttopicpic .= $tpicnew;
      $tmptstr = str_replace('{$ico}', pp_get_forum_topic_pic(ii_get_num($trs[ii_cfname('htop')]), ii_get_num($trs[ii_cfname('top')]), ii_get_num($trs[ii_cfname('lock')]), ii_get_num($trs[ii_cfname('elite')]), ii_get_num($trs[ii_cfname('count')])), $tmpastr);
      $tmptstr = str_replace('{$icon}', ii_get_num($trs[ii_cfname('icon')]), $tmptstr);
      $tmptstr = str_replace('{$topic}', $ttopic, $tmptstr);
      $tmptstr = str_replace('{$topicpic}', $ttopicpic, $tmptstr);
      $tmptstr = str_replace('{$author}', ii_htmlencode($trs[ii_cfname('author')]), $tmptstr);
      $tmptstr = str_replace('{$reply}', number_format(ii_get_num($trs[ii_cfname('reply')])), $tmptstr);
      $tmptstr = str_replace('{$count}', number_format(ii_get_num($trs[ii_cfname('count')])), $tmptstr);
      $tmptstr = str_replace('{$lasttime}', ii_format_date($trs[ii_cfname('lasttime')], 11), $tmptstr);
      $tmptstr = str_replace('{$lastuser}', ii_htmlencode($trs[ii_cfname('lastuser')]), $tmptstr);
      $tmptstr = str_replace('{$lastuserstr}', urlencode(ii_htmlencode($trs[ii_cfname('lastuser')])), $tmptstr);
      $tmptstr = str_replace('{$sid}', ii_get_num($trs[ii_cfname('sid')]), $tmptstr);
      $tmptstr = str_replace('{$tid}', $trs[$nidfield], $tmptstr);
      $tmptstr = ii_creplace($tmptstr);
      $tmprstr .= $tmptstr;
    }
  }
  $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
  $tmpstr = str_replace('{$cpagestr}', $tcp -> get_pagenum(), $tmpstr);
  $tmpstr = str_replace('{$sid}', $tsid, $tmpstr);
  $tmpstr = str_replace('{$keyword}', ii_htmlencode($tkeyword, 1), $tmpstr);
  $tmpstr = str_replace('{$author}', ii_htmlencode($tauthor, 1), $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function wdja_cms_module()
{
  global $nuser_upload;
  switch(mm_ctype($_GET['type']))
  {
    case 'manage':
      return wdja_cms_module_manage();
      break;
    case 'list':
      return wdja_cms_module_topic_list();
      break;
    case 'detail':
      return wdja_cms_module_topic_detail();
      break;
    case 'release':
      return wdja_cms_module_topic_release();
      break;
    case 'reply':
      return wdja_cms_module_topic_reply();
      break;
    case 'edit':
      return wdja_cms_module_topic_edit();
      break;
    case 'search':
      return wdja_cms_module_search_list();
      break;
    case 'upload':
      if ($nuser_upload == 1) uu_upload_files_html('upload_html');
      break;
    default:
      return wdja_cms_module_index();
      break;
  }
}

//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
?>

<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
wdja_cms_admin_init();
$nurltype = 0;
$nsearch = 'topic,sort,id';
$ncontrol = 'select,htop,top,hidden';

function pp_manage_navigation()
{
  return ii_ireplace('manage-topic.navigation', 'tpl');
}

function wdja_cms_admin_manage_editdisp()
{
  global $conn;
  global $ndatabase, $nidfield, $nfpre;
  global $ngenre;
  $tbackurl = $_GET['backurl'];
  $tcontent_files_list = ii_left(ii_cstr($_POST['content_files_list']), 10000);
  $tid = ii_get_num($_GET['id']);
  if ($tid != 0)
  {
    $tsqlstr = "update $ndatabase set
    " . ii_cfname('author') . "='" . ii_left(ii_cstr($_POST['author']), 50) . "',
    " . ii_cfname('topic') . "='" . ii_left(ii_cstr($_POST['topic']), 50) . "',
    " . ii_cfname('sid') . "=" . ii_get_num($_POST['sid']) . ",
    " . ii_cfname('icon') . "=" . ii_get_num($_POST['icon']) . ",
    " . ii_cfname('content') . "='" . ii_left(ii_cstr($_POST['content']), 100000) . "',
    " . ii_cfname('content_files_list') . "='$tcontent_files_list',
    " . ii_cfname('ubb') . "=" . ii_get_num($_POST['ubb']) . "
    where $nidfield=$tid";
    $trs = ii_conn_query($tsqlstr, $conn);
    if ($trs)
    {
      $tsqlstr = "update $ndatabase set " . ii_cfname('sid') . "=" . ii_get_num($_POST['sid']) . " where " . ii_cfname('fid') . "=$tid";
      @ii_conn_query($tsqlstr, $conn);
      $upfid = $tid;
      uu_upload_update_database_note($ngenre, $tcontent_files_list, 'content_files', $upfid);
      wdja_cms_admin_msg(ii_itake('global.lng_public.edit_succeed', 'lng'), $tbackurl, 1);
    }
    else wdja_cms_admin_msg(ii_itake('global.lng_public.edit_failed', 'lng'), $tbackurl, 1);
  }
  else wdja_cms_admin_msg(ii_itake('global.lng_public.sudd', 'lng'), $tbackurl, 1);
}

function wdja_cms_admin_manage_deletedisp()
{
  global $conn;
  global $ndatabase, $nidfield, $nfpre;
  global $ngenre, $nuppath;
  $tbackurl = $_GET['backurl'];
  $tid = ii_get_num($_GET['id']);
  $tsqlstr = "select * from $ndatabase where $nidfield=$tid";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs)
  {
    $tVoteDatabase = mm_cndatabase($ngenre, 'vote');
    $tVoteIdfield = mm_cnidfield($ngenre, 'vote');
    $tVoteFpre = mm_cnfpre($ngenre, 'vote');
    $tVoteDataDatabase = mm_cndatabase($ngenre, 'vote_data');
    $tVoteDataIdfield = mm_cnidfield($ngenre, 'vote_data');
    $tVoteDataFpre = mm_cnfpre($ngenre, 'vote_data');
    $tVoteVoterDatabase = mm_cndatabase($ngenre, 'vote_voter');
    $tVoteVoterIdfield = mm_cnidfield($ngenre, 'vote_voter');
    $tVoteVoterFpre = mm_cnfpre($ngenre, 'vote_voter');
    uu_upload_delete_database_note($ngenre, $trs[$nidfield]);
    mm_dbase_delete($ndatabase, ii_cfname('fid'), $trs[$nidfield]);
    mm_dbase_delete($tVoteDatabase, $tVoteIdfield, $trs[ii_cfname('voteid')]);
    mm_dbase_delete($tVoteDataDatabase, ii_cfnames($tVoteDataFpre, 'fid'), $trs[ii_cfname('voteid')]);
    mm_dbase_delete($tVoteVoterDatabase, ii_cfnames($tVoteVoterFpre, 'fid'), $trs[ii_cfname('voteid')]);
    mm_dbase_delete($ndatabase, $nidfield, $trs[$nidfield]);
    wdja_cms_admin_msg(ii_itake('global.lng_public.succeed', 'lng'), $tbackurl, 1);
  }
  else wdja_cms_admin_msg(ii_itake('global.lng_public.sudd', 'lng'), $tbackurl, 1);
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
      wdja_cms_admin_manage_deletedisp();
      break;
    case 'control':
      wdja_cms_admin_controldisp($ndatabase, $nidfield, $nfpre, $ncontrol);
      break;
    case 'upload':
      uu_upload_files();
      break;
  }
}

function wdja_cms_admin_manage_edit()
{
  global $conn;
  global $ndatabase, $nidfield, $nfpre;
  $tid = ii_get_num($_GET['id']);
  $tsqlstr = "select * from $ndatabase where $nidfield=$tid";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs)
  {
    $tmpstr = ii_itake('manage-topic.edit', 'tpl');
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
  global $ngenre, $npagesize, $nlisttopx;
  global $ndatabase, $nidfield, $nfpre;
  $toffset = ii_get_num($_GET['offset']);
  $tsid = ii_get_num($_GET['sid']);
  $search_field = ii_get_safecode($_GET['field']);
  $search_keyword = ii_get_safecode($_GET['keyword']);
  $tdatabase = mm_cndatabase($ngenre, 'sort');
  $tidfield = mm_cnidfield($ngenre, 'sort');
  $tfpre = mm_cnfpre($ngenre, 'sort');
  $tmpstr = ii_itake('manage-topic.list', 'tpl');
  $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_ida}');
  $tmprstr = '';
  $tsqlstr = "select * from $ndatabase,$tdatabase where $ndatabase." . ii_cfname('sid') . "=$tdatabase.$tidfield and $ndatabase." . ii_cfname('fid') . "=0 and $tdatabase." . ii_cfnames($tfpre, 'lng') . "='$slng'";
  if ($tsid != 0) $tsqlstr .= " and $ndatabase." . ii_cfname('sid') . "=$tsid";
  if ($search_field == 'topic') $tsqlstr .= " and $ndatabase." . ii_cfname('topic') . " like '%" . $search_keyword . "%'";
  if ($search_field == 'sort') $tsqlstr .= " and $tdatabase." . ii_cfnames($tfpre, 'sort') . " like '%" . $search_keyword . "%'";
  if ($search_field == 'htop') $tsqlstr .= " and $ndatabase." . ii_cfname('htop') . "=" . ii_get_num($search_keyword);
  if ($search_field == 'top') $tsqlstr .= " and $ndatabase." . ii_cfname('top') . "=" . ii_get_num($search_keyword);
  if ($search_field == 'hidden') $tsqlstr .= " and $ndatabase." . ii_cfname('hidden') . "=" . ii_get_num($search_keyword);
  if ($search_field == 'id') $tsqlstr .= " and $ndatabase.$nidfield=" . ii_get_num($search_keyword);
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
      $tmptstr = str_replace('{$topic}', $ttopic, $tmpastr);
      $tmptstr = str_replace('{$topicstr}', ii_encode_scripts(ii_htmlencode($trs[ii_cfname('topic')])), $tmptstr);
      $tmptstr = str_replace('{$sort}', ii_htmlencode($trs[ii_cfnames($tfpre, 'sort')]), $tmptstr);
      $tmptstr = str_replace('{$time}', ii_get_date($trs[ii_cfname('time')]), $tmptstr);
      $tmptstr = str_replace('{$sid}', ii_get_num($trs[ii_cfname('sid')]), $tmptstr);
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

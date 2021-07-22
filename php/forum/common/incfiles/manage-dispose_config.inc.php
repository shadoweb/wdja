<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
wdja_cms_admin_init();

function wdja_cms_admin_manage_transferdisp()
{
  global $conn;
  global $ndatabase, $nidfield, $nfpre;
  $tbackurl = $_GET['backurl'];
  $tsort1 = ii_get_num($_POST['sort1'], 0);
  $tsort2 = ii_get_num($_POST['sort2'], 0);
  $tcondition = $_POST['condition'];
  if (is_array($tcondition)) $tcondition = implode(',', $tcondition);
  $tstart_time = ii_get_safecode($_POST['start_time']);
  $tend_time = ii_get_safecode($_POST['end_time']);
  $tauthor = ii_get_safecode($_POST['author']);
  if ($tsort1 == 0 || $tsort2 == 0 || ii_isnull($tcondition)) wdja_cms_admin_msg(ii_itake('manage-dispose.transfer_failed', 'lng'), $tbackurl, 1);
  else
  {
    $tsqlstr = "update $ndatabase set " . ii_cfname('sid') . "=$tsort2 where " . ii_cfname('sid') . "=$tsort1";
    if (!ii_cinstr($tcondition, 'all', ','))
    {
      $tsqlstr .= " and ($nidfield=0";
      if (ii_cinstr($tcondition, 'elite', ',')) $tsqlstr .= " or " . ii_cfname('elite') . "=1";
      if (ii_cinstr($tcondition, 'lock', ',')) $tsqlstr .= " or " . ii_cfname('lock') . "=1";
      if (ii_cinstr($tcondition, 'top', ',')) $tsqlstr .= " or " . ii_cfname('top') . "=1";
      if (ii_cinstr($tcondition, 'htop', ',')) $tsqlstr .= " or " . ii_cfname('htop') . "=1";
      if (ii_cinstr($tcondition, 'hidden', ',')) $tsqlstr .= " or " . ii_cfname('hidden') . "=1";
      $tsqlstr .= ")";
    }
    if (!ii_isnull($tauthor)) $tsqlstr .= " or " . ii_cfname('author') . "='$tauthor'";
    if (ii_isdate($tstart_time)) $tsqlstr .=  " and TO_DAYS('" . $tstart_time . "')-TO_DAYS(" . ii_cfname('time') . ")<=0";
    if (ii_isdate($tend_time)) $tsqlstr .=  " and TO_DAYS('" . $tend_time . "')-TO_DAYS(" . ii_cfname('time') . ")>=0";
    $trs = ii_conn_query($tsqlstr, $conn);
    if ($trs) wdja_cms_admin_msg(ii_itake('manage-dispose.transfer_succeed', 'lng'), $tbackurl, 1);
    else wdja_cms_admin_msg(ii_itake('global.lng_public.sudd', 'lng'), $tbackurl, 1);
  }
}

function wdja_cms_admin_manage_deletedisp()
{
  global $conn;
  global $ndatabase, $nidfield, $nfpre;
  global $ngenre, $nuppath;
  $tbackurl = $_GET['backurl'];
  $tsort = ii_get_num($_POST['sort'], 0);
  $tcondition = $_POST['condition'];
  if (is_array($tcondition)) $tcondition = implode(',', $tcondition);
  $tstart_time = ii_get_safecode($_POST['start_time']);
  $tend_time = ii_get_safecode($_POST['end_time']);
  $tauthor = ii_get_safecode($_POST['author']);
  if ($tsort == 0 || ii_isnull($tcondition)) wdja_cms_admin_msg(ii_itake('manage-dispose.delete_failed', 'lng'), $tbackurl, 1);
  else
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
    $tsqlstr = "select * from $ndatabase where " . ii_cfname('fid') . "=0";
    if ($tsort != -1) $tsqlstr .= " and " . ii_cfname('sid') . "=$tsort";
    if (!ii_cinstr($tcondition, 'all', ','))
    {
      $tsqlstr .= " and ($nidfield=0";
      if (ii_cinstr($tcondition, 'elite', ',')) $tsqlstr .= " or " . ii_cfname('elite') . "=1";
      if (ii_cinstr($tcondition, 'lock', ',')) $tsqlstr .= " or " . ii_cfname('lock') . "=1";
      if (ii_cinstr($tcondition, 'top', ',')) $tsqlstr .= " or " . ii_cfname('top') . "=1";
      if (ii_cinstr($tcondition, 'htop', ',')) $tsqlstr .= " or " . ii_cfname('htop') . "=1";
      if (ii_cinstr($tcondition, 'hidden', ',')) $tsqlstr .= " or " . ii_cfname('hidden') . "=1";
      $tsqlstr .= ")";
    }
    if (!ii_isnull($tauthor)) $tsqlstr .= " or " . ii_cfname('author') . "='$tauthor'";
    if (ii_isdate($tstart_time)) $tsqlstr .=  " and TO_DAYS('" . $tstart_time . "')-TO_DAYS(" . ii_cfname('time') . ")<=0";
    if (ii_isdate($tend_time)) $tsqlstr .=  " and TO_DAYS('" . $tend_time . "')-TO_DAYS(" . ii_cfname('time') . ")>=0";
    $trs = ii_conn_query($tsqlstr, $conn);
    while ($trow = ii_conn_fetch_array($trs))
    {
      uu_upload_delete_database_note($ngenre, $trow[$nidfield]);
      mm_dbase_delete($ndatabase, ii_cfname('fid'), $trow[$nidfield]);
      mm_dbase_delete($tVoteDatabase, $tVoteIdfield, $trow[ii_cfname('voteid')]);
      mm_dbase_delete($tVoteDataDatabase, ii_cfnames($tVoteDataFpre, 'fid'), $trow[ii_cfname('voteid')]);
      mm_dbase_delete($tVoteVoterDatabase, ii_cfnames($tVoteVoterFpre, 'fid'), $trow[ii_cfname('voteid')]);
      mm_dbase_delete($ndatabase, $nidfield, $trow[$nidfield]);
    }
    wdja_cms_admin_msg(ii_itake('manage-dispose.delete_succeed', 'lng'), $tbackurl, 1);
  }
}

function wdja_cms_admin_manage_updatedisp()
{
  global $conn, $ngenre;
  global $ndatabase, $nidfield, $nfpre;
  $tbackurl = $_GET['backurl'];
  $tsort = ii_get_num($_POST['sort'], 0);
  $tcondition = $_POST['condition'];
  if (is_array($tcondition)) $tcondition = implode(',', $tcondition);
  $tstart_time = ii_get_safecode($_POST['start_time']);
  $tend_time = ii_get_safecode($_POST['end_time']);
  if ($tsort == 0 || ii_isnull($tcondition)) wdja_cms_admin_msg(ii_itake('manage-dispose.update_failed', 'lng'), $tbackurl, 1);
  else
  {
    $tdatabase = mm_cndatabase($ngenre, 'sort');
    $tidfield = mm_cnidfield($ngenre, 'sort');
    $tfpre = mm_cnfpre($ngenre, 'sort');
    $tsqlstr = "select * from $tdatabase where $tidfield>0";
    if ($tsort != -1) $tsqlstr .= " and $tidfield=$tsort";
    $trs = ii_conn_query($tsqlstr, $conn);
    while ($trow = ii_conn_fetch_array($trs))
    {
      $tsqlstr = "select count($nidfield) from $ndatabase where " . ii_cfname('sid') . "=$trow[$tidfield]";
      $trs2 = ii_conn_query($tsqlstr, $conn);
      $trs2 = ii_conn_fetch_array($trs2);
      if ($trs2) $tcount1 = $trs2[0];
      $tsqlstr = "select count($nidfield) from $ndatabase where " . ii_cfname('fid') . "=0 and " . ii_cfname('sid') . "=$trow[$tidfield]";
      $trs2 = ii_conn_query($tsqlstr, $conn);
      $trs2 = ii_conn_fetch_array($trs2);
      if ($trs2) $tcount2 = $trs2[0];
      $tsqlstr = "select count($nidfield) from $ndatabase where " . ii_cfname('fid') . "=0 and " . ii_cfname('sid') . "=$trow[$tidfield] and TO_DAYS('" . ii_now() . "')-TO_DAYS(" . ii_cfname('time') . ")=0";
      $trs2 = ii_conn_query($tsqlstr, $conn);
      $trs2 = ii_conn_fetch_array($trs2);
      if ($trs2) $tcount3 = $trs2[0];
      $tsqlstr = "update $tdatabase set ";
      if (ii_cinstr($tcondition, 'all', ',') || ii_cinstr($tcondition, 'nnote', ',')) $tsqlstr .= ii_cfnames($tfpre, 'nnote') . "=$tcount1,";
      if (ii_cinstr($tcondition, 'all', ',') || ii_cinstr($tcondition, 'ntopic', ',')) $tsqlstr .= ii_cfnames($tfpre, 'ntopic') . "=$tcount2,";
      if (ii_cinstr($tcondition, 'all', ',') || ii_cinstr($tcondition, 'today_ntopic', ',')) $tsqlstr .= ii_cfnames($tfpre, 'today_ntopic') . "=$tcount3,";
      $tsqlstr = ii_get_lrstr($tsqlstr, ',', 'leftr');
      $tsqlstr .= " where $tidfield=$trow[$tidfield]";
      @ii_conn_query($tsqlstr, $conn);
    }
    wdja_cms_admin_msg(ii_itake('manage-dispose.update_succeed', 'lng'), $tbackurl, 1);
  }
}

function wdja_cms_admin_manage_action()
{
  global $ndatabase, $nidfield, $nfpre, $ncontrol;
  switch($_GET['action'])
  {
    case 'transfer':
      wdja_cms_admin_manage_transferdisp();
      break;
    case 'delete':
      wdja_cms_admin_manage_deletedisp();
      break;
    case 'update':
      wdja_cms_admin_manage_updatedisp();
      break;
  }
}

function pp_manage_navigation()
{
  return ii_ireplace('manage-dispose.navigation', 'tpl');
}

function wdja_cms_admin_manage_transfer()
{
  return ii_ireplace('manage-dispose.transfer', 'tpl');
}

function wdja_cms_admin_manage_delete()
{
  return ii_ireplace('manage-dispose.delete', 'tpl');
}

function wdja_cms_admin_manage_update()
{
  return ii_ireplace('manage-dispose.update', 'tpl');
}

function wdja_cms_admin_manage()
{
  switch($_GET['type'])
  {
    case 'transfer':
      return wdja_cms_admin_manage_transfer();
      break;
    case 'delete':
      return wdja_cms_admin_manage_delete();
      break;
    case 'update':
      return wdja_cms_admin_manage_update();
      break;
    default:
      return wdja_cms_admin_manage_transfer();
      break;
  }
}
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
?>

<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
$ncontrol = 'select,delete';
$nsearch = 'genre,filename,id';

function pp_manage_navigation()
{
  return ii_ireplace('manage.navigation', 'tpl');
}

function wdja_cms_admin_manage_controldisp()
{
  global $conn;
  global $ndatabase, $nidfield, $nfpre;
  $tsid = $_POST['sel_ids'];
  $tbackurl = $_GET['backurl'];
  if (ii_cidary($tsid))
  {
    $ti = 0; $tib = 0; $tic = 0;
    $tsqlstr = "select * from $ndatabase where $nidfield in ($tsid)";
    $trs = ii_conn_query($tsqlstr, $conn);
    while ($trow = ii_conn_fetch_array($trs))
    {
      $tfilename = $trow[ii_cfname('filename')];
      if (!(ii_left($tfilename, 1) == '/')) $tfilename = ii_get_actual_route($trow[ii_cfname('genre')] . '/' . $trow[ii_cfname('filename')]);
      if (unlink($tfilename)) $tib += 1;
      else $tic += 1;
      $ti += 1;
      mm_dbase_delete($ndatabase, $nidfield, $trow[$nidfield]);
    }
    $tdelete_info = ii_itake('manage.delete_info', 'lng');
    $tdelete_info = str_replace('[ti]', $ti, $tdelete_info);
    $tdelete_info = str_replace('[tib]', $tib, $tdelete_info);
    $tdelete_info = str_replace('[tic]', $tic, $tdelete_info);
    wdja_cms_admin_msg($tdelete_info, $tbackurl, 1);
  }
  else
  {
    wdja_cms_admin_msg(ii_itake('global.lng_public.sudd', 'lng'), $tbackurl, 1);
  }
}

function wdja_cms_admin_manage_action()
{
  switch($_GET['action'])
  {
    case 'control':
      wdja_cms_admin_manage_controldisp();
      break;
  }
}

function wdja_cms_admin_manage_list()
{
  global $ndatabase, $nidfield, $nfpre, $npagesize, $slng;
  $toffset = ii_get_num($_GET['offset']);
  $search_field = ii_get_safecode($_GET['field']);
  $search_keyword = ii_get_safecode($_GET['keyword']);
  $tmpstr = ii_itake('manage.list', 'tpl');
  $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_ida}');
  $tmprstr = '';
  $tsqlstr = "select * from $ndatabase where " . ii_cfname('lng') . " = '".$slng."'";
  if ($search_field == 'filename') $tsqlstr .= " and " . ii_cfname('filename') . " like '%" . $search_keyword . "%'";
  if ($search_field == 'genre') $tsqlstr .= " and " . ii_cfname('genre') . " like '%" . $search_keyword . "%'";
  if ($search_field == 'valid') $tsqlstr .= " and " . ii_cfname('valid') . "=" . ii_get_num($search_keyword);
  if ($search_field == 'id') $tsqlstr .= " and $nidfield=" . ii_get_num($search_keyword);
  $tsqlstr .= " order by $nidfield desc";
  $tcp = new cc_cutepage;
  $tcp -> id = $nidfield;
  $tcp -> sqlstr = $tsqlstr;
  $tcp -> offset = $toffset;
  $tcp -> pagesize = $npagesize;
  $tcp -> init();
  $trsary = $tcp -> get_rs_array();
  $font_disabled = ii_itake('global.tpl_config.font_disabled', 'tpl');
  $font_reds = ii_itake('global.tpl_config.font_red', 'tpl');
  $teffective = ii_itake('manage.effective', 'lng');
  $tnoneffective = ii_itake('manage.noneffective', 'lng');
  $tnoneffective1 = ii_itake('manage.noneffective1', 'lng');
  $tnoneffective2 = ii_itake('manage.noneffective2', 'lng');
  if ($search_field == 'filename') $font_red = ii_itake('global.tpl_config.font_red', 'tpl');
  if (is_array($trsary))
  {
    foreach($trsary as $trs)
    {
      $tvalid = $trs[ii_cfname('valid')];
      if ($tvalid == 1) $tstate = str_replace('{$explain}', $teffective, $font_reds);
      else
      {
        $tvoidreason = $trs[ii_cfname('voidreason')];
        switch($tvoidreason)
        {
          case 1:
            $tstate = str_replace('{$explain}', $tnoneffective1, $font_disabled);
            break;
          case 2:
            $tstate = str_replace('{$explain}', $tnoneffective2, $font_disabled);
            break;
          default:
            $tstate = str_replace('{$explain}', $tnoneffective, $font_disabled);
            break;
        }
      }
      $tfilename = $trs[ii_cfname('filename')];
      $tfilename = ii_get_lrstr($tfilename, '/', 'right');
      if (isset($font_red))
      {
        $tfont_red = str_replace('{$explain}', $search_keyword, $font_red);
        $tfilename = str_replace($search_keyword, $tfont_red, $tfilename);
      }
      $tmptstr = str_replace('{$filename}', $tfilename, $tmpastr);
      $tmptstr = str_replace('{$true_filename}', $trs[ii_cfname('filename')], $tmptstr);
      $tmptstr = str_replace('{$user}', $trs[ii_cfname('user')], $tmptstr);
      $tmptstr = str_replace('{$genre}', $trs[ii_cfname('genre')], $tmptstr);
      $tmptstr = str_replace('{$time}', $trs[ii_cfname('time')], $tmptstr);
      $tmptstr = str_replace('{$validity}', $tstate, $tmptstr);
      $tmptstr = str_replace('{$id}', $trs[$nidfield], $tmptstr);
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
    case 'list':
      return wdja_cms_admin_manage_list();
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

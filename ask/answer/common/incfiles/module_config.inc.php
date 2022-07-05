<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
function wdja_cms_module_adddisp()
{
  wdja_cms_web_noout();
  global $variable, $ngenre, $nlng;
  global $conn, $nusername;
  global $ndatabase, $nidfield, $nfpre;
  $tbackurl = $_GET['backurl'];
  $tfid = ii_get_num($_GET['fid']);
  $tauthor = $nusername;
  if (ii_isnull($tauthor)) $tauthor = ii_itake('config.df_username', 'lng');
  $tcontent = ii_left(ii_cstr($_POST['content']), 10000000);
  if (!ii_isnull($tcontent) && $tfid != 0)
  {
    $tuserip = ii_get_client_ip();
    $ttimeout = ii_get_num($variable[ii_cvgenre($ngenre) . '.timeout'], -1);
    if ($ttimeout != -1)
    {
      $tsqlstr = "select * from $ndatabase where " . ii_cfname('authorip') . "='$tuserip' order by " . ii_cfname('time') . " desc limit 0,1";
      $trs = ii_conn_query($tsqlstr, $conn);
      $trs = ii_conn_fetch_array($trs);
      if ($trs)
      {
        if (ii_datediff('s', $trs[ii_cfname('time')], ii_now()) <= $ttimeout) mm_imessage(ii_itake('module.error0', 'lng'), '-1');
      }
    }
    $tsqlstr = "insert into $ndatabase (
    " . ii_cfname('author') . ",
    " . ii_cfname('authorip') . ",
    " . ii_cfname('content') . ",
    " . ii_cfname('time') . ",
    " . ii_cfname('fid') . ",
    " . ii_cfname('hidden') . ",
    " . ii_cfname('lng') . "
    ) values (
    '$tauthor',
    '$tuserip',
    '$tcontent',
    '" . ii_now() . "',
    $tfid,
    '1',
    '$nlng'
    )";
    $trs = ii_conn_query($tsqlstr, $conn);
    if ($trs)
    {
      if (!ii_isnull($tbackurl)) mm_imessage(ii_itake('global.lng_public.succeed', 'lng'), $tbackurl);
      else mm_imessage(ii_itake('global.lng_public.succeed', 'lng'), '-1');
    }
    else mm_imessage(ii_itake('global.lng_public.sudd', 'lng'), '-1');
  }
  else mm_imessage(ii_itake('module.error1', 'lng'), '-1');
}

function wdja_cms_module_action()
{
  switch($_GET['action'])
  {
    case 'add':
      wdja_cms_module_adddisp();
      break;
  }
}

function wdja_cms_module_list()
{
  global $conn, $nlng;
  global $nlisttopx, $npagesize;
  global $ndatabase, $nidfield, $nfpre;
  $tfid = ii_get_num($_GET['fid']);
  $task = mm_get_field('ask',$tfid,'topic');
  $tcontent = str_replace("<br>","",mm_get_field('ask',$tfid,'content'));
  $tcontent = trim(str_replace("&nbsp;","",$tcontent));
  $tcontent = ii_left(strip_tags($tcontent), 60);
  $toffset = ii_get_num($_GET['offset']);
  $tmpstr = ii_itake('module.list', 'tpl');
  $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_ida}');
  $tmprstr = '';
  $tsqlstr = "select * from $ndatabase where " . ii_cfname('hidden') . "=0 and " . ii_cfname('lng') . "='$nlng' and " . ii_cfname('fid') . "=$tfid order by " . ii_cfname('good') . " desc," . ii_cfname('time') . " desc";
  mm_cntitle($task.ii_itake('api.answer_list', 'lng'));
  mm_cnkeywords($task);
  mm_cndescription($task.ii_itake('api.answer_list', 'lng').','.$tcontent);
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
      $tmptstr = $tmpastr;
      foreach ($trs as $key => $val)
      {
        $tkey = ii_get_lrstr($key, '_', 'rightr');
        $GLOBALS['RS_' . $tkey] = $val;
        if($tkey == 'good' && !is_numeric($tkey)){
          if($val == 1) $tmptstr = str_replace('{$best}', '<div class="lists-item-best">'.ii_itake('global.ask/answer:api.best', 'lng').'</div>', $tmptstr);
          else  $tmptstr = str_replace('{$best}', '', $tmptstr);
        } 
        else $tmptstr = str_replace('{$' . $tkey . '}', ii_htmlencode($val), $tmptstr);
      }
      $tmptstr = str_replace('{$id}', $trs[$nidfield], $tmptstr);
      $tmptstr = ii_creplace($tmptstr);
      $tmprstr .= $tmptstr;
    }
  }
  $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
  $tmpstr = str_replace('{$fid}', $tfid, $tmpstr);
  $tmpstr = str_replace('{$ask}', $task, $tmpstr);
  $tmpstr = str_replace('{$nlng}', $nlng, $tmpstr);
  $tmpstr = str_replace('{$cpagestr}', $tcp -> get_pagenum(), $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function wdja_cms_module()
{
  switch(mm_ctype($_GET['type']))
  {
    case 'list':
      return wdja_cms_module_list();
      break;
    default:
      return wdja_cms_module_list();
      break;
  }
}
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
?>
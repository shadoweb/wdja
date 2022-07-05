<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************

function wdja_cms_module_adddisp()
{
  global $ctype;
  $ctype = 'add';
  $Err = array();
  global $conn;
  global $nlng, $nuri;
  global $ngenre,$ndatabase, $nidfield, $nfpre;
  $tip = ii_get_client_ip();
  if (wdja_cms_ckip_postmax($ngenre)) mm_imessage(ii_itake('config.postmax_tips', 'lng'), '-1');
  if (!mm_check_token()) mm_imessage(ii_itake('global.lng_error.token_error', 'lng'), '-1');
  if (!mm_ck_valcode()) $Err[count($Err)] = ii_itake('global.lng_error.valcode', 'lng');
  $tckstr = 'name:' . ii_itake('config.name', 'lng') . ',mobile:' . ii_itake('config.mobile', 'lng') . ',content:' . ii_itake('config.content', 'lng');
  $tary = explode(',', $tckstr);
  foreach ($tary as $val)
  {
    $tvalary = explode(':', $val);
    if (ii_isnull($_POST[$tvalary[0]])) $Err[count($Err)] = str_replace('[]', '[' . $tvalary[1] . ']', ii_itake('global.lng_error.insert_empty', 'lng'));
  }
  if (count($Err) > 0) mm_imessage($Err[0], '-1');
  $tsqlstr = "insert into $ndatabase (
  " . ii_cfname('name') . ",
  " . ii_cfname('ip') . ",
  " . ii_cfname('sex') . ",
  " . ii_cfname('mobile') . ",
  " . ii_cfname('email') . ",
  " . ii_cfname('address') . ",
  " . ii_cfname('title') . ",
  " . ii_cfname('content') . ",
  " . ii_cfname('hidden') . ",
  " . ii_cfname('lng') . ",
  " . ii_cfname('token') . ",
  " . ii_cfname('time') . "
  ) values (
  '" . ii_left(ii_cstr($_POST['name']), 50) . "',
  '" . $tip . "',
  " . ii_get_num($_POST['sex']) . ",
  '" . ii_left(ii_cstr($_POST['mobile']), 50) . "',
  '" . ii_left(ii_cstr($_POST['email']), 50) . "',
  '" . ii_left(ii_cstr($_POST['address']), 255) . "',
  '" . ii_left(ii_cstr($_POST['title']), 250) . "',
  '" . ii_left(ii_cstr($_POST['content']), 10000) . "',
  " . ii_get_num($_POST['hidden']) . ",
  '$nlng',
  '" . ii_left(ii_cstr($_POST['token']), 250) . "',
  '" . ii_now() . "'
  )";
  $trs = ii_conn_query($tsqlstr, $conn);
  if ($trs) {
      $gmail = ii_itake('global.support/global:email.message_mail','lng');
      $gtitle = ii_itake('global.support/global:email.message_title','lng');
      $gbody = ii_itake('global.support/global:email.message_body','lng');
      mm_sendemail($gmail, $gtitle, $gbody);
      mm_imessage(ii_itake('global.lng_public.succeed', 'lng'), $nuri);
  } else {
      mm_imessage(ii_itake('global.lng_public.sudd', 'lng'), '-1');
  }
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
  global $nlng, $ngenre;
  global $ndatabase, $nidfield, $nfpre;
  global $npagesize, $nlisttopx;
  $toffset = ii_get_num($_GET['offset']);
  $tmpstr = ii_itake('module.list', 'tpl');
  $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_ida}');
  $tmprstr = '';
  $tsqlstr = "select * from $ndatabase where " . ii_cfname('hidden') . "=0 and " . ii_cfname('lng') . "='$nlng' order by " . ii_cfname('time') . " desc";
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
      if (ii_isnull($trs[ii_cfname('reply')])) $treplyis = 0;
      else $treplyis = 1;
      $tmptstr = mm_cvalhtml($tmpastr, $treplyis, '{@admin_reply}');
      foreach ($trs as $key => $val)
      {
        $tkey = ii_get_lrstr($key, '_', 'rightr');
        $GLOBALS['RS_' . $tkey] = $val;
        $tmptstr = str_replace('{$' . $tkey . '}', ii_htmlencode($val), $tmptstr);
      }
      $tmptstr = str_replace('{$id}', $trs[$nidfield], $tmptstr);
      $tmptstr = ii_creplace($tmptstr);
      $tmprstr .= $tmptstr;
    }
  }
  $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
  $tmpstr = str_replace('{$genre}', $ngenre, $tmpstr);
  $tmpstr = str_replace('{$cpagestr}', $tcp -> get_pagenum(), $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function wdja_cms_module_add()
{
  global $nvalidate, $ngenre;
  $tmpstr = ii_itake('module.add', 'tpl');
  $tmpstr = mm_cvalhtml($tmpstr, $nvalidate, '{@recurrence_valcode}');
  $tmpstr = str_replace('{$genre}', $ngenre, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function wdja_cms_module_index()
{
  global $nvalidate, $ngenre;
  $tmpstr = ii_itake('module.index', 'tpl');
  $tmpstr = mm_cvalhtml($tmpstr, $nvalidate, '{@recurrence_valcode}');
  $tmpstr = str_replace('{$genre}', $ngenre, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  if (ii_isnull($tmpstr)) $tmpstr = wdja_cms_module_list();
  return $tmpstr;
}

function wdja_cms_module()
{
  switch(mm_ctype($_GET['type']))
  {
    case 'list':
      return wdja_cms_module_list();
      break;
    case 'add':
      return wdja_cms_module_add();
      break;
    case 'index':
      return wdja_cms_module_index();
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
<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
wdja_cms_admin_init();
$nsearch = 'topic,id';
$ncontrol = 'select,hidden,delete';

function pp_manage_navigation()
{
  return ii_ireplace('manage.navigation', 'tpl');
}

function pp_get_post_infos($count)
{
  $tmpstr = '';
  for ($i = 1; $i <= $count; $i ++)
  {
    $tmpstr .= ii_cstr($_POST['infos_topic' . $i]) . '{:::}' . ii_cstr($_POST['infos_link' . $i]) . '{|||}';
  }
  if (!ii_isnull($tmpstr)) $tmpstr = ii_left($tmpstr, mb_strlen($tmpstr, CHARSET) - 5);
  return $tmpstr;
}

function wdja_cms_admin_manage_adddisp()
{
  global $conn;
  global $ngenre, $slng;
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
  $ttype = ii_get_num($_POST['type']);
  $tinputs_type = ii_left(ii_cstr($_POST['inputs_type']), 50);
  switch($ttype)
  {
    case '0':
      $tcontent = ii_left(ii_cstr($_POST['input']), 250);
      break;
    case '1':
      $tcontent = ii_left(ii_cstr($_POST['image']), 250);
      break;
    case '2':
      $tcontent = ii_left(ii_cstr($_POST['text']), 250);
      break;
    case '3':
      $tcontent = ii_left(pp_get_post_infos(ii_get_num($_POST['infos_date_option'])), 1200);
      break;
    case '4':
      $tcontent = ii_left(ii_cstr($_POST['images_list']), 10000);
      break;
    case '5':
	  if ($nsaveimages == '1') $tcontent = ii_left(ii_cstr(saveimages($_POST['content'])), 100000);
	  else $tcontent =ii_left(ii_cstr($_POST['content']), 100000);
      break;
    default:
      $tcontent = ii_left(ii_cstr($_POST['input']), 250);
      break;
  }
  $tcontent_atts_list = ii_left(ii_cstr($_POST['content_atts_list']), 10000);
  $tsqlstr = "insert into $ndatabase (
  " . ii_cfname('topic') . ",
  " . ii_cfname('type') . ",
  " . ii_cfname('inputs_type') . ",
  " . ii_cfname('images_tpl') . ",
  " . ii_cfname('content') . ",
  " . ii_cfname('content_atts_list') . ",
  " . ii_cfname('time') . ",
  " . ii_cfname('update') . ",
  " . ii_cfname('hidden') . ",
  " . ii_cfname('lng') . "
  ) values (
  '" . ii_left(ii_cstr($_POST['topic']), 50) . "',
  '$ttype',
  '$tinputs_type',
  '" . ii_left(ii_cstr($_POST['images_tpl']), 50) . "',
  '$tcontent',
  '$tcontent_atts_list',
  '" . ii_now() . "',
  '" . ii_now() . "',
  " . ii_get_num($_POST['hidden']) . ",
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
  global $conn;
  global $ngenre;
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
  $ttype = ii_get_num($_POST['type']);
  $tinputs_type = ii_left(ii_cstr($_POST['inputs_type']), 50);
  switch($ttype)
  {
    case '0':
      $tcontent = ii_left(ii_cstr($_POST['input']), 250);
      break;
    case '1':
      $tcontent = ii_left(ii_cstr($_POST['image']), 250);
      break;
    case '2':
      $tcontent = ii_left(ii_cstr($_POST['text']), 250);
      break;
    case '3':
      $tcontent = ii_left(pp_get_post_infos(ii_get_num($_POST['infos_date_option'])), 1200);
      break;
    case '4':
      $tcontent = ii_left(ii_cstr($_POST['images_list']), 10000);
      break;
    case '5':
	  if ($nsaveimages == '1') $tcontent = ii_left(ii_cstr(saveimages($_POST['content'])), 100000);
	  else $tcontent =ii_left(ii_cstr($_POST['content']), 100000);
      break;
    default:
      $tcontent = ii_left(ii_cstr($_POST['input']), 250);
      break;
  }
  $tcontent_atts_list = ii_left(ii_cstr($_POST['content_atts_list']), 10000);
  $tid = ii_get_num($_GET['id']);
  $tsqlstr = "update $ndatabase set
  " . ii_cfname('topic') . "='" . ii_left(ii_cstr($_POST['topic']), 50) . "',
  " . ii_cfname('type') . "='$ttype',
  " . ii_cfname('inputs_type') . "='$tinputs_type',
  " . ii_cfname('content') . "='$tcontent',
  " . ii_cfname('images_tpl') . "='" . ii_left(ii_cstr($_POST['images_tpl']), 50) . "',
  " . ii_cfname('content_atts_list') . "='$tcontent_atts_list',
  " . ii_cfname('update') . "='" . ii_now() . "',
  " . ii_cfname('hidden') . "=" . ii_get_num($_POST['hidden']) . "
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

function wdja_cms_admin_manage_action()
{
  global $ndatabase, $nidfield, $nfpre, $ncontrol;
  switch($_GET['action'])
  {
    case 'add':
      wdja_cms_admin_manage_adddisp();
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
    case 'upload':
      uu_upload_files();
      break;
  }
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
  global $ngenre;
  global $conn;
  global $ndatabase, $nidfield, $nfpre, $nupsimg, $nupsimgs;
  $tid = ii_get_num($_GET['id']);
  $tsqlstr = "select * from $ndatabase where $nidfield=$tid";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs)
  {
    $tmpstr = ii_itake('manage.edit', 'tpl');
    $ttopic = $trs[ii_cfname('topic')];
    $ttype = $trs[ii_cfname('type')];
    $timages_tpl = $trs[ii_cfname('images_tpl')];
    $tcontent = ii_get_safecode($trs[ii_cfname('content')]);
    $tcontent_atts_list = $trs[ii_cfname('content_atts_list')];
    $thidden = $trs[ii_cfname('hidden')];
    $ttype = $trs[ii_cfname('type')];
    $tinputs_type = $trs[ii_cfname('inputs_type')];
    $tinput = '';
    $timage = '/'.$ngenre.'/common/upload/noimg.gif';
    $ttext = '';
    $tinputs = '{:::}{|||}{:::}';
    $timages_list = '';
    $tedit = '';
    $tnone = 'style="display:none;"';
    $ttype0 = $tnone;
    $ttype1 = $tnone;
    $ttype2 = $tnone;
    $ttype3 = $tnone;
    $ttype4 = $tnone;
    $ttype5 = $tnone;
	  switch($ttype)
	  {
	    case '0':
	    	$tinput = $tcontent;
	    	$ttype0 = '';
	      break;
	    case '1':
	    	$timage = $tcontent;
	    	$ttype1 = '';
	      break;
	    case '2':
	    	$ttext = $tcontent;
	    	$ttype2 = '';
	      break;
	    case '3':
	    	$tinputs = $tcontent;
	    	$ttype3 = '';
	      break;
	    case '4':
	    	$timages_list = $tcontent;
	    	$ttype4 = '';
	      break;
	    case '5':
	    	$tedit = $tcontent;
	    	$ttype5 = '';
	      break;
	    default:
	    	$tinput = $tcontent;
	    	$ttype0 = '';
	      break;
	  }
    $GLOBALS['RS_content'] = $tedit;
    $tmpastr = ii_ctemplate_infos($tmpstr, '{@recurrence_ida}');
    $tmprstr = '';
    if (!ii_isnull($tinputs))
    {
      $ticount = 1;
      $tinfosary = explode('{|||}', $tinputs);
      $tinfoscount = count($tinfosary);
      for ($i = 1; $i <= $tinfoscount; $i ++)
      {
        $tinfostr = $tinfosary[$i - 1];
        if (!ii_isnull($tinfostr))
        {
          $tinfostrary = explode('{:::}', $tinfostr);
          if (count($tinfostrary) == 2)
          {
            $tmptstr = str_replace('{$infos_topic}', $tinfostrary[0], $tmpastr);
            $tmptstr = str_replace('{$infos_link}', $tinfostrary[1], $tmptstr);
            $tmptstr = str_replace('{$inop_i}', $ticount, $tmptstr);
            $ticount += 1;
            $tmprstr .= $tmptstr;
          }
        }
      }
    }
    else $tinfoscount = 0;
    $tmpstr = str_replace(WDJA_CINFO_INFOS, $tmprstr, $tmpstr);
    $tmpstr = str_replace('{$inop_count}', $tinfoscount, $tmpstr);
    $tmpstr = str_replace('{$id}', $tid, $tmpstr);
    $tmpstr = str_replace('{$topic}', $ttopic, $tmpstr);
    $tmpstr = str_replace('{$type}', $ttype, $tmpstr);
    $tmpstr = str_replace('{$images_tpl}', $timages_tpl, $tmpstr);
    $tmpstr = str_replace('{$input}', $tinput, $tmpstr);
    $tmpstr = str_replace('{$image}', $timage, $tmpstr);
    $tmpstr = str_replace('{$text}', $ttext, $tmpstr);
    $tmpstr = str_replace('{$images_list}', $timages_list, $tmpstr);
    $tmpstr = str_replace('{$content}', $tedit, $tmpstr);
    $tmpstr = str_replace('{$content_atts_list}', $tcontent_atts_list, $tmpstr);
    $tmpstr = str_replace('{$inputs_type}', $tinputs_type, $tmpstr);
    $tmpstr = str_replace('{$hidden}', $thidden, $tmpstr);
    $tmpstr = str_replace('{$type0}', $ttype0, $tmpstr);
    $tmpstr = str_replace('{$type1}', $ttype1, $tmpstr);
    $tmpstr = str_replace('{$type2}', $ttype2, $tmpstr);
    $tmpstr = str_replace('{$type3}', $ttype3, $tmpstr);
    $tmpstr = str_replace('{$type4}', $ttype4, $tmpstr);
    $tmpstr = str_replace('{$type5}', $ttype5, $tmpstr);
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
  $tsqlstr = "select * from $ndatabase where " . ii_cfname('lng') . "='$slng'";
  if ($search_field == 'topic') $tsqlstr .= " and " . ii_cfname('topic') . " like '%" . $search_keyword . "%'";
  if ($search_field == 'good') $tsqlstr .= " and " . ii_cfname('good') . "=" . ii_get_num($search_keyword);
  if ($search_field == 'id') $tsqlstr .= " and $nidfield=" . ii_get_num($search_keyword);
  $tsqlstr .= " order by " . ii_cfname('time') . " desc";
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
      if ($trs[ii_cfname('good')] == 1) $ttopic .= $postfix_good;
      $ttype = ii_get_num($trs[ii_cfname('type')]);
      $tinputs_type = $trs[ii_cfname('inputs_type')];
      $label_name = "{\$=api_label_name('".ii_get_num($trs[$nidfield])."')}";
      $label_content = "{\$=api_label_content('".ii_get_num($trs[$nidfield])."')}";
      $tmptstr = str_replace('{$topic}', $ttopic, $tmpastr);
      $tmptstr = str_replace('{$topicstr}', ii_encode_scripts(ii_htmlencode($trs[ii_cfname('topic')])), $tmptstr);
      $tmptstr = str_replace('{$images_tpl}', ii_htmlencode($trs[ii_cfname('images_tpl')]), $tmptstr);
      $tmptstr = str_replace('{$time}', ii_get_date($trs[ii_cfname('time')]), $tmptstr);
      $tmptstr = str_replace('{$type}', $ttype, $tmptstr);
      $tmptstr = str_replace('{$hidden}', ii_itake('global.sel_yesno.'.ii_get_num($trs[ii_cfname('hidden')]), 'lng'), $tmptstr);
      $tmptstr = str_replace('{$id}', ii_get_num($trs[$nidfield]), $tmptstr);
      $tmptstr = str_replace('{$label_name}', ii_htmlencode($label_name), $tmptstr);
      $tmptstr = str_replace('{$label_content}', ii_htmlencode($label_content), $tmptstr);
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
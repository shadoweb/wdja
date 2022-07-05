<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************

function wdja_cms_module_list()
{
  //不展示资料列表页,直接跳转至教程模块首页
  $tgourl = ii_get_actual_route('tutorial');
  if (!ii_isnull($tgourl)) {
    header("HTTP/1.1 301 Moved Permanently");
    header("Location:$tgourl");
    exit;
  }
}

function wdja_cms_module_detail()
{
  global $conn, $ngenre;
  global $nvalidate;
  $tid = ii_get_num($_GET['id']);
  $tpage = ii_get_num($_GET['page']);
  $tucode = ii_cstr($_GET['ucode']);
  global $nurlpre, $nurltype;
  $turl = $nurlpre.'/'.$ngenre.'/'.ii_iurl('detail', $tid, $nurltype);
  global $ndatabase, $nidfield, $nfpre;
  if (!ii_isnull($tucode)) $tsqlstr = "select * from $ndatabase where " . ii_cfname('hidden') . "=0 and " . ii_cfname('ucode') . "='$tucode'";
  else $tsqlstr = "select * from $ndatabase where " . ii_cfname('hidden') . "=0 and $nidfield=$tid";
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs)
  {
  	$tid = $trs[$nidfield];
	$tgourl = mm_get_field($ngenre,$tid,'gourl');
	if (!ii_isnull($tgourl)) {
		header("HTTP/1.1 301 Moved Permanently");
		header ("Location:$tgourl");
		exit;
	}
    $tcount = $trs[ii_cfname('count')] + 1;
    mm_update_field($ngenre,$trs[$nidfield],'count',$tcount);
    $ttutorialid = $trs[ii_cfname('tutorial_id')];
    $tutid = mm_get_field('tutorial',$ttutorialid,'utid');//教程用户组id
    //继承教程的权限,这里可以加个开关是否继承/或者单独设置免费.
    if (!ap_check_userType($tutid)) {
        $tmpstr = ii_itake('module.nodetail', 'tpl');
    }else{
        $ttpl = mm_get_field($ngenre,$tid,'tpl');
        if (!ii_isnull($ttpl)) $tmpstr = ii_itake('module.'.$ttpl, 'tpl');
        else $tmpstr = ii_itake('module.detail', 'tpl');
    }
    $titles = ii_htmlencode($trs[ii_cfname('titles')]);
    if(!ii_isnull($titles)) mm_cntitle($titles);
    else mm_cntitle(ii_htmlencode($trs[ii_cfname('topic')]));
    mm_cnkeywords(ii_htmlencode($trs[ii_cfname('keywords')]));
    mm_cndescription(ii_htmlencode($trs[ii_cfname('description')]));
    foreach ($trs as $key => $val)
    {
      $tkey = ii_get_lrstr($key, '_', 'rightr');
      $GLOBALS['RS_' . $tkey] = $val;
      $tmpstr = str_replace('{$' . $tkey . '}', ii_htmlencode($val), $tmpstr);
    }
    $file = ii_htmlencode($trs[ii_cfname('file')]);
    $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_file}');
    $tmprstr = '';
    if(!ii_isnull($file)){
        $tmprstr = str_replace('{$file}', $file, $tmpastr);
        $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
    }
    else $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
    $tmpstr = str_replace('{$id}', $trs[$nidfield], $tmpstr);
    $tmpstr = str_replace('{$url}', $turl, $tmpstr);
    $tmpstr = str_replace('{$genre}', $ngenre, $tmpstr);
    $tmpstr = str_replace('{$page}', $tpage, $tmpstr);
    $tmpstr = mm_cvalhtml($tmpstr, $nvalidate, '{@recurrence_valcode}');
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
  }else{
    mm_imessage(ii_itake('global.lng_config.nodata', 'lng'), '-1');   
  }
}

function wdja_cms_module_index()
{
  global $ngenre;
  global $nvalidate;
  global $ntitles,$nkeywords,$ndescription;
  $tmpstr = ii_itake('module.index', 'tpl');
  mm_cntitle($ntitles);
  mm_cnkeywords($nkeywords);
  mm_cndescription($ndescription);
  $tmpstr = str_replace('{$genre}', $ngenre, $tmpstr);
  $tmpstr = mm_cvalhtml($tmpstr, $nvalidate, '{@recurrence_valcode}');
  $tmpstr = ii_creplace($tmpstr);
  if (!ii_isnull($tmpstr)) return $tmpstr;
  else return wdja_cms_module_list();
}

function wdja_cms_module()
{
  switch($_GET['type'])
  {
    case 'list':
      return wdja_cms_module_list();
      break;
    case 'api':
      return wdja_cms_module_api();
      break;
    case 'detail':
      return wdja_cms_module_detail();
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
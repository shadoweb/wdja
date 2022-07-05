<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
function wdja_cms_module_detail()
{
  global $conn, $ngenre,$nlng;
  global $nvalidate;
  $tid = ii_get_num($_GET['id'],0);
  $tpage = ii_get_num($_GET['page']);
  $tucode = ii_cstr($_GET['ucode']);
  global $ndatabase, $nidfield, $nfpre;
  if (!ii_isnull($tucode)) {
    $tsqlstr = "select * from $ndatabase where " . ii_cfname('hidden') . "=0 and " . ii_cfname('lng') . "='$nlng' and " . ii_cfname('ucode') . "='$tucode'";
  }elseif ($tid==0) {
    $tsqlstr = "select * from $ndatabase where " . ii_cfname('hidden') . "=0 and " . ii_cfname('lng') . "='$nlng' order by ".$nidfield." asc limit 0,1";
  } else{
    $tsqlstr = "select * from $ndatabase where " . ii_cfname('hidden') . "=0 and " . ii_cfname('lng') . "='$nlng' and $nidfield=$tid";
  }
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs)
  {
    $tid = $trs[$nidfield];
    $ttpl = mm_get_field($ngenre,$tid,'tpl');
    $tgourl = mm_get_field($ngenre,$tid,'gourl');
    if (!ii_isnull($tgourl)) {
        header("HTTP/1.1 301 Moved Permanently");
        header ("Location:$tgourl");
        exit;
    }
    $tcount = $trs[ii_cfname('count')] + 1;
    mm_update_field($ngenre,$trs[$nidfield],'count',$tcount);//访问一次,更新一次访问次数+1;
    if (!ii_isnull($ttpl)) $tmpstr = ii_itake('module.'.$ttpl, 'tpl');
    else $tmpstr = ii_itake('module.detail', 'tpl');
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
    $tmpstr = api_replace_fields($tmpstr,$trs[$nidfield],$ngenre);
    $tmpstr = str_replace('{$id}', $trs[$nidfield], $tmpstr);
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
  else return wdja_cms_module_detail();
}

function wdja_cms_module()
{
  switch($_GET['type'])
  {
    case 'detail':
      return wdja_cms_module_detail();
      break;
    case 'index':
      return wdja_cms_module_index();
      break;
    default:
      return wdja_cms_module_detail();
      break;
  }
}
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
?>
<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
function mm_nav_topic($fsid)
{
  global $conn;
  global $ngenre, $slng, $nurltype;
  global $ndatabase, $nidfield, $nfpre;
  $tfsid = ii_get_num($fsid);
  $tfid = mm_get_field($ngenre,$fsid,'fid').','.$fsid;
  $tfidary = explode(',',$tfid);
  $tpl_href = ii_itake('global.tpl_config.a_href_self', 'tpl');
  $tmpstr = '';
  $thome = ii_itake('global.module.channel_title', 'lng');
  $tmpstr = $tpl_href;
  $tmpstr = str_replace('{$explain}', $thome, $tmpstr);
  $tmpstr = str_replace('{$value}', ii_get_actual_route('./'), $tmpstr);
  $ttopic = ii_itake('global.' . $ngenre . ':module.channel_title', 'lng');
    if(!ii_isnull($ttopic)){
      $tmpstr .= NAV_SP_STR . $tpl_href;
      $tmpstr = str_replace('{$explain}', $ttopic, $tmpstr);
      $tmpstr = str_replace('{$value}', ii_get_actual_route($ngenre), $tmpstr);
    }
  foreach($tfidary as $fid)
  {
    if($fid !=0){
        $ttopic = mm_get_field($ngenre,$fid,'topic');
        $ttype = mm_get_field($ngenre,$fid,'type');
        $tstr = $tpl_href;
        $tstr = str_replace('{$explain}', $ttopic, $tstr);
        if($ttype == 0) $tstr = str_replace('{$value}', ii_iurl('list',$fid, $nurltype), $tstr);
        $tstr = str_replace('{$value}', ii_iurl('detail',$fid, $nurltype), $tstr);
        $tmpstr .= NAV_SP_STR . $tstr;
    }
  }
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function wdja_cms_module_list()
{
  global $conn, $nlng, $ngenre;
  global $nvalidate;
  $toffset = ii_get_num($_GET['offset']);
  $tfsid = ii_get_num($_GET['classid'],0);
  $tgid = api_get_gid();
  global $nclstype, $nlisttopx, $npagesize, $ntitles,$nkeywords,$ndescription;
  global $ndatabase, $nidfield, $nfpre;
  $tmpstr = ii_itake('module.list', 'tpl');
  mm_cntitle($ntitles);
  mm_cnkeywords($nkeywords);
  mm_cndescription($ndescription);
  $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_ida}');
  $tmprstr = '';
  $tsqlstr = "select * from $ndatabase where " . ii_cfname('hidden') . "=0 and " . ii_cfname('type') . "=0 and " . ii_cfname('lng') . "= '" . $nlng . "'";
  if($tfsid != 0) $tsqlstr .= " and " . ii_cfname('fsid') . "=$tfsid";
  if (!ii_isnull($tgid) && !ii_isnull($_GET['type'])) $tsqlstr .= " and $nidfield in ($tgid)";
  $tsqlstr .= " order by " . ii_cfname('time') . " desc";
  $trsary = ii_conn_query($tsqlstr, $conn);
  $trsary = ii_conn_fetch_all($trsary);
  if (is_array($trsary))
  {
    foreach($trsary as $trs)
    {
      $tmptstr = $tmpastr;
      foreach ($trs as $key => $val)
      {
        $tkey = ii_get_lrstr($key, '_', 'rightr');
        $GLOBALS['RS_' . $tkey] = $val;
        $tmptstr = str_replace('{$' . $tkey . '}', ii_htmlencode($val), $tmptstr);
      }
      global $nurltype, $ncreatefiletype;
      $turl = ii_iurl('list', $trs[$nidfield], $nurltype);
      $tmptstr = api_replace_fields($tmptstr,$trs[$nidfield],$ngenre);
      $tmptstr = str_replace('{$id}', $trs[$nidfield], $tmptstr);
      $tmptstr = str_replace('{$url}', $turl, $tmptstr);
      $tmptstr = ii_creplace($tmptstr);
      $tmprstr .= $tmptstr;
    }
  }
  $tdisplay = '';
  if(ii_isnull($tmprstr)) $tdisplay = 'style="display:none;"';
  $tmpstr = str_replace('{$display}', $tdisplay, $tmpstr);
  $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
  $tmpbstr = ii_ctemplate($tmpstr, '{@recurrence_idb}');
  $tmprstr = '';
  $tsqlstr = "select * from $ndatabase where " . ii_cfname('hidden') . "=0 and " . ii_cfname('type') . "=1 and " . ii_cfname('lng') . "= '" . $nlng . "'";
  if($tfsid != 0) $tsqlstr .= " and " . ii_cfname('fsid') . "=$tfsid";
  if (!ii_isnull($tgid) && !ii_isnull($_GET['type'])) $tsqlstr .= " and $nidfield in ($tgid)";
  $tsqlstr .= " order by " . ii_cfname('time') . " desc";
  $tcp = new cc_cutepage;
  $tcp -> id = $nidfield;
  $tcp -> pagesize = $npagesize;
  $tcp -> rslimit = $nlisttopx;
  $tcp -> sqlstr = $tsqlstr;
  $tcp -> offset = $toffset;
  $tcp -> urlid = $tfsid;
  $tcp -> init();
  $trsary = $tcp -> get_rs_array();
  if (is_array($trsary))
  {
    foreach($trsary as $trs)
    {
      $tmptstr = $tmpbstr;
      foreach ($trs as $key => $val)
      {
        $tkey = ii_get_lrstr($key, '_', 'rightr');
        $GLOBALS['RS_' . $tkey] = $val;
        $tmptstr = str_replace('{$' . $tkey . '}', ii_htmlencode($val), $tmptstr);
      }
      $tmptstr = api_replace_fields($tmptstr,$trs[$nidfield],$ngenre);
      $tmptstr = str_replace('{$id}', $trs[$nidfield], $tmptstr);
      $tmptstr = str_replace('{$simage}', mm_get_content_image($ngenre,$trs[ii_cfname('content')],$trs[ii_cfname('image')]), $tmptstr);
      $tmptstr = str_replace('{$nlng}', $nlng, $tmptstr);
      $tmptstr = ii_creplace($tmptstr);
      $tmprstr .= $tmptstr;
    }
  }
  $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
  $tmpstr = str_replace('{$cpagestr}', $tcp -> get_pagenum(), $tmpstr);
  if($tfsid == 0) $tmpstr = str_replace('{$topic}', mm_get_genre_title($ngenre), $tmpstr);
  else $tmpstr = str_replace('{$topic}', mm_get_field($ngenre,$tfsid,'topic'), $tmpstr);
  $tmpstr = str_replace('{$content}', mm_get_field($ngenre,$tfsid,'content'), $tmpstr);
  $tmpstr = str_replace('{$offset}', $toffset, $tmpstr);
  $tmpstr = str_replace('{$genre}', $ngenre, $tmpstr);
  $tmpstr = mm_cvalhtml($tmpstr, $nvalidate, '{@recurrence_valcode}');
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}
function wdja_cms_module_detail()
{
  global $conn, $ngenre;
  global $nvalidate;
  $tid = ii_get_num($_GET['id'],0);
  $tpage = ii_get_num($_GET['page']);
  $tucode = ii_cstr($_GET['ucode']);
  global $ndatabase, $nidfield, $nfpre;
  global $nurlpre, $nurltype;
  $turl = $nurlpre.'/'.$ngenre.'/'.ii_iurl('detail', $tid, $nurltype);
  if (!ii_isnull($tucode)) {
    $tsqlstr = "select * from $ndatabase where " . ii_cfname('hidden') . "=0 and " . ii_cfname('ucode') . "='$tucode'";
  }elseif ($tid==0) {
    $tsqlstr = "select * from $ndatabase where " . ii_cfname('hidden') . "=0 order by ".$nidfield." asc limit 0,1";
  } else{
    $tsqlstr = "select * from $ndatabase where " . ii_cfname('hidden') . "=0 and $nidfield=$tid";
  }
  $trs = ii_conn_query($tsqlstr, $conn);
  $trs = ii_conn_fetch_array($trs);
  if ($trs)
  {
    $tcount = $trs[ii_cfname('count')] + 1;
    mm_update_field($ngenre,$trs[$nidfield],'count',$tcount);//访问一次,更新一次访问次数+1;
    $tmpstr = ii_itake('module.detail', 'tpl');
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
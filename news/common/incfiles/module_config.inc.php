<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************

function wdja_cms_module_list()
{
  global $conn, $nlng, $ngenre;
  global $nvalidate;
  $tclassid = ii_get_num($_GET['classid']);
  //if ($tclassid == 0) $tclassid = mm_get_firstSortId($ngenre);//模块首页使用第一个分类
  $toffset = ii_get_num($_GET['offset']);
  $ttpl = mm_get_sort_field($tclassid,'tpl_list');
  $tgourl = mm_get_sort_field($tclassid,'gourl');
  if (!ii_isnull($tgourl)) {
    header("HTTP/1.1 301 Moved Permanently");
    header("Location:$tgourl");
    exit;
  }
  global $nclstype, $nlisttopx, $npagesize, $ntitles,$nkeywords,$ndescription;
  global $ndatabase, $nidfield, $nfpre;
  $tclassids = mm_get_sortids($ngenre, $nlng);
  if (!ii_isnull($ttpl)) $tmpstr = ii_itake('module.'.$ttpl, 'tpl');
  else $tmpstr = ii_itake('module.list', 'tpl');
  $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_ida}');
  $tmprstr = '';
  global $sort_database, $sort_idfield, $sort_fpre;
  $tsqlstr = "select * from $ndatabase,$sort_database where $ndatabase." . ii_cfname('class') . "=$sort_database.$sort_idfield and $sort_database." . ii_cfnames($sort_fpre, 'lng') . "='$nlng' and $sort_database." . ii_cfnames($sort_fpre, 'genre') . "='$ngenre' and $ndatabase." . ii_cfname('hidden') . "=0 and $ndatabase." . ii_cfname('lng') . "='$nlng'";
  if ($tclassid != 0)
  {
    if (ii_cinstr($tclassids, $tclassid, ','))
    {
      mm_cntitle(mm_get_sorttitles($ngenre, $nlng, $tclassid));
      mm_cnkeywords(mm_get_sortkeywords($ngenre, $nlng, $tclassid));
      mm_cndescription(mm_get_sortdescription($ngenre, $nlng, $tclassid));
      if ($nclstype == 0) $tsqlstr .= " and " . ii_cfname('class') . "=$tclassid";
      else $tsqlstr .= " and (" . ii_cfname('cls') . " like '%|" . $tclassid . "|%' or find_in_set($tclassid," . ii_cfname('class_list') . "))";
    }
  }else{
      mm_cntitle($ntitles);
      mm_cnkeywords($nkeywords);
      mm_cndescription($ndescription);
    if (!ii_isnull($tclassids)) $tsqlstr .= " and (" . ii_cfname('class') . " in ($tclassids) or find_in_set($tclassid," . ii_cfname('class_list') . "))";
  }
  $tgid = api_get_gid();
  if (!ii_isnull($tgid) && !ii_isnull($_GET['type'])) $tsqlstr .= " and $nidfield in ($tgid)";
  $tsqlstr .= " order by " . ii_cfname('time') . " desc";
  $tcp = new cc_cutepage;
  $tcp -> id = $nidfield;
  $tcp -> pagesize = $npagesize;
  $tcp -> rslimit = $nlisttopx;
  $tcp -> sqlstr = $tsqlstr;
  $tcp -> offset = $toffset;
  $tcp -> urlid = $tclassid;
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
        $tmptstr = str_replace('{$' . $tkey . '}', ii_htmlencode($val), $tmptstr);
      }
      $tmptstr = api_replace_fields($tmptstr,$trs[$nidfield],$ngenre);
      $tmptstr = str_replace('{$id}', $trs[$nidfield], $tmptstr);
      $tmptstr = str_replace('{$classid}', $tclassid, $tmptstr);
      $tmptstr = str_replace('{$simage}', mm_get_content_image($ngenre,$trs[ii_cfname('content')],$trs[ii_cfname('image')]), $tmptstr);
      $tmptstr = str_replace('{$nlng}', $nlng, $tmptstr);
      $tmptstr = ii_creplace($tmptstr);
      $tmprstr .= $tmptstr;
    }
  }
  $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
  $tmpstr = str_replace('{$cpagestr}', $tcp -> get_pagenum(), $tmpstr);
  $tmpstr = str_replace('{$genre}', $ngenre, $tmpstr);
  $tmpstr = str_replace('{$nlng}', $nlng, $tmpstr);
  $tmpstr = str_replace('{$classid}', $tclassid, $tmpstr);
  $tmpstr = str_replace('{$offset}', $toffset, $tmpstr);
  $tmpstr = mm_cvalhtml($tmpstr, $nvalidate, '{@recurrence_valcode}');
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
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
    $tcount = $trs[ii_cfname('count')] + 1;
    mm_update_field($ngenre,$trs[$nidfield],'count',$tcount);
    $ttpl = mm_get_sort_field($trs[ii_cfname('class')],'tpl_detail');
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
    $tmpstr = str_replace('{$simage}', mm_get_content_image($ngenre,$trs[ii_cfname('content')],$trs[ii_cfname('image')]), $tmpstr);
    $tmpstr = str_replace('{$url}', $turl, $tmpstr);
    $tmpstr = str_replace('{$genre}', $ngenre, $tmpstr);
    $tmpstr = str_replace('{$page}', $tpage, $tmpstr);
    $tmpstr = mm_cvalhtml($tmpstr, $nvalidate, '{@recurrence_valcode}');
    $tmpstr = ii_creplace($tmpstr);
    return $tmpstr;
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
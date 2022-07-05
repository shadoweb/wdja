<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
function pp_nhotwords_genre($topic)
{
        global $nhotwords_genre;
        $tngenres = explode(',', $nhotwords_genre);
        $tmpstr = ii_itake('module.module', 'tpl');
        for ($ti = 0; $ti < count($tngenres); $ti ++)
        {
            $tngenre = $tngenres[$ti];
            $tmptstr = str_replace('{$module}', $tngenre, $tmpstr);
            $tmptstr = str_replace('{$topic}', $topic, $tmptstr);
            $tmprstr .= ii_creplace($tmptstr);
        }
         $tmpstr = $tmprstr;
         return $tmpstr;
}

function wdja_cms_module_list()
{
  global $conn, $nlng, $ngenre;
  $tclassid = ii_get_num($_GET['classid']);
  $toffset = ii_get_num($_GET['offset']);
  global $nclstype, $nlisttopx, $npagesize, $ntitles,$nkeywords,$ndescription;
  global $ndatabase, $nidfield, $nfpre;
  global $nurltype, $ncreatefiletype ;
  $tclassids = mm_get_sortids($ngenre, $nlng);
  $tmpstr = ii_itake('module.list', 'tpl');
  $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_ida}');
  $tmprstr = '';
  $tsqlstr = "select * from $ndatabase where " . ii_cfname('hidden') . "=0 and " . ii_cfname('lng') . "= '" . $nlng . "'";
  if ($tclassid != 0)
  {
    if (ii_cinstr($tclassids, $tclassid, ','))
    {
      mm_cntitle(mm_get_sorttitles($ngenre, $nlng, $tclassid));
      mm_cnkeywords(mm_get_sortkeywords($ngenre, $nlng, $tclassid));
      mm_cndescription(mm_get_sortdescription($ngenre, $nlng, $tclassid));
      if ($nclstype == 0) $tsqlstr .= " and " . ii_cfname('class') . "=$tclassid";
      else $tsqlstr .= " and " . ii_cfname('cls') . " like '%|" . $tclassid . "|%'";
    }
  }else{
      mm_cntitle($ntitles);
      mm_cnkeywords($nkeywords);
      mm_cndescription($ndescription);
    if (!ii_isnull($tclassids)) $tsqlstr .= " and " . ii_cfname('class') . " in ($tclassids)";
  }
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
      $tgourl = mm_get_field($ngenre,$trs[$nidfield],'gourl');
      if (!ii_isnull($tgourl)) $turl = $tgourl;
      else $turl = '/'.$ngenre.'/'.ii_iurl('detail',$trs[$nidfield], $nurltype);
      $tmptstr = str_replace('{$id}', $trs[$nidfield], $tmptstr);
      $tmptstr = str_replace('{$sum}', '('.api_tags_sum($trs[$nidfield]).')', $tmptstr);
      $tmptstr = str_replace('{$url}', $turl, $tmptstr);
      $tmptstr = ii_creplace($tmptstr);
      $tmprstr .= $tmptstr;
    }
  }
  $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
  $tmpstr = str_replace('{$cpagestr}', $tcp -> get_pagenum(), $tmpstr);
  $tmpstr = str_replace('{$genre}', $ngenre, $tmpstr);
  $tmpstr = str_replace('{$nlng}', $nlng, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}

function wdja_cms_module_detail()
{
  global $conn, $ngenre;
  $tid = ii_get_num($_GET['id']);
  $toffset = ii_get_num($_GET['offset']);
  $tshkeyword = ii_get_safecode($_GET['topic']);
  if (ii_isnull($tshkeyword)) {
      global $ndatabase, $nidfield, $nfpre;
      $tsqlstr = "select * from $ndatabase where " . ii_cfname('hidden') . "=0 and $nidfield=$tid";
      $trs = ii_conn_query($tsqlstr, $conn);
      $trs = ii_conn_fetch_array($trs);
      if ($trs)
      {
        $trsdary = $trs;
        $tid = $trs[$nidfield];
        $ttopic = $trs[ii_cfname('topic')];
        $ttime = $trs[ii_cfname('time')];
        $tcount = $trs[ii_cfname('count')] + 1;
        mm_update_field($ngenre,$trs[$nidfield],'count',$tcount);//访问一次,更新一次访问次数+1;
        $tgourl = mm_get_field($ngenre,$tid,'gourl');
        if (!ii_isnull($tgourl)) {
          header("HTTP/1.1 301 Moved Permanently");
          header ("Location:$tgourl");
          exit;
        }
        $titles = ii_htmlencode($trs[ii_cfname('titles')]);
        if(!ii_isnull($titles)) mm_cntitle($titles);
        else mm_cntitle(ii_htmlencode($trs[ii_cfname('topic')]));
        mm_cnkeywords(ii_htmlencode($trs[ii_cfname('keywords')]));
        mm_cndescription(ii_htmlencode($trs[ii_cfname('description')]));
        $tshkeyword = ii_htmlencode($trs[ii_cfname('topic')]);
      }
  }
  global $variable, $nvalidate, $nurltype, $ncreatefiletype, $nlng;
  global $npagesize, $nlisttopx, $nsearch_genre, $nsearch_field;
  if (ii_isnull($tshkeyword)) mm_imessage(ii_itake('module.keyword_error', 'lng'), '/tags');
  $tshkeywords = explode(' ', $tshkeyword);
  if (count($tshkeywords) > 5) mm_imessage(ii_itake('module.complex_error', 'lng'), '/tags');
  $font_red = ii_itake('global.tpl_config.font_red', 'tpl');
  $tmpstr = ii_itake('module.detail', 'tpl');
  $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_ida}');
  $tmprstr = '';
  $tndatabases = explode(',', $nsearch_genre);
  $tnfields = explode(',', $nsearch_field);
  $tsqlstr = "";
  for ($ti = 0; $ti < count($tndatabases); $ti ++)
  {
    $tndatabase = $tndatabases[$ti];
    $tdatabase = $variable[ii_cvgenre($tndatabase) . '.ndatabase'];
    $tidfield = $variable[ii_cvgenre($tndatabase) . '.nidfield'];
    $tfpre = $variable[ii_cvgenre($tndatabase) . '.nfpre'];
    $tunion = " union all ";
    $tsqlstr .= "select * from (";
    $tsqlstr .= "select " . $tidfield . " as un_id,";
    foreach ($tnfields as $tnfield)
    {
      $tsqlstr .= ii_cfnames($tfpre, $tnfield) . " as un_" . $tnfield . ",";
    }
    $tsid = $tidfield;
    $tsqlstr .= $tsid . " as un_sid," . ii_cfnames($tfpre, 'count') . " as un_count," . ii_cfnames($tfpre, 'time') . " as un_time,'" . $tndatabase . "' as un_genre from " . $tdatabase . " where " . ii_cfnames($tfpre, 'hidden') . "=0 and " . ii_cfnames($tfpre, 'lng') . "='$nlng'";
    foreach ($tshkeywords as $key => $val)
    {
      foreach ($tnfields as $tnfield)
      {
        if ($tnfield == 'topic') $tsqlstr .= " and " . ii_cfnames($tfpre, $tnfield) . " like '%" . $val . "%'";
        else $tsqlstr .= " or " . ii_cfnames($tfpre, $tnfield) . " like '%" . $val . "%'";
      }
    }
    if ($ti == count($tndatabases) - 1) $tsqlstr .= " order by " . ii_cfnames($tfpre, 'time') . " desc) as un_" . $tndatabase;
    else $tsqlstr .= " order by " . ii_cfnames($tfpre, 'time') . " desc) as un_" . $tndatabase . $tunion;
  }
  $tcp = new cc_cutepage;
  $tcp -> id = 'un_id';
  $tcp -> pagesize = $npagesize;
  $tcp -> rslimit = $nlisttopx;
  $tcp -> urlid = $tid;
  $tcp -> sqlstr = $tsqlstr;
  $tcp -> offset = $toffset;
  $tcp -> init();
  $trsary = $tcp -> get_rs_array();
  if (is_array($trsary))
  {
    foreach($trsary as $trs)
    {
      $tfshkeyword = '';
      $tmptstr = $tmpastr;
      $tfshkeyword = str_replace('{$explain}', $tshkeyword, $font_red);
      $ttopic = ii_htmlencode($trs['un_topic']);
      $tcontent = $trs['un_content'];
      $tmptstr = str_replace('{$topicstr}', $ttopic, $tmpastr);
      if (!ii_isnull($tfshkeyword)) 
      {
        $ttopic = str_replace($tshkeyword, $tfshkeyword, $ttopic);
        $tcontent = str_replace($tshkeyword, $tfshkeyword, $tcontent);
      }
      $tmptstr = str_replace('{$topic}', $ttopic, $tmptstr);
      $tmptstr = str_replace('{$content}', $tcontent, $tmptstr);
      $tmptstr = str_replace('{$time}', ii_get_date($trs['un_time']), $tmptstr);
      $tmptstr = str_replace('{$count}', ii_get_num($trs['un_count']), $tmptstr);
      $tmptstr = str_replace('{$id}', ii_get_num($trs['un_id']), $tmptstr);
      $tmptstr = str_replace('{$module}', '<a href="'.ii_get_actual_route($trs['un_genre']).'">['.ii_itake('global.'.$trs['un_genre'].':module.channel_title', 'lng').']</a>&nbsp;', $tmptstr);
      $tmptstr = str_replace('{$url}', ii_curl(ii_get_actual_route($trs['un_genre']), ii_iurl('detail', ii_get_num($trs['un_id']), $nurltype)), $tmptstr);

      $tmprstr .= $tmptstr;
    }
  $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
  $tmpstr = str_replace('{$urltype}', $nurltype, $tmpstr);
  $tmpstr = str_replace('{$createfiletype}', $tcreatefiletype, $tmpstr);
  $tmpstr = str_replace('{$cpagestr}', $tcp -> get_pagenum('tag'), $tmpstr);
  $tmpstr = str_replace('{$keyword}', $tshkeyword, $tmpstr);
  $tmpstr = str_replace('{$genre}', $ngenre, $tmpstr);
  $tmpstr = str_replace('{$id}', $tid, $tmpstr);
  $tmpstr = str_replace('{$time}', $ttime, $tmpstr);
  $tmpstr = str_replace('{$topic}', $tshkeyword, $tmpstr);
  $tmpstr = mm_cvalhtml($tmpstr, $nvalidate, '{@recurrence_valcode}');
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
  }else{
      if (is_array($trsdary))
      {
        $tmpstr = ii_itake('module.detail_nodata', 'tpl');
        foreach ($trsdary as $key => $val)
        {
          $tkey = ii_get_lrstr($key, '_', 'rightr');
          $GLOBALS['RS_' . $tkey] = $val;
          $tmpstr = str_replace('{$' . $tkey . '}', ii_htmlencode($val), $tmpstr);
        }
        $tmpstr = str_replace('{$id}', $trsdary[$nidfield], $tmpstr);
        $tmpstr = str_replace('{$genre}', $ngenre, $tmpstr);
        $tmpstr = str_replace('{$page}', $tpage, $tmpstr);
        $tmpstr = mm_cvalhtml($tmpstr, $nvalidate, '{@recurrence_valcode}');
        $tmpstr = ii_creplace($tmpstr);
        return $tmpstr;
      }
  }
}

function wdja_cms_module_frame()
{
  global $ngenre;
  $tmpstr = ii_itake('module.frame', 'tpl');
  $tmpstr = str_replace('{$genre}', $ngenre, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  if (!ii_isnull($tmpstr)) return $tmpstr;
  else return wdja_cms_module_list();
}

function wdja_cms_module_index()
{
  global $ngenre;
  global $ntitles,$nkeywords,$ndescription;
  $tmpstr = ii_itake('module.index', 'tpl');
  mm_cntitle($ntitles);
  mm_cnkeywords($nkeywords);
  mm_cndescription($ndescription);
  $tmpstr = str_replace('{$genre}', $ngenre, $tmpstr);
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
    case 'detail':
      return wdja_cms_module_detail();
      break;
    case 'frame':
      return wdja_cms_module_frame();
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
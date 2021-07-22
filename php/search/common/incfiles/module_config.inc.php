<?php
//****************************************************
// WDJA CMS Power by wdja.net
// Email: admin@wdja.net
// Web: http://www.wdja.net/
//****************************************************
function wdja_cms_module_list()
{
  global $variable;
  global $ngenre, $npagesize, $nlisttopx, $nlng;
  global $nsearch_genre, $nsearch_field;
  global $nvalidate;
  $tshkeyword = ii_get_safecode($_GET['keyword']);
  if (!ii_isnull($tshkeyword)) search_data_insert($_GET['keyword']);
  $toffset = ii_get_num($_GET['offset']);
  if (ii_isnull($tshkeyword)) mm_imessage(ii_itake('module.keyword_error', 'lng'), '/search');
  mm_cntitle('关于'.$tshkeyword.'的搜索结果');
  mm_cnkeywords($tshkeyword);
  mm_cndescription($ndescription);
  $tshkeywords = explode(' ', $tshkeyword);
  if (count($tshkeywords) > 5) mm_imessage(ii_itake('module.complex_error', 'lng'), '/search');
  $font_red = ii_itake('global.tpl_config.font_red', 'tpl');
  $tmpstr = ii_itake('module.list', 'tpl');
  $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_ida}');
  $tmprstr = '';
  $tndatabases = explode(',', $nsearch_genre);
  $tnfields = explode(',', $nsearch_field);
  $tsqlstr = "";
  for ($ti = 0; $ti < count($tndatabases); $ti ++)
  {
    $tndatabase = $tndatabases[$ti];
    $turltype = ii_get_num($variable[ii_cvgenre($tndatabase) . '.nurltype']);
    $tcreatefolder = $variable[ii_cvgenre($tndatabase) . '.ncreatefolder'];
    $tcreatefiletype = $variable[ii_cvgenre($tndatabase) . '.ncreatefiletype'];
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
    if ($tndatabase == 'forum') $tsid = ii_cfnames($tfpre, 'sid');
    else  $tsid = $tidfield;
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
      $tmptstr = str_replace('{$genre}', $trs['un_genre'], $tmptstr);
      $tmptstr = str_replace('{$module}', '<a href="'.ii_get_actual_route($trs['un_genre']).'">['.ii_itake('global.'.$trs['un_genre'].':module.channel_title', 'lng').']</a>&nbsp;', $tmptstr);
      if ($trs['un_genre'] == 'forum') $tmptstr = str_replace('{$url}', ii_get_actual_route($trs['un_genre']).'/?type=detail&sid='.ii_get_num($trs['un_sid']).'&tid='.ii_get_num($trs['un_id']), $tmptstr);
      else $tmptstr = str_replace('{$url}', ii_curl(ii_get_actual_route($trs['un_genre']), ii_iurl('detail', ii_get_num($trs['un_id']), $turltype, 'folder='.$tcreatefolder.';filetype='.$tcreatefiletype.';time='.ii_get_date($trs['un_time']))), $tmptstr);

      $tmprstr .= $tmptstr;
    }
  $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
  $tmpstr = str_replace('{$urltype}', $turltype, $tmpstr);
  $tmpstr = str_replace('{$createfolder}', $tcreatefolder, $tmpstr);
  $tmpstr = str_replace('{$createfiletype}', $tcreatefiletype, $tmpstr);
  $tmpstr = str_replace('{$cpagestr}', $tcp -> get_pagenum(), $tmpstr);
  $tmpstr = str_replace('{$keyword}', $tshkeyword, $tmpstr);
  $tmpstr = str_replace('{$genre}', $ngenre, $tmpstr);
  $tmpstr = mm_cvalhtml($tmpstr, $nvalidate, '{@recurrence_valcode}');
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
  }
  else mm_imessage(ii_itake('module.nodata', 'lng'), '/search');
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
  $tmpstr = str_replace('{$keyword}', '', $tmpstr);
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
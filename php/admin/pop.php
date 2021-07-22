<?php
require('../common/incfiles/autoload.php');
wdja_cms_islogin();
$genre = ii_get_safecode($_GET['genre']);
if(!ii_isnull($genre)) $mybody = wdja_cms_pop_list($genre);
else $mybody = wdja_cms_pop_upload();
$myhead = wdja_cms_web_head('pop_head');
$myfoot = wdja_cms_web_foot('pop_foot');
$myhtml = $myhead . $mybody . $myfoot;
echo $myhtml;

function wdja_cms_pop_list($genre)
{
  global $conn, $slng;
  global $variable;
  $ngenre = $genre;
  $nclstype = $variable[ii_cvgenre($ngenre) . '.nclstype'];
  $npagesize = $variable[ii_cvgenre($ngenre) . '.npagesize'];
  $nlisttopx = $variable[ii_cvgenre($ngenre) . '.nlisttopx'];
  $ndatabase = $variable[ii_cvgenre($ngenre) . '.ndatabase'];
  $nidfield = $variable[ii_cvgenre($ngenre) . '.nidfield'];
  $nfpre = $variable[ii_cvgenre($ngenre) . '.nfpre'];
  $nurltype = $variable[ii_cvgenre($ngenre) . '.nurltype'];
  $ncreatefolder = $variable[ii_cvgenre($ngenre) . '.ncreatefolder'];
  $ncreatefiletype = $variable[ii_cvgenre($ngenre) . '.ncreatefiletype'];
  $toffset = ii_get_num($_GET['offset']);
  $search_keyword = ii_get_safecode($_GET['keyword']);
  $tmpstr = ii_itake('global.tpl_pops.pop_list', 'tpl');
  $tmpastr = ii_ctemplate($tmpstr, '{@recurrence_ida}');
  $tmprstr = '';
  $tsqlstr = "select * from $ndatabase where " . ii_cfnames($nfpre,'hidden') . "=0";
  if (!ii_isnull($search_keyword)) $tsqlstr .= " and " . ii_cfnames($nfpre,'topic') . " like '%" . $search_keyword . "%'";
  $tsqlstr .= " order by " . ii_cfnames($nfpre,'time') . " desc";
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
  if (!(ii_isnull($search_keyword))) $font_red = ii_itake('global.tpl_config.font_red', 'tpl');
  if (is_array($trsary))
  {
    foreach($trsary as $trs)
    {
      $ttopic = ii_htmlencode($trs[ii_cfnames($nfpre,'topic')]);
      $title= ii_htmlencode($trs[ii_cfnames($nfpre,'topic')]);
      if (isset($font_red))
      {
        $font_red = str_replace('{$explain}', $search_keyword, $font_red);
        $ttopic = str_replace($search_keyword, $font_red, $ttopic);
      }
      $turl = '/'.$ngenre.'/'.ii_iurl('detail',$trs[$nidfield], $nurltype, 'folder=' . $ncreatefolder . ';filetype=' . $ncreatefiletype);
      $tmptstr = str_replace('{$topic}', $ttopic, $tmpastr);
      $tmptstr = str_replace('{$title}', $title, $tmptstr);
      $tmptstr = str_replace('{$topicstr}', ii_encode_scripts(ii_htmlencode($trs[ii_cfnames($nfpre,'topic')])), $tmptstr);
      $tmptstr = str_replace('{$url}', $turl, $tmptstr);
      $tmptstr = str_replace('{$genre}', $ngenre, $tmptstr);
      $tmptstr = str_replace('{$time}', ii_get_date($trs[ii_cfnames($nfpre,'time')]), $tmptstr);
      $tmptstr = str_replace('{$id}', ii_get_num($trs[$nidfield]), $tmptstr);
      $tmprstr .= $tmptstr;
    }
  }
  $tmpstr = str_replace('{$cpagestr}', $tcp -> get_pagenum(), $tmpstr);
  $tmpstr  = str_replace('{$genre}', $ngenre, $tmpstr);
  $tmpstr = str_replace(WDJA_CINFO, $tmprstr, $tmpstr);
  $tmpstr = ii_creplace($tmpstr);
  return $tmpstr;
}
?>